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

    const colorMap = {
        'Accum Annual JPY': '#2ec4b6', // Cyan
        'Accum Actual JPY': '#db2777', // Pink
        'Accum PLAN THB': '#1447E6',   // Blue (สลับตามความต้องการ)
        'Accum Annual THB': '#7c3aed', // Purple
        'Accum Actual THB': '#ff7a00'  // Orange
    };

    function initChart(initialSeries, initialLabels) {
        const chartSeries = initialSeries.filter(s => s.name !== 'Accum Progressive Acc').map(s => {
            let cleanData = [...s.data];
            if (s.name.includes('Actual')) {
                let lastValidIndex = cleanData.findLastIndex(v => v !== null && v !== undefined && v > 0);
                for (let i = lastValidIndex + 1; i < cleanData.length; i++) cleanData[i] = null;
            }
            return { name: s.name, type: s.name.includes('JPY') ? 'column' : 'line', data: cleanData };
        });

        const orderedColors = chartSeries.map(s => colorMap[s.name] || '#334155');

        const options = {
            series: chartSeries,
            colors: orderedColors, // ใช้สีที่เรียงตามลำดับ Array
            chart: {
                height: 600, type: 'line', toolbar: { show: true },
                events: {
                    mounted: (ctx, conf) => generateDataTable(initialSeries, conf.config.xaxis.categories),
                    updated: (ctx, conf) => generateDataTable(initialSeries, conf.config.xaxis.categories)
                }
            },
            dataLabels: { 
                enabled: true, 
                enabledOnSeries: [0, 1, 2, 3, 4], 
                style: { colors: orderedColors },
                formatter: function(val, opts) {
                    // 🎯 ดึงชื่อซีรีส์ปัจจุบัน
                    const seriesName = opts.w.config.series[opts.seriesIndex].name;
                    if (seriesName.includes('Accum Annual JPY') || seriesName.includes('Accum Actual JPY')) {
                        return '';
                    }
                    return (val === null || val === 0) ? '' : val.toLocaleString();
                }
            },
            stroke: { curve: 'smooth', width: chartSeries.map(s => s.type === 'column' ? 0 : 3.5) },
            markers: { size: chartSeries.map(s => s.type === 'column' ? 0 : 5), colors: orderedColors, strokeWidth: 2 },
            xaxis: { categories: initialLabels },
           yaxis: [
                { 
                    seriesName: 'Accum PLAN THB', 
                    min: 0, 
                    max: 80000, 
                    tickAmount: 9,
                    title: { text: 'Amount (Baht)', style: { color: '#1447E6' } },
                    labels: { 
                        style: { colors: '#1447E6' },
                        formatter: (v) => v ? v.toLocaleString() : '0' 
                    }
                },
                { seriesName: 'Accum PLAN THB', show: false },
                { 
                    seriesName: 'Accum Annual JPY', 
                    opposite: true, 
                    min: 0, 
                    max: 400000,
                    tickAmount: 8,
                    title: { text: 'Amount (Yen)', style: { color: '#2ec4b6' } },
                    labels: { 
                        style: { colors: '#2ec4b6' },
                        formatter: (v) => v ? v.toLocaleString() : '0' 
                    }
                },
                { seriesName: 'Accum PLAN THB', show: false, opposite: true },
                { seriesName: 'Accum Annual JPY', opposite: true, show: false }
            ]
        };

        chart = new ApexCharts(document.querySelector("#cumulativeApexChart"), options);
        chart.render();
    }

    function generateDataTable(series, categories) {
        let tableHtml = `<table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 9px; text-align: center; table-layout: fixed;">`;

        tableHtml += `<tr style="background-color: #f8fafc;">`;
        tableHtml += `<th style="border: 1px solid #b8b8b8; padding: 5px 7px; width: 14%; text-align: left; background-color: #f1f5f9;">Items</th>`;
        
        const days = {!! json_encode($labels) !!};
        days.forEach(day => { 
            tableHtml += `<th style="border: 1px solid #b8b8b8; padding: 2px 2px; background-color: #f1f5f9;">${parseInt(day)}</th>`; 
        });
        tableHtml += `</tr>`;
            
        series.forEach(s => {
            let isProgressive = s.name.includes('Accum Progressive Acc');
            let color = isProgressive ? '#ef4444' : (colorMap[s.name] || '#334155');

            tableHtml += `<tr>`;
            tableHtml += `<td style="border: 1px solid #b8b8b8; padding: 4px 6px; text-align: left; font-weight: bold; color: ${color}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <span style="display:inline-block; width:10px; height:3px; background:${color}; margin-right:4px; vertical-align:middle;"></span>
                            ${s.name}
                        </td>`;
                        
            s.data.forEach(val => {
                let textStyle = isProgressive ? `color: #ef4444; font-weight: bold;` : `color: #333;`;
                let displayVal = (val !== null && val !== undefined && val > 0) 
                    ? (isProgressive ? val.toLocaleString(undefined, { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + '%' : val.toLocaleString()) 
                    : '';
                tableHtml += `<td style="border: 1px solid #b8b8b8; padding: 4px 2px; ${textStyle}">${displayVal}</td>`;
            });
            tableHtml += `</tr>`;
        });
        
        tableHtml += `</table>`;
        
        document.getElementById('apexDataTable').innerHTML = tableHtml;
    }

    document.addEventListener("DOMContentLoaded", function() {
        const initialSeriesData = [
            { name: 'Accum Annual JPY', data: {!! json_encode($accumJpyData ?? []) !!} },
            { name: 'Accum Actual JPY', data: {!! json_encode($actualJpyData ?? []) !!} },
            { name: 'Accum PLAN THB', data: {!! json_encode($accPlanData) !!} },
            { name: 'Accum Annual THB', data: {!! json_encode($accAnnualData ?? []) !!} },
            { name: 'Accum Actual THB', data: {!! json_encode($accActualData) !!} },
            { name: 'Accum Progressive Acc', data: {!! json_encode($progressive ?? []) !!} }
        ];

        initChart(initialSeriesData, {!! json_encode($labels) !!});
    });
</script>
@endsection
