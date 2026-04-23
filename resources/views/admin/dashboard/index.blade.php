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
    
    <div class="group relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-orange-500/10 hover:-translate-y-1.5 transition-all duration-500 overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-running text-7xl rotate-12"></i>
        </div>
        <div class="relative z-10">
            <div class="size-12 bg-orange-500 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-orange-500/30 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-running text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Active Learning</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($learning_count) }}</h3>
                <span class="text-sm font-bold text-slate-400">คน</span>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-orange-400 to-amber-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
    </div>

    <div class="group relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-1.5 transition-all duration-500 overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-check-circle text-7xl rotate-12"></i>
        </div>
        <div class="relative z-10">
            <div class="size-12 bg-emerald-500 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Completed</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($completed_count) }}</h3>
                <span class="text-sm font-bold text-slate-400">คอร์ส</span>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
    </div>

    <div class="group relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-violet-500/10 hover:-translate-y-1.5 transition-all duration-500 overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-book-open text-7xl rotate-12"></i>
        </div>
        <div class="relative z-10">
            <div class="size-12 bg-violet-600 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-violet-500/30 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-book-open text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Courses</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($total_courses) }}</h3>
                <span class="text-sm font-bold text-slate-400">คอร์ส</span>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-violet-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
    </div>

    <div class="group relative bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1.5 transition-all duration-500 overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-users text-7xl rotate-12"></i>
        </div>
        <div class="relative z-10">
            <div class="size-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-users text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Employees</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800 tracking-tight">{{ number_format($total_students) }}</h3>
                <span class="text-sm font-bold text-slate-400">คน</span>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
    </div>

</div>

<div class="mx-auto grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
    <div class="lg:col-span-2 bg-white p-8 rounded-[1rem] shadow-sm border border-slate-50">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">สถิติการเข้าเรียน 6
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

<div class="mx-auto grid grid-cols-1 lg:grid-cols-3 gap-3">
    <div class="bg-white p-8 rounded-[1rem] shadow-sm border border-slate-50">
        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter mb-8 text-center">
            สัดส่วนความสำเร็จ</h3>
        <div class="h-[250px]">
            <canvas id="doughnutChart"></canvas>
        </div>
    </div>

    <div
        class="lg:col-span-2 bg-gradient-to-br from-slate-800 to-slate-900 p-10 rounded-[1rem] shadow-xl relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-white text-3xl font-black mb-4">Ready for DX 4.0?</h2>
            <p class="text-slate-400 text-sm leading-relaxed mb-6 max-w-md">ยินดีด้วย! ระบบ E-learning
                ของคุณกำลังขับเคลื่อนด้วยข้อมูลจริง ข้อมูลพนักงานและสถิติเหล่านี้จะช่วยให้ Khun JJ
                และทีมบริหารตัดสินใจได้อย่างแม่นยำ</p>
            <div class="flex gap-4">
                <button
                    class="px-6 py-3 bg-green-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-green-600 transition-all shadow-lg shadow-green-500/20">Manage
                    Courses</button>
                <button
                    class="px-6 py-3 bg-white/10 text-white border border-white/10 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-white/20 transition-all">Support
                    IT</button>
            </div>
        </div>
        <i class="fas fa-rocket absolute -right-10 -bottom-10 text-[15rem] text-white/5 -rotate-12"></i>
    </div>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('scripts')
<script>
    const chartLabels = @json($labels);
    const chartData = @json($data);

    // 1. Line Chart (สถิติการสมัครเรียน/เข้าเรียน)
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'พนักงานใหม่',
                data: chartData,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 4,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#10b981',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Doughnut Chart (สัดส่วนความสำเร็จ)
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
</script>
@endsection
