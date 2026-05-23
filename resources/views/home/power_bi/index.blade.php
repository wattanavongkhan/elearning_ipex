@extends('layouts.layout_public')
@section('content')

<style>
    .bi-card {
        background: white;
        border: 1px solid #e2e8f0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .bi-card:hover {
        transform: translateY(-8px);
        border-color: #3b82f6;
    }

    .btn-open-bi {
        background: linear-gradient(90deg, #2563eb, #4f46e5);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .data-grid-bg {
        background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
        background-size: 20px 20px;
    }
</style>

<div class="min-h-screen bg-[#f8fafc] p-6 font-kanit">
    <div class="grid grid-cols-1 lg:grid-cols-9 sm:grid-cols-4 gap-3">
        <div>
            <a  href="{{route('dashboard.mng')}}" 
                class="px-6 py-3 mb-5 bg-yellow-500 text-white rounded-2xl font-bold text-sm hover:bg-yellow-800 transition-all flex items-center gap-2 shadow-lg shadow-slate-200">
                <i class="fas fa-external-link"></i> Manage data
            </a>
        </div>
        <div>
            <button onclick="location.reload()" type="button"
                class="px-6 py-3 mb-5 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all flex items-center gap-2 shadow-lg shadow-slate-200">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-8">
        @foreach($sections as $section)
        <div
            class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 relative overflow-hidden group">
            <div onclick="openModal('{{ $section->id }}')" class="cursor-pointer">
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <div class="flex items-center gap-4">
                        <div
                            class="size-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-500">
                            <i class="fas {{ $section->icon ?? 'fa-chart-simple' }} text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 uppercase italic leading-none">
                                {{ $section->section }}</h3>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                Performance by Section</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-bold text-green-500 uppercase">Efficiency</p>
                    </div>
                </div>
                <div class="mt-4" style="margin: 0 auto; max-width: 600px;">
                    <div id="chart-{{ $section->id }}"></div>
                </div>
            </div>
        <div
            class="absolute -right-10 -bottom-10 size-40 bg-slate-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700">
        </div>
    </div>
    @endforeach
</div>
</div>

<el-dialog>
    <dialog id="dialog" aria-labelledby="dialog-title"
        class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-slate-950/80 backdrop:backdrop-blur-sm">
        <el-dialog-backdrop
            class="fixed inset-0 bg-slate-900/40 transition-opacity data-closed:opacity-0 data-enter:duration-500 data-enter:ease-out data-leave:duration-300 data-leave:ease-in">
        </el-dialog-backdrop>

        <div tabindex="0" class="flex min-h-full items-center justify-center p-4 text-center focus:outline-none sm:p-6">
            <el-dialog-panel class="relative transform overflow-hidden rounded-[2.5rem] bg-slate-900/90 border border-white/10 text-left shadow-2xl transition-all 
                data-closed:translate-y-8 data-closed:opacity-0 data-enter:duration-500 data-enter:ease-out data-leave:duration-300 data-leave:ease-in 
                w-full max-w-4xl">

                <div class="p-8 sm:p-12">
                    <h3 id="dialog-title"
                        class="text-2xl font-bold text-white mb-8 tracking-tight flex items-center gap-3">
                        <i class="fas fa-pie-chart text-blue-400"></i>
                        Dashboard Power BI - เลือกหัวข้อที่ต้องการดูข้อมูล
                    </h3>
                    <div id="system-1" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-5">
                    </div>
                </div>

                <div class="bg-white/5 px-8 py-6 flex justify-end border-t border-white/5">
                    <button type="button" command="close" commandfor="dialog"
                        class="px-6 py-2.5 rounded-2xl bg-white/10 text-sm font-semibold text-white hover:bg-white/20 transition-colors border border-white/10">
                        ปิดหน้าต่าง
                    </button>
                </div>

            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>
@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById('dialog').showModal();
        let url = "{{ route('dashboardall.show', ':id') }}".replace(':id', id);
        $('#system-1').empty();
        $.get(url, function (res) {
            res.forEach((item, index) => {
                const card = `
        <div class="group relative flex flex-col p-6 bg-white/5 hover:bg-white/10 backdrop-blur-xl rounded-[2.2rem]
                    border border-white/10 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl
                    hover:shadow-blue-500/20 opacity-0 translate-y-4" 
             style="transition-delay: ${index * 100}ms;">
            
            <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2.2rem]"></div>
            
            <a href="${item.powerbi_link}" target="_blank" class="relative z-10 block mb-5">
                <div class="size-12 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white shadow-lg mb-4 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-chart-pie text-white text-xl"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-white font-bold text-base tracking-tight mb-1 group-hover:text-sky-300 transition-colors line-clamp-1">
                        ${item.title}
                    </span>
                    <span class="text-blue-200/40 text-[10px] uppercase font-bold tracking-widest flex items-center gap-2">
                        Click to Open Report <i class="fas fa-external-link-alt text-[8px]"></i>
                    </span>
                </div>
            </a>
        </div>
        `;

                const $card = $(card);
                $('#system-1').append($card);
                setTimeout(() => {
                    $card.removeClass('opacity-0 translate-y-4').addClass(
                        'opacity-100 translate-y-0');
                }, 50);
            });
        });
    }

    document.addEventListener("DOMContentLoaded", function () {

        //HR

        const rawData = @json($data_hr);
        var options = {
            // 1. ดึงตัวเลขมาใส่ในชุดข้อมูลของกราฟแท่ง
            series: [{
                name: "Total Employees",
                data: rawData.map(item => parseFloat(item.total_employees))
            }],
            chart: {
                width: '100%',
                height: 350, // เพิ่มความสูงให้กราฟแท่งมีพื้นที่แสดงผลพอดี
                type: 'bar', // 2. เปลี่ยนประเภทจาก 'pie' เป็น 'bar'
            },
            // 3. ปรับแต่งลูกเล่นของแท่งกราฟ (เช่น แสดงตัวเลขบนหัวเสา และเปิดใช้มุมมนเล็กน้อย)
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '55%',
                    dataLabels: {
                        position: 'top', // แสดงตัวเลขที่ยอดของแท่ง
                    }
                }
            },
            // 4. เปิดแสดงตัวเลขบนหัวเสา
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            // 5. เอาชื่อแผนก (Section) มาเป็นแกน X ด้านล่างแทน Labels ของ Pie
            xaxis: {
                categories: rawData.map(item => item.section),
                position: 'bottom',
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            // 6. กำหนดขอบเขตแกน Y เผื่อพื้นที่ด้านบนไว้ให้ตัวเลขหัวเสาไม่โดนตัด
            yaxis: {
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { show: true }
            },
            // เลือกโทนสีน้ำเงินเท่ๆ สไตล์ Dashboard โรงงาน (สามารถเปลี่ยนสีเดี่ยวได้ตามใจชอบ)
            colors: ['#1E90FF'], 
            title: {
                text: 'Employees Working 6 Days/Week by Section',
                align: 'left',
                margin: 25, // เพิ่ม margin เพื่อให้ตัวกราฟขยับลงมา ไม่เบียดกับชื่อเรื่องตามที่ต้องการก่อนหน้านี้
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                    color: '#444'
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart-3"), options);
        chart.render();


        //IT 
        const dataFromPhp = @json($data_it);
        const options_it = {
            series: [{
                name: 'Count of Requester',
                data: dataFromPhp.map(item => item.total)
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: dataFromPhp.map(item => item.department),
                position: 'bottom',
                labels: {
                    rotate: -90, // หมุนชื่อแผนกแนวตั้งเหมือนในรูป
                    style: {
                        fontSize: '10px'
                    }
                },
                title: {
                    text: 'Department'
                }
            },
            yaxis: {
                title: {
                    text: 'Count of Requester'
                }
            },
            colors: ['#1E90FF'], // สีฟ้าตามแบบ image_54aea6.png
            title: {
                text: 'Count of Requester by Department',
                align: 'left',
                style: {
                    fontSize: '16px',
                }
            }
        };

        const chart_it = new ApexCharts(document.querySelector("#chart-5"), options_it);
        chart_it.render();

        // ISO
        const data_iso = @json($data_iso);
        const options_iso = {
            series: [{
                name: 'Sum of kWh',
                data: data_iso.map(item => item.kwh)
            }, {
                name: 'Sum of Baht',
                data: data_iso.map(item => item.baht)
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: true
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false // ปิดตัวเลขบนแท่งเพื่อความสะอาดเหมือนรูปตัวอย่าง
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: data_iso.map(item => item.year),
                labels: {
                    style: {
                        colors: '#854d0e', // สีน้ำตาลใกล้เคียงกับรูป image_4aa759.png
                        fontWeight: 'bold'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return (value / 1000000).toFixed(1) +
                            "M"; // แปลงเป็นหน่วยล้าน (M) เพื่อให้อ่านง่าย
                    }
                }
            },
            fill: {
                opacity: 1,
                colors: ['#1E90FF', '#00008B'] // สีฟ้าสด และ น้ำเงินเข้ม ตามแบบ
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center'
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 4, // เส้นประแนวนอนเหมือนในรูป
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val.toLocaleString() + " units";
                    }
                }
            },
            title: {
                text: 'Carbon footprint',
                align: 'left',
                style: {
                    fontSize: '16px',
                    color: '#444'

                }
            }
        };

        const chart_iso = new ApexCharts(document.querySelector("#chart-4"), options_iso);
        chart_iso.render();


        //LO

        const data_lo = @json($data_lo);
        var options_lo = {
            series: data_lo.map(item => parseFloat(item.total_value)),
            title: {
                text: 'Sum of Stock RM & Component by Part name Top 5',
                align: 'left',
                style: {
                    fontSize: '16px',
                }
            },
            chart: {
                width: '112%',
                type: 'pie',
            },
            labels: data_lo.map(item => item.part_name),
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    let value = opts.w.globals.series[opts.seriesIndex];
                    let formattedValue = (value / 1000000).toFixed(2) + "M";
                    return formattedValue + " (" + val.toFixed(2) + "%)";
                },
            },
            responsive: [{
                options: {
                    chart: {
                        width: '100%'
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        const chart_lo = new ApexCharts(document.querySelector("#chart-6"), options_lo);
        chart_lo.render();

        // MT
        const data_mt = @json($data_mt);
        const options_mt = {
            series: [{
                name: 'Average of %OEE',
                data: data_mt.map(item => parseFloat(item.avg_oee).toFixed(2))
            }],
            chart: {
                type: 'bar',
                height: 400,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 2,
                    columnWidth: '70%',
                }
            },
            dataLabels: {
                enabled: false // ปิดตัวเลขบนแท่งเพื่อให้เหมือนรูป image_4a2f55.png
            },
            xaxis: {
                categories: data_mt.map(item => item.mc),
                title: {
                    text: 'M/C'
                }
            },
            yaxis: {
                min: 0,
                max: 100,
                tickAmount: 2, // แสดง 0, 50, 100 เหมือนในรูป
                labels: {
                    formatter: function (val) {
                        return val + "%";
                    }
                },
                title: {
                    text: 'Average of Column %OEE'
                }
            },
            colors: ['#1E90FF'], // สีฟ้าสดใส
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 3,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            title: {
                text: 'Average of Column %OEE by M/C',
                align: 'left',
                style: {
                    fontSize: '16px',
                }
            }
        };

        const chart_mt = new ApexCharts(document.querySelector("#chart-7"), options_mt);
        chart_mt.render();

        //PC
        const data_pc = @json($data_pc);
        const options_pc = {
            series: [{
                name: 'Sum of Actual',
                data: data_pc.map(item => parseFloat(item.total_actual))
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    distributed: true, // ทำให้แต่ละแท่งมีสีต่างกันได้
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    // หารด้วย 1,000 เพื่อแสดงเป็นหน่วย K
                    return (val / 1000).toFixed(2) + "K";
                },
                offsetY: -20,
                style: {
                    fontSize: '16px',
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return (val / 1000).toFixed(0) + "K";
                    }
                },
                title: {
                    text: 'Monthly Plan (K)'
                }
            },
            yaxis: {
                // เพิ่มการตั้งค่าขอบเขตแกน Y เพื่อให้มีพื้นที่เหลือสำหรับแสดงตัวเลขบนหัวเสา
                forceNiceScale: true,
                labels: {
                    formatter: function (val) {
                        return (val / 1000000000).toFixed(1) + "bn";
                    }
                },
                title: {
                    text: 'Monthly Plan (THB)'
                }
            },
            grid: {
                padding: {
                    top: 30
                }
            },
            xaxis: {
                categories: data_pc.map(item => item.part_name),
                labels: {
                    style: {
                        fontSize: '10PX'
                    },
                    hideOverlappingLabels: false
                },
                title: {
                    text: 'PartName'
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return (val / 1000000000).toFixed(1) + "bn";
                    }
                },
                title: {
                    text: 'Monthly Plan (THB)'
                }
            },
            // ไล่สีแท่งกราฟจากฟ้าไปเหลืองอ่อนตาม image_49340e.png
            colors: ['#80c6ff', '#e6d690', '#e0e0b0', '#e0e5d0', '#e0f0ff'],
            title: {
                text: 'Sales Value : TOP 5 Item',
                align: 'left',
                style: {
                    fontSize: '16px'
                }
            },
            grid: {
                strokeDashArray: 3,
            },
            legend: {
                show: false
            }
        };

        const chart_pc = new ApexCharts(document.querySelector("#chart-8"), options_pc);
        chart_pc.render();

    });

    //PR
    const data_pr = @json($data_pr);
    const options_pr = {
        series: [{
            name: 'Sum of New Total',
            data: data_pr.map(item => parseFloat(item.total_new_total))
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                columnWidth: '60%',
            }
        },
        xaxis: {
            categories: data_pr.map(item => item.suppliers_name),
            labels: {
                rotate: -45,
                style: {
                    fontSize: '10px'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return (val / 1000000).toFixed(0) + "M";
                }
            },
            title: {
                text: 'Sum of New Total'
            }
        },
        colors: ['#4A8991'], // สีเขียวหัวเป็ดตามรูปต้นฉบับ
        title: {
            text: 'Suppliers TOP 5',
            align: 'left',
            style: {
                fontSize: '16px',
            }
        },
        grid: {
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true
                }
            }
        }
    };

    const chart_pr = new ApexCharts(document.querySelector("#chart-9"), options_pr);
    chart_pr.render();

    // QA
   const data_qa = @json($data_qa);

    // กำหนดสีตามปีให้เหมือนต้นฉบับ
    const yearColors = {
        '2025': '#1E90FF', // ฟ้า
        '2023': '#00008B', // น้ำเงินเข้ม
        '2024': '#E66E33'  // ส้ม
    };

    var options_qa = {
        // 1. ดึงตัวเลขมาใส่ในชุดข้อมูลของกราฟแท่ง
        series: [{
            name: "Claim Count",
            data: data_qa.map(item => parseFloat(item.value))
        }],
        chart: {
            width: '100%',
            height: 380, // เพิ่มความสูงให้เหมาะสมกับกราฟแท่ง
            type: 'bar', // 2. เปลี่ยนประเภทจาก 'pie' เป็น 'bar'
            toolbar: { show: false }
        },
        // 3. ตั้งค่าการแสดงผลของแท่งกราฟและการสลับสีตามปี
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '50%',
                distributed: true, // เปิดใช้เพื่อให้แต่ละแท่งสามารถแยกสีกันได้ตามปี
                dataLabels: {
                    position: 'top', // แสดงตัวเลขบนหัวเสา
                }
            }
        },
        // 4. จับคูู่สีให้ตรงกับปีของแต่ละไอเทมแบบอัตโนมัติ
        colors: data_qa.map(item => yearColors[item.year] || '#666'),
        
        // 5. เปิดแสดงตัวเลขจำนวนเต็มบนหัวเสา (ลบ % ออกเพื่อให้ตรงกับธรรมชาติของกราฟแท่ง)
        dataLabels: {
            enabled: true,
            offsetY: -22,
            style: {
                fontSize: '11px',
                colors: ['#333'],
                fontWeight: 'bold'
            },
            formatter: function (val) {
                return val.toLocaleString(); // แสดงเป็นตัวเลขจำนวนเคลมจริง เช่น 5, 12, 45
            }
        },
        // 6. นำ Label มาเป็นแกน X ด้านล่าง
        xaxis: {
            categories: data_qa.map(item => item.label),
            labels: {
                style: {
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Number of Claims'
            },
            labels: {
                formatter: function (val) {
                    return val.toFixed(0);
                }
            }
        },
        title: {
            text: 'Customer Claim (A), (B), (C) by Year',
            align: 'left',
            margin: 25, // เว้นระยะด้านล่างไม่ให้บังหัวกราฟ
            style: {
                fontSize: '16px',
                fontWeight: 'bold'
            }
        },
        // 7. ซ่อน Legend (คำอธิบายสี) ด้านข้างเพราะชื่อบนแกน X อธิบายชัดเจนอยู่แล้ว
        legend: {
            show: false 
        },
        grid: {
            strokeDashArray: 4,
        }
    };

    const chart_qa = new ApexCharts(document.querySelector("#chart-10"), options_qa);
    chart_qa.render();

    //QA
    const data_qc = @json($data_qc);
    const options_qc = {
        series: [{
            name: "NG Q'ty (pcs)",
            data: data_qc.map(item => item.total_ng)
        }],
        chart: {
            type: 'bar',
            height: 400,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    position: 'top',
                },
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return (val / 1000).toFixed(1) + "K"; // แปลงเป็นหน่วย K (เช่น 2.3K)
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#666"]
            }
        },
        xaxis: {
            // สร้าง Label 2 บรรทัดโดยใช้ Array
            categories: data_qc.map(item => [item.year, item.slip_through_item]),
            labels: {
                style: {
                    fontSize: '11px',
                    fontWeight: 'bold'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return (val / 1000).toFixed(0) + "K";
                }
            },
            title: {
                text: "Sum of NG Q'ty (pcs)"
            }
        },
        colors: ['#1E90FF'], // สีฟ้าสดใสตามรูปต้นฉบับ
        grid: {
            borderColor: '#e7e7e7',
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true
                }
            }
        },
        title: {
            text: "Sum of NG Q'ty (pcs) by Slip through item and Year",
            align: 'left',
            style: {
                color: '#1b1b1b',
                fontSize: '16px'
            }
        }
    };
    const chart_qc = new ApexCharts(document.querySelector("#chart-11"), options_qc);
    chart_qc.render();

    //SO
    const data_so = @json($data_so);
    const options_so = {
        series: data_so.map(item => parseFloat(item.total_qty)),
        labels: data_so.map(item => item.defect),
        chart: {
            type: 'donut',
            height: 400,
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%' // ปรับขนาดวงกลมตรงกลาง
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                // แสดงจำนวน qty และเปอร์เซ็นต์ (เช่น 354 (45.8%))
                const qty = opts.w.globals.series[opts.seriesIndex];
                return qty + " (" + val.toFixed(2) + "%)";
            },
            dropShadow: {
                enabled: false
            },
            style: {
                fontSize: '12px',
                colors: ['#f8f8f8']
            },
            connectorColors: ['#333'],
            // ตั้งค่าให้ Label เด้งออกไปด้านนอกพร้อมเส้นชี้
            distributed: true,
            position: 'outside'
        },
        legend: {
            position: 'right',
            offsetY: 50
        },
        // กำหนดโทนสีน้ำเงิน-ฟ้า ตามรูป image_4770e0.png
        colors: ['#003366', '#0066cc', '#0099ff', '#66ccff', '#13a05a'],
        title: {
            text: 'Top 5 Defect by Year',
            align: 'left',
            style: {
                fontSize: '16px',
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    const chart_so = new ApexCharts(document.querySelector("#chart-12"), options_so);
    chart_so.render();

    //TL
    const data_tl = @json($data_tl);

    // กำหนดสีหลักของแต่ละปี (จับคู่ 2 สีต่อปี สำหรับ Insert และ Repair)
    const colorPalette = {
        '2025': ['#1E90FF', '#33CCFF'], // ฟ้าเข้ม / ฟ้าอ่อน
        '2023': ['#101D8B', '#1E3A8A'], // น้ำเงินมืด / น้ำเงินปกติ
        '2024': ['#E66E33', '#F28C5A']  // ส้มเข้ม / ส้มอ่อน
    };

    const options_tl = {
        // 1. นำข้อมูลตัวเลขใส่เข้าไปในชุดข้อมูลของกราฟแท่ง
        series: [{
            name: 'Cost Amount',
            data: data_tl.map(item => parseFloat(item.value))
        }],
        chart: {
            width: '100%',
            height: 380,
            type: 'bar', // 2. เปลี่ยนเป็นกราฟแท่ง
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '50%',
                distributed: true, // 3. เปิดใช้เพื่อให้สลับสีแต่ละแท่งแยกกันได้อย่างอิสระ
                dataLabels: {
                    position: 'top', // แสดงตัวเลขบนหัวเสา
                }
            }
        },
        // 4. วนลูปสลับสีตามปีและตำแหน่ง Index (คุมโทนให้เหมือน Pie Chart เดิม)
        colors: data_tl.map((item, index) => {
            const colors = colorPalette[item.year] || ['#666'];
            return index % 2 === 0 ? colors[0] : colors[1];
        }),
        dataLabels: {
            enabled: true,
            offsetY: -22, // ขยับตัวเลขขึ้นไปเหนือกราฟเล็กน้อย
            style: {
                fontSize: '11px',
                colors: ['#333'],
                fontWeight: 'bold'
            },
            // 5. แปลงค่าเป็นหน่วย K บนหัวเสา (เช่น 350K) ลบเปอร์เซ็นต์ออกเพื่อให้เหมาะกับกราฟแท่ง
            formatter: function (val) {
                return (val / 1000).toFixed(0) + "K";
            }
        },
        // 6. นำ Label เช่น "Cost Insert (2024)" มาแสดงที่แกน X ด้านล่าง
        xaxis: {
            categories: data_tl.map(item => item.label),
            labels: {
                style: {
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            title: { text: 'Cost (THB)' },
            labels: {
                formatter: function (val) {
                    return (val / 1000).toFixed(0) + "K";
                }
            }
        },
        title: {
            text: 'Cost insert and Cost repair by Year',
            align: 'left',
            margin: 25, // เว้นระยะห่างด้านล่างไม่ให้บังหัวเสากราฟ
            style: {
                fontSize: '16px',
                fontWeight: 'bold'
            }
        },
        // 7. ซ่อน Legend เพราะข้อความที่แกน X ระบุประเภทและปีชัดเจนอยู่แล้วครับ
        legend: {
            show: false 
        },
        grid: {
            strokeDashArray: 4,
        }
    };

    const chart_tl = new ApexCharts(document.querySelector("#chart-13"), options_tl);
    chart_tl.render();

    //FA 
    const options_fa = {
        series: [{
            name: 'external',
            data: @json($externalData_fa)
        }, {
            name: 'internal',
            data: @json($internalData_fa)
        }],
        chart: {
            type: 'bar',
            height: 350,
            stacked: true, // ทำให้กราฟซ้อนกันตาม image_470878.png
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                dataLabels: {
                    position: 'top', // กำหนดให้ยอดรวมอยู่ด้านบน
                }
            },
        },
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#fff']
            },
            offsetY: 10,
            formatter: function (val, opts) {
                // แสดงตัวเลขในแต่ละส่วนของแท่งกราฟ
                return val;
            }
        },
        colors: ['#5C7CEA', '#1E90FF'], // สีฟ้าโทนอ่อนและเข้มตามภาพต้นฉบับ
        xaxis: {
            categories: @json($customers_fa),
            title: {
                text: 'customer'
            }
        },
        yaxis: {
            title: {
                text: 'Count of PCR level'
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            title: {
                text: 'PCR level'
            }
        },
        title: {
            text: 'Summary PCR by Customer',
            align: 'left',
            style: {
                fontSize: '16px',
            }
        },
        grid: {
            borderColor: '#ccc',
            strokeDashArray: 3,
        }
    };

    const chart_fa = new ApexCharts(document.querySelector("#chart-1"), options_fa);
    chart_fa.render();

</script>
@endsection
