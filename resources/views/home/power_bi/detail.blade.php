@extends('layouts.layout_public')

@section('content')

<div class="min-h-screen bg-[#f8fafc] p-6 font-kanit">
    <button
        class="px-6 py-3 mb-5 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all flex items-center gap-2 shadow-lg shadow-slate-200">
        <i class="fas fa-sync-alt"></i> Refresh Data
    </button>

    <iframe title="IT Support Request" width="1500" height="800"
        src="https://app.powerbi.com/reportEmbed?reportId=ce9e7eda-25d3-4560-b382-26dbad577286&autoAuth=true&ctid=f381c23b-842e-4f83-87c1-6949b79e4e26"
        frameborder="0" allowFullScreen="true"></iframe>
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
