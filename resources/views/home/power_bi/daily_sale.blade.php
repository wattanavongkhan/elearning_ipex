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
                
                <a href="{{ route('dashboard.mng') }}" 
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
       const options = {
        series: initialSeries,
        chart: {
            height: 600,
            type: 'area',
            dropShadow: { enabled: true, color: '#000', top: 5, left: 3, blur: 5, opacity: 0.1 },
            toolbar: { show: true },
            events: {
                
                mounted: function(chartContext, config) {
                    generateDataTable(config.config.series, config.config.xaxis.categories);
                },
                updated: function(chartContext, config) {
                    // เมื่อสลับเดือน/ปี ให้วาดตารางใหม่ให้ค่าตรงกันทันที
                    generateDataTable(config.config.series, config.config.xaxis.categories);
                }
            }
        },
          colors: ['#ff3c00', '#3157ff', '#05c23e'], 
        fill: {
            type: ['solid', 'solid', 'solid'],
            opacity: [0.15, 0.04, 0.1] 
        },
        stroke: { 
            curve: 'smooth', // 🎯 เส้นโค้งสมูทพริ้วสวย
            width: [3, 2, 3] 
        },
        dataLabels: { enabled: false }, // ปิดตัวเลขลอยบนเส้นเพื่อให้ดูสะอาดตา
        title: {
            text: 'DAILY SALES PROGRESS (' + '{{ $year }})',
            align: 'left',
            style: { fontSize: '16px', fontWeight: 'bold' }
        },
        grid: {
            borderColor: '#e7e7e7',
            row: { colors: ['#f3f3f3', 'transparent'], opacity: 0.5 },
        },
        markers: { size: 4 },
        xaxis: {
            categories: initialLabels,
            title: { text: 'Day' }
        },
        yaxis: {
            title: { text: 'Sale amount (KTHB)' },
            labels: { 
                formatter: function (val) { 
                    if (val === 0) return '0';
                    // หาร 1000 เพื่อโชว์หน่วย K และตัดเศษทศนิยมให้สวยงาม
                    return (val / 1000).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 1 }) + 'K'; 
                } 
            }
        },
        legend: {
            show: true, 
            position: 'bottom', 
            horizontalAlign: 'left', 
            floating: false, 
            fontSize: '14px', 
            fontWeight: 600,
            onItemClick: { 
                toggleDataSeries: true // จิ้มเปิด-ปิดซ่อนเส้นที่แถบล่างได้ Dynamic
            },
            onItemHover: { highlightDataSeries: true }
        }
    };

    chart = new ApexCharts(document.querySelector("#cumulativeApexChart"), options);
    chart.render();
    }


    function fetchChartData() {
        const year = document.getElementById('select-year').value;
        const month = document.getElementById('select-month').value;

        // เรียกยิงไปยัง API หลังบ้านที่เซ็ตไว้ใน Laravel Controller
        fetch(`/get-dynamic-chart-data?year=${year}&month=${month}`)
            .then(response => response.json())
            .then(data => {
                // เปลี่ยนหัวข้อวันตามเดือนและปีที่เปลี่ยน
                chart.updateOptions({
                    xaxis: { categories: data.labels },
                    title: { text: `Cumulative Production Chart (${month}/${year})` }
                });
                
                // อัปเดตข้อมูลยัดลง 3 เส้นกราฟ
                chart.updateSeries([
                    { name: 'AccumMonthly - PLAN ', data: data.accPlanData },
                    { name: 'AccumAnnual - PLAN ', data: data.accAnnualData },
                    { name: 'AccumActual - PLAN', data: data.accActualData }

                ]);
            })
            .catch(error => console.error('Error fetching data:', error));
    }

   
    function generateDataTable(series, categories) {
        let tableHtml = `<table style="width: 100%; border-collapse: collapse; font-family: Arial; font-size: 12px; text-align: center;">`;
    
        tableHtml += `<tr style="background-color: #f8fafc;">`;
        tableHtml += `<th style="border: 1px solid #b8b8b8; padding: 8px; width: 160px; text-align: left; background-color: #f1f5f9;">Items</th>`;
        categories.forEach(day => { 
            tableHtml += `<th style="border: 1px solid #b8b8b8; padding: 8px; min-width: 45px; background-color: #f1f5f9;">${parseInt(day)}</th>`; 
        });
        tableHtml += `</tr>`;
            
            series.forEach(s => {
                let color = '#3157ff'; 
                if (s.name.includes('AccumMonthly')) color = '#ff3c00';   
                if (s.name.includes('AccumActual')) color = '#069906';

                tableHtml += `<tr>`;
                tableHtml += `<td style="border: 1px solid #b8b8b8; padding: 8px; text-align: left; font-weight: bold; color: ${color};">
                                <span style="display:inline-block; width:15px; height:3px; background:${color}; margin-right:5px; vertical-align:middle;"></span>
                                ${s.name}
                            </td>`;
            s.data.forEach(val => {
                let displayVal = val !== null && val !== undefined && val > 0 ? val.toLocaleString() : '-';
                tableHtml += `<td style="border: 1px solid #b8b8b8; padding: 8px; color: #333;">${displayVal}</td>`;
            });
                tableHtml += `</tr>`;
            });
        
        tableHtml += `</table>`;
        document.getElementById('apexDataTable').innerHTML = tableHtml;
    }

    // ทำงานอัตโนมัติเมื่อเปิดหน้าเว็บครั้งแรก
    document.addEventListener("DOMContentLoaded", function() {
        const labels = {!! json_encode($labels) !!};
        const accPlanData = {!! json_encode($accPlanData) !!};
        const accAnnualData = {!! json_encode($accAnnualData ?? []) !!}; 
        const accActualData = {!! json_encode($accActualData) !!};

        // เริ่มต้นวาดกราฟ
        initChart([
            { name: 'AccumMonthly - PLAN ', data: accPlanData },
            { name: 'AccumAnnual - PLAN', data: accAnnualData },
            { name: 'AccumActual - PLAN', data: accActualData }
        ], labels);

        // ผูกปุ่มกดค้นหาให้เรียกใช้ฟังก์ชันดึงค่าใหม่
        document.getElementById('btn-search').addEventListener('click', fetchChartData);
    });







    // 📋 1. ฟังก์ชันสร้างตารางใต้กราฟ (ปรับแต่งสีให้ตรงกับธีมกราฟเข้มใหม่ของคุณ)
    function generateDataTableInj(series, categories) {
        let tableHtml = `<table style="width: 100%; border-collapse: collapse; font-family: Arial; font-size: 12px; text-align: center;">`;
        
        // แถวหัวคอลัมน์ (Items และ วันที่ 1-30/31)
        tableHtml += `<tr style="background-color: #f8fafc;">`;
        tableHtml += `<th style="border: 1px solid #b8b8b8; padding: 8px; width: 160px; text-align: left; background-color: #f1f5f9;">Items</th>`;
        categories.forEach(day => { 
            // ถอดเครื่องหมาย -- ออกเพื่อให้แสดงเฉพาะตัวเลขวันที่คลีนๆ
            tableHtml += `<th style="border: 1px solid #b8b8b8; padding: 8px; min-width: 45px; background-color: #f1f5f9;">${parseInt(day)}</th>`; 
        });
        tableHtml += `</tr>`;
        
        // วนลูปกรอกแถวข้อมูล (PLAN และ ACTUAL)
        series.forEach(s => {
            let color = '#333'; 
            let rowBg = '#ffffff';

            // แมตช์สีตัวหนังสือและสัญลักษณ์ในตารางให้ตรงกับสีกราฟด้านบน
            if (s.name.includes('PLAN')) {
                color = '#1E3A8A';  // น้ำเงินเข้ม (Deep Navy)
                rowBg = '#fffdec';  // พื้นหลังอมเหลืองอ่อนให้ตัดสายตา
            } else if (s.name.includes('ACTUAL')) {
                color = '#15803D';  // เขียวเข้ม (Forest Green)
                rowBg = '#f4fff4';  // พื้นหลังอมเขียวอ่อน
            }

            tableHtml += `<tr style="background-color: ${rowBg};">`;
            // คอลัมน์ป้ายหัวข้อแถว พร้อมเส้นขีดสีไอคอนหน้าชื่อ
            tableHtml += `<td style="border: 1px solid #b8b8b8; padding: 8px; text-align: left; font-weight: bold; color: ${color};">
                            <span style="display:inline-block; width:15px; height:4px; background:${color}; margin-right:8px; vertical-align:middle; border-radius:2px;"></span>
                            ${s.name}
                        </td>`;
                        
            // คอลัมน์ตัวเลขรายวัน
            s.data.forEach(val => {
                let displayVal = val !== null && val !== undefined && val > 0 ? val.toLocaleString() : '-';
                tableHtml += `<td style="border: 1px solid #b8b8b8; padding: 8px; color: #333;">${displayVal}</td>`;
            });
            tableHtml += `</tr>`;
        });
        
        tableHtml += `</table>`;
        // ส่งตารางไปแสดงผลที่ div id="apexDataTableInj"
        document.getElementById('apexDataTableInj').innerHTML = tableHtml;
    }

    // 📊 2. การตั้งค่ากราฟ ApexCharts (ชุดคำสั่งของคุณ)
    var options = {
    series: [
        { name: 'TOTAL ACC PLAN', data: @json($accPlanDatainj), color: '#A855F7' },    // สีม่วงลาเวนเดอร์เข้ม (Soft Purple)
        { name: 'TOTAL ACC ACTUAL', data: @json($accActualDatainj), color: '#3B82F6' } // สีน้ำเงินสว่างโทนสบายตา (Soft Blue)
    ],
        title: {
            text: "Production Daily Output for Tray Air Spoiler and Ramp in {{ $yearinj }}",
            align: 'left', 
            margin: 20,
            style: {
                fontSize: '18px',
                fontWeight: 'bold',
                color: '#333'
            }
        },
        chart: { 
            type: 'line', 
            height: 400,
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 6,
                left: 2,
                blur: 4,
                opacity: 0.15
            },
            toolbar: { show: true }
        },
        stroke: { 
            width: 4, 
            curve: 'smooth' 
        },
        dataLabels: {
            enabled: true,
            background: {
                enabled: true,
                foreColor: '#fff',
                borderRadius: 3,
                padding: 4,
                opacity: 0.9,
                borderWidth: 1,
                borderColor: '#fff'
            },
            formatter: function (val) {
                return val > 0 ? val.toLocaleString() : '';
            }
        },
        xaxis: { 
            categories: @json($labelsinj),
            title: { text: 'Days' }
        },
        yaxis: {
            title: { text: 'Amount (Pcs)' }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return val !== undefined ? val.toLocaleString() + " Pcs" : '';
                }
            }
        },
        markers: {
            size: 4
        }
    };

    // 🎬 3. สั่งวาดกราฟและตารางเมื่อโหลดหน้าจอเสร็จสมบูรณ์
    document.addEventListener("DOMContentLoaded", function() {
        // วาดกราฟเส้น
        var charts = new ApexCharts(document.querySelector("#injectionChart"), options);
        charts.render();

        // เรียกฟังก์ชันสร้างตารางใต้กราฟทันที โดยดึงข้อมูลมาจากตัวแปรใน options ของกราฟโดยตรง
        generateDataTableInj(options.series, options.xaxis.categories);
    });
    </script>
@endsection
