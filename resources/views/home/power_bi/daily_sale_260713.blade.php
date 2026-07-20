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
    // 🎯 1. จัดการข้อมูล Actual: ตัดท้ายข้อมูลหลังจากวันที่ไม่มีผลงานจริง ให้เปลี่ยนเป็น null เพื่อไม่ให้เส้นดิ่งลงเลข 0
    const chartSeries = initialSeries.filter(s => s.name !== 'Accum Progressive Acc').map(s => {
        let cleanData = [...s.data];
        
        if (s.name.includes('Actual')) {
            let lastValidIndex = -1;
            // วนลูปหา index ตัวสุดท้ายที่มีข้อมูลมากกว่า 0
            for (let i = cleanData.length - 1; i >= 0; i--) {
                if (cleanData[i] !== null && cleanData[i] !== undefined && cleanData[i] > 0) {
                    lastValidIndex = i;
                    break;
                }
            }
            
            // เคลียร์ค่าหลังจากวันสุดท้ายที่มีการบันทึกงานให้เป็น null ทั้งหมด
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
            height: 600,
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
        // 🎯 2. จัดการเปิด-ปิด Data Labels แบบเจาะจงผ่าน Formatter (ปลอดภัยกว่าการแก้ Object ใน Array ข้อมูล)
        dataLabels: { 
            enabled: true,
            enabledOnSeries: [0, 1, 2, 3, 4], 
            style: {
                fontSize: '10px',
                fontWeight: 'bold',
                colors: ['#1447E6', '#7c3aed', '#2ec4b6', '#ff7a00', '#db2777']
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                padding: 3,
                borderRadius: 2,
                borderWidth: 1,
                borderColor: '#fff',
                opacity: 0.85
            },
            formatter: function (val, opts) {
                // หากไม่มีค่า หรือค่าเป็น 0 ไม่ต้องแสดงตัวเลขอยู่แล้ว
                if (val === null || val === undefined || val === 0) return '';
                
                // ดึงชื่อของซีรีส์ปัจจุบัน และความยาวทั้งหมดของข้อมูลซีรีส์นั้น ๆ
                const seriesName = opts.w.config.series[opts.seriesIndex].name;
                const totalPoints = opts.w.config.series[opts.seriesIndex].data.length;

                // ❌ บังคับปิด: ถ้ากำลังจะวาดข้อมูลของซีรีส์ 'Accum Annual JPY' และเป็นจุดสุดท้ายของเดือน (Index ตัวท้ายสุด) ให้ส่งค่าว่างกลับไป
                {{--  if (seriesName === 'Accum Annual JPY' && opts.dataPointIndex === totalPoints - 1) {  --}}
                if (seriesName === 'Accum Annual JPY') {
                    return ''; 
                }

                return val.toLocaleString(); // จุดอื่น ๆ และซีรีส์อื่น แสดงผลตัวเลขยอดเต็มปกติ
            },
            offsetY: -10 
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
            colors: ['#1447E6', '#7c3aed', '#2ec4b6', '#ff7a00', '#db2777'], 
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
                labels: { style: { colors: '#00b4d8' }, formatter: (v) => v ? v.toLocaleString() : '0' }
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
            fontSize: '14px', fontWeight: 600, labels: { colors: '#475569' }
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
    document.getElementById('btn-search').addEventListener('click', fetchChartData);
});
    </script>
@endsection
