@extends('layouts.layout_public')
@section('content')

<dialog id="biupload-modal"
    class="rounded-[2.5rem] shadow-2xl p-0 w-full max-w-md border-none overflow-hidden backdrop:bg-slate-900/60 backdrop:backdrop-blur-sm">
    <div class="bg-white p-5">
        <h3 class="text-2xl font-black text-slate-800 mb-6  uppercase tracking-tight">Upload data</h3>
        <form action="{{ route('dashboard.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-5">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Tabale
                    name</label>
                <select name="table_name" id="table_name"
                    class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold text-slate-700">
                    @foreach ($tabale_name as $item)
                    <option value="{{$item->table_name}}">{{$item->table_des}}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Excel file
                    (.xlsx)</label>
                <input type="file" name="file_bi" id="file_bi" required>
            </div>
            <div class="hidden space-y-3">
                <input type="text" name="id" id="id" value="{{$id}}">
                <input type="text" name="table" id="table" value="{{$table}}">
            </div>
            <div class="pt-4 flex gap-3 mt-5">
                <button type="button"
                    class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-2xl font-bold hover:bg-slate-200 transition-all">Cancel</button>
                <button type="submit"
                    class="flex-[1] py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all">Send</button>
            </div>
        </form>
    </div>
</dialog>

<div class="bg-slate-50 min-h-screen py-2 p-5" style="font-size: 13px;">
    <div class="container mx-auto px-2">
        <div class="bg-white rounded-[1.5rem] p-3 shadow-sm border border-slate-100">
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
                    <form action="{{ route('dashboard.mng', ['id' => $id]) }}" method="GET" class="w-full sm:w-auto">
                        <div class="relative w-full sm:w-64 group">
                            <select name="category_id" id="category_id" onchange="this.form.submit()"
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold text-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all appearance-none">
                                <option value="">-- เลือกรายการ --</option>
                                @foreach($dash_link_all as $type)
                                <option value="{{$type->id}}">{{$type->title}}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none">
                                <i class="fas fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>
                    </form>
                    <button onclick="bi_upload({{$id}},'{{$table}}')"
                        class="w-full sm:w-auto px-6 py-3.5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 hover:-translate-y-0.5 active:scale-95 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-3">
                        <i class="fas fa-cloud-upload-alt text-sm"></i>
                        <span>Upload data</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-2" id="data_bi">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
                            @foreach($columns as $key => $value)
                            <th class="py-1 px-4">{{ ucwords(str_replace('_', ' ', $value->Field)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                        <tr>
                            @foreach($columns as $column)
                            <td class="py-1 px-4">{{ $row->{$column->Field} }}</td>
                            @endforeach
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
        $('#data_bi').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });

    function bi_upload() {
        document.getElementById('biupload-modal').showModal();
    }

</script>
@endsection
