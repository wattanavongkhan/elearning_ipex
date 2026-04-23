<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I-PEX Elearning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: "Kanit", sans-serif;
            font-weight: 300;
            font-style: normal;
            font-size: 13px;
        }

        .select2-container--default .select2-selection--single {
            border-color: #e2e8f0;
            border-radius: 0.75rem;
            height: 40px;
            padding-top: 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .select2-dropdown {
            border-radius: 1rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        .dataTables_wrapper {
            padding: 10px 0;
        }

        /* เพิ่มระยะห่างระหว่างช่อง Search/Show Entries กับตัวตาราง */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 25px !important;
            /* เพิ่มระยะห่างตรงนี้ */
            padding: 5px 0;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #f1f5f9 !important;
        }

        table.dataTable thead th {
            border-bottom: 1px solid #f1f5f9 !important;
            background-color: #f8fafc;
            color: #94a3b8;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 20px 20px !important;
        }

        table.dataTable tbody td {
            border-bottom: 1px solid #f8fafc !important;
            /* slate-50 จางมาก */
            padding: 15px !important;
            vertical-align: middle;
        }

        /* 2. ปรับแต่งช่อง Search และ Show Entries ให้ดู Modern */
        .dataTables_filter input {
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 8px 16px !important;
            /* เพิ่ม padding ให้ช่องพิมพ์ดูใหญ่ขึ้น */
            outline: none !important;
            background-color: #ffffff;
            margin-left: 10px !important;
            transition: all 0.2s;
        }

        .dataTables_filter input:focus {
            border-color: #3b82f6 !important;
            /* เน้นสีน้ำเงินเวลาคลิก */
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.05);
        }

        .dataTables_length select {
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding: 6px 12px !important;
            outline: none !important;
            background-color: #ffffff;
        }

        /* 3. ส่วนล่างของตาราง (Pagination) */
        .dataTables_wrapper .dataTables_info {
            padding-top: 20px !important;
            color: #94a3b8 !important;
            font-size: 12px;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 15px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6 !important;
            /* สีน้ำเงิน Blue-500 */
            color: white !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 6px 15px !important;
        }

    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-slate-900 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-5 text-2xl font-bold border-b border-slate-800 flex items-center gap-3">
                <img src="{{ URL::asset('images/icont/logo.jpg') }}" alt="I-PEX"
                    style="width: 200px;border-radius: 5px;">
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <p class="text-xs font-semibold text-slate-500 uppercase px-3 mb-2">ควบคุมระบบ</p>
                <a href="{{ route('admin.dashboard.index') }}" class="flex items-center gap-3 p-3  rounded-lg text-white
                @if(Route::currentRouteName() == 'admin.dashboard.index') bg-blue-600 @endif">
                    <i class="fas fa-chart-line w-5 text-center"></i> แดชบอร์ด
                </a>

                <p class="text-xs font-semibold text-slate-500 uppercase px-3 mb-2">จัดการข้อมูลหลัก</p>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition
                    @if(Route::currentRouteName() == 'categories.index' ||
                    Route::currentRouteName() == 'categories.create' ||
                    Route::currentRouteName() == 'categories.edit'
                    ) bg-blue-600 @endif">
                    <i class="fas fa-layer-group w-5 text-center text-slate-400"></i> หมวดหมู่
                </a>
                <a href="{{ route('courses.index') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition
                    @if(Route::currentRouteName() == 'courses.index' || Route::currentRouteName() == 'courses.create' || Route::currentRouteName() == 'courses.edit'
                    || Route::currentRouteName() == 'lessons.index' || Route::currentRouteName() == 'lessons.create' || Route::currentRouteName() == 'lessons.edit'
                    || Route::currentRouteName() == 'questions.index'
                    || Route::currentRouteName() == 'quizzes.create' 
                    || Route::currentRouteName() == 'quizzes.index') bg-blue-600 @endif">
                    <i class="fas fa-book w-5 text-center text-slate-400"></i> คอร์สเรียน <span
                        class="ml-auto bg-yellow-400 text-yellow-900 text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                        {{ $courses_count ?? 0 }}
                    </span>
                </a>
                <p class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2">ผู้ใช้งาน</p>
                <a href="{{ route('students.index') }}"
                    class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition @if(Route::currentRouteName() == 'students.index' || Route::currentRouteName() == 'students.create' || Route::currentRouteName() == 'students.edit') bg-blue-600 @endif">
                    <i class="v fa-brands fa-reddit w-5 text-center text-slate-400"></i> ข้อมูลผู้ใช้งาน
                </a>
                <p class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2">จัดการกิจกรรม</p>

                <a href="{{ route('admin.activities.index') }}"
                    class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition
                @if(Route::currentRouteName() == 'admin.activities.index' || Route::currentRouteName() == 'admin.activities.create') bg-blue-600 @endif">
                    <i class="fas fa-regular fa-newspaper w-5 text-center text-slate-400"></i>
                    ข่าวสารและประกาศ
                </a>
                <p class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2">รายงาน/สรุปผล</p>
                <a href="{{ route('admin.reports.student') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition
                @if(Route::currentRouteName() == 'admin.reports.student') bg-blue-600 @endif">
                    <i class="fas fa-solid fa-chart-area w-5 text-center text-slate-400"></i> รายงานผู้เรียน
                </a>
                <a href="{{ route('admin.reports.course') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition
                @if(Route::currentRouteName() == 'admin.reports.course' || Route::currentRouteName() == 'admin.reports.quiz') bg-blue-600 @endif">
                    <i class="fas fa-solid fa-chart-line w-5 text-center text-slate-400"></i> รายงานคอร์สเรียน
                </a>
                <p class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2">Support</p>
                <a href="{{route('admin.patals.index')}}"
                    class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition
                    @if(Route::currentRouteName() == 'admin.patals.index' || Route::currentRouteName() == 'admin.patals.detail') bg-blue-600 @endif">
                    <i class="fas fa-sliders w-5 text-center text-slate-400"></i> Web Center
                </a>
                {{--  <a href="" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition">
                    <i class="fas fa-cog w-5 text-center text-slate-400"></i> ตั้งค่า
                </a>  --}}

            </nav>

            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3 p-2 bg-slate-800 rounded-lg text-sm">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">A</div>
                    <div>
                        <p class="font-medium">ผู้ดูแลระบบ</p>
                        <p class="text-xs text-slate-400">Super Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">

            <header class="h-16 bg-white border-b flex items-center justify-between px-8 shadow-sm">
                <div class="relative w-96">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-md bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                        placeholder="ค้นหาสถิติหรือคอร์ส...">
                </div>
                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-gray-500 hover:text-blue-600 transition">
                        <i class="fas fa-bell"></i>
                        <span
                            class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    <form action="{{ route('logout') }}" method="post" id="logoutForm">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-gray-700 hover:text-red-600 transition">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>

                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                @yield('content')
                @yield('scripts')
            </main>
        </div>
    </div>
</body>

<script src="{{ asset('assets/js/address-lookup.js') }}"></script>

</html>
