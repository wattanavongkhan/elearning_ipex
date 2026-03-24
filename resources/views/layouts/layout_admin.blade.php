<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I-PEX Elearning</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        body {
            font-family: "Kanit", sans-serif;
            font-weight: 200;
            font-style: normal;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--single {
            background-color: #1e293b;
            /* slate-800 */
            border: 1px solid #334155;
            /* slate-700 */
            border-radius: 1rem;
            height: 52px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white;
            padding-left: 1.5rem;
        }

        .select2-dropdown {
            background-color: #1e293b;
            border: 1px solid #334155;
            color: white;
        }

        .select2-search__field {
            background-color: #0f172a !important;
            /* slate-950 */
            color: white !important;
            border-radius: 0.5rem !important;
        }

    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-slate-900 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-5 text-2xl font-bold border-b border-slate-800 flex items-center gap-3">
                <img src="{{ URL::asset('images/icont/logo.jpg') }}" alt="I-PEX" style="width: 210px;border-radius: 5px;">
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <p class="text-xs font-semibold text-slate-500 uppercase px-3 mb-2">ควบคุมระบบ</p>
                <a href="#" class="flex items-center gap-3 p-3  rounded-lg text-white">
                    <i class="fas fa-chart-line w-5 text-center"></i> แดชบอร์ด
                </a>

                <p class="text-xs font-semibold text-slate-500 uppercase px-3 mb-2">จัดการข้อมูลหลัก</p>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition
                    @if(Route::currentRouteName() == 'categories.index') bg-blue-600 @endif">
                    <i class="fas fa-layer-group w-5 text-center text-slate-400"></i> หมวดหมู่
                </a>
                <a href="{{ route('courses.index') }}"
                    class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition
                    @if(Route::currentRouteName() == 'courses.index' || Route::currentRouteName() == 'courses.create' || Route::currentRouteName() == 'courses.edit'
                    || Route::currentRouteName() == 'lessons.index' || Route::currentRouteName() == 'lessons.create' || Route::currentRouteName() == 'lessons.edit'
                    || Route::currentRouteName() == 'questions.index') bg-blue-600 @endif">
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

                 <a href="{{ route('admin.activities.index') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition
                @if(Route::currentRouteName() == 'admin.activities.index' || Route::currentRouteName() == 'admin.activities.create') bg-blue-600 @endif">
                    <i class="fas fa-regular fa-newspaper w-5 text-center text-slate-400"></i>
                    ข่าวสารและประกาศ
                </a>

                <p class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2">รายงาน/สรุปผล</p>


                <a href="#" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition">
                    <i class="fas fa-solid fa-chart-area w-5 text-center text-slate-400"></i> รายงานผู้เรียน
                </a>
                <a href="#" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition">
                    <i class="fas fa-solid fa-chart-line w-5 text-center text-slate-400"></i> รายงานคอร์สเรียน
                </a>
                <p class="text-xs font-semibold text-slate-500 uppercase px-3 mt-6 mb-2">Support</p>
                <a href="#" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-lg transition">
                    <i class="fas fa-cog w-5 text-center text-slate-400"></i> ตั้งค่า
                </a>
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
