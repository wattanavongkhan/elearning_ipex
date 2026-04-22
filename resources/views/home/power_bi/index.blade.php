@extends('layouts.layout_public')

@section('content')
<style>
    /* ใช้ CSS เดิมของคุณที่สวยอยู่แล้ว และเพิ่มส่วนนี้ครับ */
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
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
        <iframe title="IT Support Request" width="1500" height="800"
            src="https://app.powerbi.com/reportEmbed?reportId=ce9e7eda-25d3-4560-b382-26dbad577286&autoAuth=true&ctid=f381c23b-842e-4f83-87c1-6949b79e4e26"
            frameborder="0" allowFullScreen="true"></iframe>
    </div>
</div>

<div class="min-h-screen bg-[#f8fafc] p-6 font-kanit">
    <button
        class="px-6 py-3 mb-5 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all flex items-center gap-2 shadow-lg shadow-slate-200">
        <i class="fas fa-sync-alt"></i> Refresh Data
    </button>

    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
        @foreach($sections as $section)
        <div
            class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 relative overflow-hidden group">
            <div onclick="openModal('{{ $section->section_no }}')" class="cursor-pointer">
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <div class="flex items-center gap-4">
                        <div
                            class="size-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-500">
                            <i class="fas {{ $section->icon ?? 'fa-chart-simple' }} text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 uppercase italic leading-none">
                                {{ $section->section }}</h3>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Monthly
                                Performance</span>
                        </div>
                    </div>
                    <div class="text-right">
                        {{--  <span class="text-2xl font-black text-slate-900 tracking-tighter">92%</span>  --}}
                        <p class="text-[9px] font-bold text-green-500 uppercase">Efficiency</p>
                    </div>
                </div>
                <div class="h-[180px] mb-6 relative z-10">
                    <canvas id="chart-{{ $section->id }}"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-2 pt-6 border-t border-slate-50 relative z-10">
                    {{--  <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase mb-1">Update by</p>
                        <p class="text-xs font-black text-slate-700 italic">-</p>
                    </div>  --}}
                    <div class="text-center border-x border-slate-100">
                        <p class="text-[9px] font-bold text-slate-400 uppercase mb-1">Update by</p>
                        <p class="text-xs font-black text-blue-600 italic">-</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-200 uppercase mb-1">Status</p>
                        <span
                            class="px-2 py-1 bg-green-50 text-green-600 rounded-md text-[8px] font-black uppercase tracking-tighter italic">Normal</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-3 relative z-20">
                <button onclick="openModal('{{ $section->id }}')" type="button"
                    class="flex-1 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-wider rounded-xl transition-all flex items-center justify-center gap-2 border border-slate-100">
                    <i class="fas fa-table text-blue-500"></i> View Data
                </button>
                @if($section->section_no == Auth::user()->section_id)

                <a href="{{route('dashboard.mng',$section->section_no)}}"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-wider rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-100">
                    <i class="fas fa-cog"></i> Manage
                </a>
                @endif
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

{{--  
<div class="bg-slate-50 min-h-screen pb-24 font-kanit data-grid-bg">

    <section class="relative pt-20 pb-12 overflow-hidden bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="container mx-auto px-6 text-center relative z-10">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                <i class="fas fa-chart-bar"></i> DX 4.0 Data Analytics
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 mb-4 tracking-tight">
                Dashboard <span class="text-bg-blue-600"> Power BI</span>
            </h1>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto font-medium">
                เข้าถึงข้อมูลแบบ Real-time Dashboard ของแต่ละแผนกผ่านระบบ Power BI
            </p>
        </div>
    </section>
    <div class="container mx-auto px-6 mt-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($sections as $dept)
            <div class="bi-card group rounded-[2.5rem] p-8 flex flex-col items-center text-center">
                <div
                    class="w-20 h-20 mb-6 bg-slate-50 text-slate-400 group-hover:bg-blue-600 group-hover:text-white rounded-[2rem] flex items-center justify-center transition-all duration-500 shadow-inner">
                    <i class="{{ $dept->icon ?? 'fas fa-pie-chart' }} text-3xl"></i>
</div>
<h3 class="text-xl font-black text-slate-800 mb-2 group-hover:text-blue-600 transition-colors">
    {{ $dept->section }}
</h3>
<p class="text-sm text-slate-400 mb-6 font-medium">
    รายงานสรุปผลการดำเนินงานและตัวชี้วัดหลัก {{ $dept->id }}
</p>
<a onclick="openModal('{{ $dept->id }}')" class="w-full py-3 px-6 rounded-2xl text-white font-bold text-sm btn-open-bi flex items-center
                    justify-center gap-2 group-hover:scale-105 transition-all">
    <span>Open Dashboard</span>
    <i class="fas fa-external-link-alt text-xs"></i>
</a>
<div
    class="absolute -right-4 -bottom-4 size-24 bg-blue-50 rounded-full blur-2xl opacity-0 group-hover:opacity-60 transition-opacity">
</div>
</div>
@endforeach
</div>
</div>
</div> --}}
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

</script>
@endsection
