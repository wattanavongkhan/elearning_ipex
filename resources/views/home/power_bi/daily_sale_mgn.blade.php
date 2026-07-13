@extends('layouts.layout_public')
@section('content')

<dialog id="biupload-modal"
    class="rounded-[1.5rem] shadow-2xl p-0 w-full max-w-md border-none overflow-hidden backdrop:bg-slate-900/60 backdrop:backdrop-blur-sm">
    <div class="bg-white p-5">
        <h3 class="text-2xl font-black text-slate-800 mb-6  uppercase tracking-tight">Update data</h3>
        <form action="{{ route('daily_sale_mgn.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{--  <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Excel file
                    (.CSV)</label>
                <input type="file" name="file_bi" id="file_bi" required>
            </div>  --}}
             <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Date</label>
                <input type="date" name="date" id="date" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                required>
            </div>
            <div class="mt-3">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Accum Monthly
                    - PLAN</label>
                <input type="number" name="annual_plan" id="annual_plan" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                required>
            </div>
            <div class="mt-3">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Accum Annual 
                    - PLAN</label>
                <input type="number" name="acc_acture" id="acc_acture" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
            </div>
            <div class="mt-3">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Accum Actual
                    - PLAN</label>
                <input type="number" name="acc_annual" id="acc_annual" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
            </div>
            <div class="pt-4 flex gap-3 mt-5">
                <button type="button" onclick="closeBiUploadModal()"
                    class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-2xl font-bold hover:bg-slate-200 transition-all">Cancel</button>
                <button type="submit"
                    class="flex-[1] py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all">Send</button>
            </div>
        </form>
    </div>
</dialog>

<div class="bg-slate-50 min-h-screen p-2">
    <div class="container mx-auto">
        <div class="bg-white rounded-[1.5rem] p-5 mt-5 mb-5 shadow-sm border border-slate-100">
            <form action="{{ route('daily_sale_mgn') }}" method="get" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-10 ">
                    <div class="flex items-center gap-4">
                        <div
                            class="size-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                            <i class="fas fa-database text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-800 tracking-tighter uppercase">Management
                            </h2>
                             <small class="text-slate-400 font-bold mt-0.5">
                                จัดการข้อมูล <span class="text-blue-600"></span>
                            </small>
                        </div>
                    </div>
                </div>
                <div id="event-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">วันที่เริ่ม </label>
                        <input type="date" name="st_date" id="st_date"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">วันที่สิ้นสุด</label>
                        <input type="date" name="end_date" id="end_date"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-2xl shadow-lg transition-all active:scale-95">
                        Show
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-slate-50 min-h-screen">
        <div class="container mx-auto">
            <div class="bg-white rounded-[1.5rem] p-5 mt-5 mb-5 shadow-sm border border-slate-100">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 mb-10 ">
                    <div class="flex items-center gap-4">
                        <div
                            class="size-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                            <i class="fas fa-bar-chart text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-800 tracking-tighter uppercase">Daily sale
                            </h2>
                             <small class="text-slate-400 font-bold mt-0.5">
                                จัดการข้อมูล <span class="text-blue-600"></span>
                            </small>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        {{--  <button onclick="bi_upload()"
                            class="w-full sm:w-auto px-6 py-3.5 bg-yellow-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-yellow-950 hover:-translate-y-0.5 active:scale-95 transition-all shadow-lg shadow-yellow-900/10 flex items-center justify-center gap-3">
                            <span>Add item</span>
                        </button>  --}}

                        <button onclick="bi_upload()"
                            class="w-full sm:w-auto px-6 py-3.5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 hover:-translate-y-0.5 active:scale-95 transition-all shadow-lg shadow-slate-900/10 flex items-center justify-center gap-3">
                            <span>Update</span>
                        </button>
                    </div>
                </div>
                <div class="table-container" style="width: 100%; overflow-x: auto;">
                    <table id="data_bi" class="display nowrap" style="width:100%; min-width: 800px;">
                        <thead>
                            <tr class="text-slate-400 font-black uppercase tracking-widest">
                                <th> Date</th>
                                <th> AccumMonthly</th>
                                <th> AccumAnnual</th>
                                <th> AccumActual</th>
                                <th class="px-6 py-4 text-right"> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale_report as $item)
                            <tr>
                                <td>{{$item->date}}</td>
                                <td>{{ number_format($item->accplan) }}</td>
                                <td>{{ number_format($item->acc_annual) }}</td>
                                <td>{{ number_format($item->acc_acture) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <form action="{{ route('daily_sale_mgn.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('ยืนยันการลบข้อมูล?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition px-3 py-1.5 rounded-lg">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('scripts')
    <script>
        $(document).ready(function () {
            $('#data_bi').DataTable();
        });

        function bi_upload() {
            document.getElementById('biupload-modal').showModal();
        }

        function closeBiUploadModal() {
            document.getElementById('biupload-modal').close();
        }

    </script>
    @endsection
