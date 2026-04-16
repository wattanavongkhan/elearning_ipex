@extends('layouts.layout_public')
@section('content')
<dialog id="modal-data" class="rounded-[2.5rem] shadow-2xl p-0 w-full max-w-md border-none overflow-hidden backdrop:bg-slate-900/60 backdrop:backdrop-blur-sm">
    <div class="bg-white p-10">
        <h3 class="text-2xl font-black text-slate-800 mb-6 italic uppercase tracking-tight">Record Data</h3>
        
        <form id="dataForm" class="space-y-5">
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Target Amount</label>
                <input type="number" name="target" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-bold" placeholder="เช่น 1200">
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Actual Amount</label>
                <input type="number" name="actual" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-bold" placeholder="เช่น 1150">
            </div>
            
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="document.getElementById('modal-data').close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-2xl font-bold hover:bg-slate-200 transition-all">Cancel</button>
                <button type="submit" class="flex-[2] py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</dialog>

<div class="bg-slate-50 min-h-screen py-5">
    <div class="container mx-auto px-6">
        <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-slate-100">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-10 ">

                <div class="flex items-center gap-4">
                    <div
                        class="size-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                        <i class="fas fa-database text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 tracking-tighter uppercase">Data Management
                        </h2>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-0.5">
                            จัดการข้อมูลแผนก <span class="text-blue-600"></span>
                        </p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full sm:w-64 group">
                        <select name="category_id" id="category_id"
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all appearance-none">
                            <option value="">-- เลือกรายการ --</option>
                            @foreach($dash_link as $type)
                            <option value="{{$type->id}}">{{$type->title}}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                    <button onclick="openAddModal()"
                        class="w-full sm:w-auto px-6 py-3.5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 hover:-translate-y-0.5 active:scale-95 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-3">
                        <i class="fas fa-cloud-upload-alt text-sm"></i>
                        <span>Upload data</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">


                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
                            <th class="px-6 pb-2">Period (Month/Year)</th>
                            <th class="px-6 pb-2">Target</th>
                            <th class="px-6 pb-2">Actual</th>
                            <th class="px-6 pb-2">Efficiency (%)</th>
                            <th class="px-6 pb-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
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