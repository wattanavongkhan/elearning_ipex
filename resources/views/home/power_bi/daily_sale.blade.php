@extends('layouts.layout_public')
@section('content')


<div class="min-h-screen bg-[#f8fafc] p-5 font-kanit flex flex-col gap-6">
    <div class="bg-white rounded-[2rem] p-7 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 relative overflow-hidden group">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 relative z-10">
            
            <form action="{{ route('daily_sale') }}" method="get" enctype="multipart/form-data" class="flex items-center gap-3 m-0">
                @csrf
                <div>
                    <select id="select-month" name="month"
                        class="px-4 py-2 border border-slate-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-700 font-medium cursor-pointer shadow-sm">
                        @foreach($months as $value => $name)
                        <option value="{{ $value }}" {{ $value == $month ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" id="btn-search"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-100 transition-all cursor-pointer flex items-center gap-2">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>

            <div class="flex flex-wrap items-center gap-2">
                <a href="https://app.powerbi.com/onedrive/open?pbi_source=ODSPViewer&driveId=b!tJvp-biuakugxRjZbrcpI1gW6iVGSjpMog9DdVhQwo0P7RL4Cq8kSpU06YLc4Sgt&itemId=01DR3UH7AS67KKDGO36BH247HHDQ2GCHLB" 
                   target="_blank"  class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-bold shadow-md shadow-green-100 transition-all cursor-pointer inline-flex items-center gap-2">
                    <i class="fas fa-chart-bar"></i> Power BI
                </a>
                
                <a href="{{route('daily_sale_mgn')}}"
                    class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold shadow-md shadow-amber-100 transition-all cursor-pointer inline-flex items-center gap-2">
                    <i class="fas fa-external-link-alt"></i> Manage data
                </a>
            </div>

        </div>

        <div class="relative z-10">
            <div id="cumulativeApexChart" class="w-full"></div>
            <div id="apexDataTable" class="mt-5 overflow-x-auto"></div>
        </div>
        <div class="absolute -right-10 -bottom-10 size-40 bg-slate-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    let chart;
 function initChart(initialSeries, initialLabels) {
    const chartSeries = initialSeries.filter(s => s.name !== 'Accum Progressive Acc').map(s => {
        let cleanData = [...s.data];
        
        if (s.name.includes('Actual')) {
            let lastValidIndex = -1;
            for (let i = cleanData.length - 1; i >= 0; i--) {
                if (cleanData[i] !== null && cleanData[i] !== undefined && cleanData[i] > 0) {
                    lastValidIndex = i;
                    break;
                }
            }
            for (let i = lastValidIndex + 1; i < cleanData.length; i++) {
                cleanData[i] = null;
            }
        }

        if (s.name.includes('JPY')) {
            return { name: s.name, type: 'column', data: cleanData };
        }
        return { name: s.name, type: 'line', data: cleanData };
    });

    const options = {
        series: chartSeries,
        chart: {
            height: 700,
            type: 'line',
            dropShadow: { enabled: true, color: '#000', top: 5, left: 3, blur: 5, opacity: 0.1 },
            toolbar: { show: true },
            events: {
                mounted: function(chartContext, config) {
                    generateDataTable(initialSeries, config.config.xaxis.categories);
                },
                updated: function(chartContext, config) {
                    generateDataTable(initialSeries, config.config.xaxis.categories);
                }
            }
        },
      // 🎯 จัดการ dataLabels: เอาพื้นหลังออก และยังคงเยื้องหลบซ้าย-ขวาตามปกติ
        dataLabels: { 
            enabled: true,
            enabledOnSeries: [0, 1, 2, 3, 4], 
            textAnchor: 'middle', 
            style: {
                fontSize: '9px',
                fontWeight: 'bold',
                colors: ['#1447E6', '#7c3aed', '#2ec4b6', '#ff7a00', '#db2777']
            },
            background: {
               enabled: true,
                padding: 1,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#888686',
                opacity: 0.90
            },
            formatter: function (val, opts) {
                if (val === null || val === undefined || val === 0) return '';
                const seriesName = opts.w.config.series[opts.seriesIndex].name;
                
                if (seriesName === 'Accum Annual JPY') {
                    return ''; 
                }

                const formattedVal = val.toLocaleString();
                const dayIndex = opts.dataPointIndex;

                const planSeries = initialSeries.find(s => s.name === 'Accum PLAN THB');
                const annualSeries = initialSeries.find(s => s.name === 'Accum Annual THB');
                const actualSeries = initialSeries.find(s => s.name === 'Accum Actual THB');

                if (planSeries && annualSeries && actualSeries) {
                    const planVal = planSeries.data[dayIndex];
                    const annualVal = annualSeries.data[dayIndex];
                    const actualVal = actualSeries.data[dayIndex];

                    // เช็คว่ามีวันไหนที่วิ่งมาทับกันบ้าง (< 2500)
                    const isPlanAnnualOverlap = (planVal !== null && annualVal !== null && Math.abs(planVal - annualVal) < 2500);
                    const isPlanActualOverlap = (planVal !== null && actualVal !== null && Math.abs(planVal - actualVal) < 2500);
                    const isAnnualActualOverlap = (annualVal !== null && actualVal !== null && Math.abs(annualVal - actualVal) < 2500);

                    if (isPlanAnnualOverlap || isPlanActualOverlap || isAnnualActualOverlap) {
                        
                        // 🔹 1. เส้น PLAN: ดีดไปทางซ้ายของ Marker
                        if (seriesName === 'Accum PLAN THB') {
                            return formattedVal + '                 '; 
                        }
                        
                        // 🔹 2. เส้น Annual: ดีดไปทางขวาของ Marker
                        if (seriesName === 'Accum Annual THB') {
                            return '                 ' + formattedVal; 
                        }

                        // 🔹 3. เส้น Actual THB: อยู่ตรงกลาง Marker 
                        if (seriesName === 'Accum Actual THB') {
                            return formattedVal; 
                        }
                    }
                }

                // สำหรับกราฟแท่งหรือจุดที่ไม่มีการทับซ้อน ให้อยู่ตรงกลางปกติ
                return formattedVal; 
            },
           // 🎯 เช็คการซ้อนทับโดยคำนวณจากสัดส่วนความสูงของกราฟ (เสมือนเช็คพิกัดพิกเซลของ Marker)
            offsetX: function(val, opts) {
                if (opts === undefined || opts.seriesIndex === undefined) return 0;

                const dayIndex = opts.dataPointIndex;
                const currentSeriesName = opts.w.config.series[opts.seriesIndex].name;

                // 1. ดึงข้อมูลดิบ THB ทั้ง 3 เส้น
                const planSeries = initialSeries.find(s => s.name === 'Accum PLAN THB');
                const annualSeries = initialSeries.find(s => s.name === 'Accum Annual THB');
                const actualSeries = initialSeries.find(s => s.name === 'Accum Actual THB');

                if (planSeries && annualSeries && actualSeries) {
                    const planVal = planSeries.data[dayIndex];
                    const annualVal = annualSeries.data[dayIndex];
                    const actualVal = actualSeries.data[dayIndex];

                    // 2. ดึงค่าสูงสุด (Max) ของแกน Y ปัจจุบันจากออปชันมาคำนวณ (ในโค้ดของคุณตั้งไว้ที่ 80,000)
                    const yAxisMax = 80000; 

                    // 3. กำหนดความไว (Threshold): ถ้าค่าต่างกันน้อยกว่า 3% ของความสูงกราฟ แปลว่าบนหน้าจอ Marker ทับกันแน่ๆ
                    const overlapLimit = yAxisMax * 0.03; // 3% ของ 80,000 คือ 2,400

                    // ตรวจเช็คว่ามีคู่ใดคู่หนึ่งซ้อนทับกันหรือไม่
                    const isPlanAnnualOverlap = (planVal !== null && annualVal !== null && Math.abs(planVal - annualVal) < overlapLimit);
                    const isPlanActualOverlap = (planVal !== null && actualVal !== null && Math.abs(planVal - actualVal) < overlapLimit);
                    const isAnnualActualOverlap = (annualVal !== null && actualVal !== null && Math.abs(annualVal - actualVal) < overlapLimit);

                    // 4. ถ้า Marker เบียดกันจนซ้อนทับ สั่งดีดกล่องข้อความออกซ้าย-ขวาทันที
                    if (isPlanAnnualOverlap || isPlanActualOverlap || isAnnualActualOverlap) {
                        if (currentSeriesName === 'Accum PLAN THB') {
                            return formattedVal + '                 '; // ดีดซ้าย
                        }
                        if (currentSeriesName === 'Accum Annual THB') {
                            return '                 ' + formattedVal; // ดีดขวา
                        }
                    }
                }

                return 0; // ถ้า Marker อยู่ห่างกันเกิน 3% ให้แสดงตรงกลางปกติ
            },
            offsetY: -7 
        },
        plotOptions: {
            line: {
                dataLabels: {
                    hideOverflowingLabels: true 
                }
            },
            bar: {
                dataLabels: {
                    hideOverflowingLabels: true
                }
            }
        },
        colors: ['#1447E6', '#7c3aed', '#2ec4b6', '#ff7a00', '#db2777'], 
        fill: { type: 'solid', opacity: [1, 1, 0.85, 1, 0.85] },
        stroke: { curve: 'smooth', width: [3.5, 3.5, 0, 3.5, 0] },
        grid: {
            borderColor: '#f1f5f9', 
            row: { colors: ['#f8fafc', 'transparent'], opacity: 0.5 },
        },
        markers: { 
            size: [5, 5, 0, 5, 0], 
            {{--  colors: ['#1447E6', '#7c3aed', '#2ec4b6', '#ff7a00', '#db2777'],   --}}
            strokeColors: '#ffffff', 
            strokeWidth: 2,
            hover: { sizeOffset: 2 }
        },
        xaxis: {
            categories: initialLabels,
            axisBorder: { show: false }, 
            axisTicks: { show: false },  
            title: { text: 'Day', style: { color: '#64748b' } },
            labels: { style: { colors: '#64748b' } }
        },
        yaxis: [
            {
                seriesName: 'Accum PLAN THB',
                min: 0, max: 80000, stepSize: 10000,
                title: { text: 'Amount (Baht)', style: { color: '#64748b' } },
                labels: { style: { colors: '#1447E6' }, formatter: (v) => v ? v.toLocaleString() : '0' }
            },
            { seriesName: 'Accum PLAN THB', show: false, min: 0, max: 80000, stepSize: 10000 },
            {
                seriesName: 'Accum Annual JPY',
                opposite: true,
                min: 0, max: 350000, stepSize: 50000,
                title: { text: 'Amount (Yen)', style: { color: '#64748b' } },
                labels: { style: { colors: '#2ec4b6' }, formatter: (v) => v ? v.toLocaleString() : '0' }
            },
            { seriesName: 'Accum PLAN THB', show: false, min: 0, max: 80000, stepSize: 10000 },
            { seriesName: 'Accum Annual JPY', opposite: true, show: false, min: 0, max: 350000, stepSize: 50000 }
        ],
        annotations: {
            position: 'back',
            texts: [
                {
                    x: 15, y: 12,
                    text: 'UNIT : K THB',
                    textAnchor: 'start',
                    style: { color: '#ef4444', fontSize: '13px', fontWeight: 'bold', fontFamily: 'Arial, sans-serif' }
                }
            ]
        },
        legend: {
            show: true, position: 'bottom', horizontalAlign: 'left',
            fontSize: '12px', fontWeight: 600, labels: { colors: '#475569' }
        }
    };

    chart = new ApexCharts(document.querySelector("#cumulativeApexChart"), options);
    chart.render();
}
function generateDataTable(series, categories) {
    let tableHtml = `<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11px; text-align: center; table-layout: auto;">`;

    tableHtml += `<tr style="background-color: #f8fafc;">`;
    tableHtml += `<th style="border: 1px solid #b8b8b8; padding: 4px 6px; width: 130px; text-align: left; background-color: #f1f5f9;">Items</th>`;
    
    categories.forEach(day => { 
        tableHtml += `<th style="border: 1px solid #b8b8b8; padding: 4px 2px; background-color: #f1f5f9;">${parseInt(day)}</th>`; 
    });
    tableHtml += `</tr>`;
        
    series.forEach(s => {
        let color = '#334155';
        let isProgressive = s.name.includes('Accum Progressive Acc');

        if (s.name.includes('Accum PLAN THB')) color = '#00b4d8';   
        else if (s.name.includes('Accum Annual THB')) color = '#7c3aed';
        else if (s.name.includes('Accum Annual JPY')) color = '#2ec4b6';
        else if (s.name.includes('Accum Actual THB')) color = '#ff7a00';
        else if (s.name.includes('Accum Actual JPY')) color = '#db2777';
        else if (isProgressive) color = '#ef4444'; // 🎯 3. เปลี่ยนแถว Progressive Acc ในตารางข้อมูลให้แสดงเป็นสีแดงตามตัวอย่างภาพแรก

        tableHtml += `<tr>`;
        tableHtml += `<td style="border: 1px solid #b8b8b8; padding: 4px 6px; text-align: left; font-weight: bold; color: ${color}; line-height: 1.2;">
                        <span style="display:inline-block; width:10px; height:3px; background:${color}; margin-right:4px; vertical-align:middle;"></span>
                        ${s.name}
                     </td>`;
                     
        s.data.forEach(val => {
            let displayVal = '';
            // 🎯 4. ปรับเปลี่ยนการแสดงสีตัวเลขของเปอร์เซ็นต์ Progressive ให้กลายเป็นสีแดงเข้มสดใสสะดุดตา
            let textStyle = isProgressive ? `color: #ef4444; font-weight: bold;` : `color: #333;`;

            if (val !== null && val !== undefined && val > 0) {
                if (isProgressive) {
                    displayVal = val.toLocaleString(undefined, { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + '%';
                } else {
                    displayVal = val.toLocaleString();
                }
            }
            tableHtml += `<td style="border: 1px solid #b8b8b8; padding: 4px 2px; ${textStyle}">${displayVal}</td>`;
        });
        tableHtml += `</tr>`;
    });
    
    tableHtml += `</table>`;
    document.getElementById('apexDataTable').innerHTML = tableHtml;
}

document.addEventListener("DOMContentLoaded", function() {
    const labels = {!! json_encode($labels) !!};
    const accPlanData = {!! json_encode($accPlanData) !!};
    const accAnnualData = {!! json_encode($accAnnualData ?? []) !!}; 
    const accumJpyData = {!! json_encode($accumJpyData ?? []) !!};
    const accActualData = {!! json_encode($accActualData) !!};
    const actualJpyData = {!! json_encode($actualJpyData ?? []) !!};
    const progressiveData = {!! json_encode($progressive ?? []) !!};

    const initialSeriesData = [
        { name: 'Accum PLAN THB', data: accPlanData },
        { name: 'Accum Annual THB', data: accAnnualData },
        { name: 'Accum Annual JPY', data: accumJpyData },
        { name: 'Accum Actual THB', data: accActualData },
        { name: 'Accum Actual JPY', data: actualJpyData },
        { name: 'Accum Progressive Acc', data: progressiveData } 
    ];

    initChart(initialSeriesData, labels);
    {{--  document.getElementById('btn-search').addEventListener('click', fetchChartData);  --}}
});

    </script>
@endsection
