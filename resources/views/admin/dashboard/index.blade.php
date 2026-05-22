@extends('layouts.layout_admin')
@section('content')
<div class=" mx-auto mb-5 flex flex-col md:flex-row md:items-center justify-between gap-2">
    <div>
        <h1 class="text-xl font-black text-slate-800 tracking-tighter uppercase">
            I-PEX <span class="text-violet-500 underline decoration-violet-200 underline-offset-4">Command Center</span>
        </h1>
        <p class="text-[11px] text-slate-400 font-bold tracking-[0.1em] uppercase mt-1">Digital Transformation &
            Learning Analytics</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="px-4 py-2 bg-white rounded-xl shadow-sm border border-slate-100 text-xs font-bold text-slate-500">
            <i class="fas fa-calendar-alt mr-2 text-violet-500"></i> {{ date('d M Y') }}
        </span>
    </div>
</div>
<div class="mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div
        class="group relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-orange-500/10 hover:-translate-y-1.5 transition-all duration-500 overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-running text-7xl rotate-12"></i>
        </div>
        <div class="relative z-10">
            <div
                class="size-12 bg-orange-500 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-orange-500/30 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-running text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Active Learning</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($learning_count) }}</h3>
                <span class="text-sm font-bold text-slate-400">คน</span>
            </div>
        </div>
        <div
            class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-orange-400 to-amber-500 opacity-0 group-hover:opacity-100 transition-opacity">
        </div>
    </div>

    <div
        class="group relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-1.5 transition-all duration-500 overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-check-circle text-7xl rotate-12"></i>
        </div>
        <div class="relative z-10">
            <div
                class="size-12 bg-emerald-500 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Learning Completed</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($completed_count) }}</h3>
                <span class="text-sm font-bold text-slate-400">รายการ</span>
            </div>
        </div>
        <div
            class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity">
        </div>
    </div>

    <div
        class="group relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-violet-500/10 hover:-translate-y-1.5 transition-all duration-500 overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-book-open text-7xl rotate-12"></i>
        </div>
        <div class="relative z-10">
            <div
                class="size-12 bg-violet-600 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-violet-500/30 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-book-open text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Courses</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($total_courses) }}</h3>
                <span class="text-sm font-bold text-slate-400">คอร์ส</span>
            </div>
        </div>
        <div
            class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-violet-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity">
        </div>
    </div>

    <div
        class="group relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1.5 transition-all duration-500 overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-users text-7xl rotate-12"></i>
        </div>
        <div class="relative z-10">
            <div
                class="size-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-users text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Employees</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($total_students) }}</h3>
                <span class="text-sm font-bold text-slate-400">คน</span>
            </div>
        </div>
        <div
            class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
        </div>
    </div>
</div>

<div class="mx-auto grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
    <div class="lg:col-span-2 bg-white p-8 rounded-[1rem] shadow-sm border border-slate-50">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">สถิติการเข้าเรียน 3
                เดือนล่าสุด</h3>
            <i class="fas fa-chart-line text-green-500"></i>
        </div>
        <div class="h-[320px]">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-8 rounded-[1rem] shadow-sm border border-slate-50">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Top Learners</h3>
            <i class="fas fa-medal text-amber-500"></i>
        </div>
        <div class="space-y-6">
            @foreach($top_learners as $index => $learner)
            <div class="flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div
                        class="size-10 rounded-xl bg-slate-50 flex items-center justify-center font-black text-sm {{ $index == 0 ? 'text-amber-500 bg-amber-50' : 'text-slate-400' }}">
                        #{{ $index + 1 }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-700 group-hover:text-green-600 transition-colors">
                            {{ $learner->full_name_th }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                            {{ $learner->course_count }} คอร์สที่ลงเรียน</p>
                    </div>
                </div>
                @if($index == 0)
                <i class="fas fa-crown text-amber-400"></i>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 lg:col-span-1 p-5 flex flex-col justify-between">
        <div>
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-8 text-center">สัดส่วนความสำเร็จ</h3>
            <div class="h-[250px] relative">
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 lg:col-span-2 p-5 flex flex-col justify-between">
        <div>
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-8 text-center">สถิติและแนวโน้มผู้เข้าเรียน</h3>
            <div class="h-[250px] relative">
                <canvas id="splineAreaChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@section('scripts')
<script>
    const chartLabels = @json($labels); // เช่น ['Mar', 'Apr', 'May']
    const chartDataSets = @json($datasets); 

    const ctxBar = document.getElementById('lineChart').getContext('2d'); // ใช้ IDเดิมจาก HTML ได้เลยครับ
    const modernColors = [
        { bg: '#10b981', hover: '#059669' }, // เขียว
        { bg: '#3b82f6', hover: '#2563eb' }, // ฟ้า
        { bg: '#f59e0b', hover: '#d97706' }, // ส้ม
        { bg: '#8b5cf6', hover: '#7c3aed' }, // ม่วง
        { bg: '#ec4899', hover: '#db2777' }  // ชมพู
    ];

    const formattedDatasets = chartDataSets.map((dataset, index) => {
        const color = modernColors[index % modernColors.length];
        return {
            label: dataset.label, // ชื่อคอร์ส
            data: dataset.data,   // [10, 20, 15]
            backgroundColor: color.bg,
            hoverBackgroundColor: color.hover,
            borderRadius: 6, // ทำมุมแท่งกราฟให้โค้งมนดูโมเดิร์น
            borderWidth: 0
        };
    });

    new Chart(ctxBar, {
        type: 'bar', 
        data: {
            labels: chartLabels,
            datasets: formattedDatasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        usePointStyle: true, // เปลี่ยนสัญลักษณ์สี่เหลี่ยมเป็นวงกลมเล็กๆ
                        font: { family: 'Kanit', size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { family: 'Kanit', size: 13 },
                    bodyFont: { family: 'Kanit', size: 13 },
                    callbacks: {
                        label: function(context) {
                            return ` ${context.dataset.label}: ${context.raw} คน`;
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { borderDash: [5, 5], color: '#e2e8f0' },
                    ticks: { 
                        stepSize: 1, 
                        font: { family: 'Kanit' } 
                    } 
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { family: 'Kanit' } }
                }
            }
        }
    });

    const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['กำลังเรียน', 'เรียนจบสมบูรณ์'],
            datasets: [{
                // ดึงค่ามาจาก Controller
                data: [{{ $learning_count }}, {{ $completed_count }}],
                backgroundColor: [
                    '#f59e0b', // สีส้ม (Active)
                    '#10b981'  // สีเขียว (Completed)
                ],
                hoverOffset: 20,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%', // ทำเป็นวงแหวนบางๆ สไตล์ Modern
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: { family: 'Kanit', size: 12, weight: 'bold' }
                    }
                },
                // เพิ่มส่วนกลางวงกลม (Custom Tooltip)
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} คน (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });


    document.addEventListener('DOMContentLoaded', function() 
    {
    // ดึงข้อมูล Array ที่ส่งมาจาก Controller
    const labelsData = @json($months_labels);
    const studentsData = @json($students_count);

    const ctx = document.getElementById('splineAreaChart').getContext('2d');
    
    // สร้าง Gradient Color สำหรับพื้นหลังไล่เฉดสีให้ดูหรูหราขึ้น
    const emeraldGradient = ctx.createLinearGradient(0, 0, 0, 400);
    emeraldGradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');  // สีเขียวมรกตจางด้านบน
    emeraldGradient.addColorStop(1, 'rgba(16, 185, 129, 0.00)');  // ไล่ลงไปจนโปร่งใสสนิทล่างสุด

    new Chart(ctx, {
        type: 'line', // ใน Chart.js กราฟพื้นที่ใช้อิงจากประเภท line
        data: {
            labels: labelsData,
            datasets: [{
                label: 'จำนวนผู้เข้าเรียน (คน)',
                data: studentsData,
                
                // 🔥 คีย์เวิร์ดสำคัญทำกราฟโค้งนุ่ม (Spline Area)
                tension: 0.4,           // ปรับความโค้ง (0 = เส้นตรง, 1 = โค้งงอมาก) ค่า 0.4 กำลังสวยที่สุดครับ
                fill: true,             // เปิดใช้งานพื้นที่ระบายสีใต้เส้นกราฟ
                backgroundColor: emeraldGradient, // ใช้สีไล่เฉดที่ทำไว้ด้านบน
                
                // ตั้งค่าเส้นขอบกราฟ
                borderColor: '#10b981', // สีเขียวมรกตเข้ม
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // ปิดป้ายหัวข้อเนื่องจากเรามีชื่อการ์ดอยู่แล้ว
                },
                tooltip: {
                    backgroundColor: '#1e293b', // พื้นหลัง Tooltip สีเข้มสไตล์โมเดิร์น
                    titleFont: { family: 'Kanit', size: 13, weight: 'bold' },
                    bodyFont: { family: 'Kanit', size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return ` ผู้เข้าเรียน: ${context.raw} คน`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false }, // ซ่อนเส้นตารางแนวตั้งเพื่อให้กราฟคลีน
                    ticks: { font: { family: 'Kanit', size: 12 }, color: '#64748b' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' }, // ให้เส้นตารางแนวนอนเป็นสีเทาจางมาก ๆ
                    ticks: { 
                        font: { family: 'Kanit', size: 12 }, 
                        color: '#64748b',
                        callback: function(value) { return value + ' คน'; } // ใส่หน่วยต่อท้ายตัวเลขแกน Y
                    }
                }
            }
        }
    });
});
</script>
@endsection
