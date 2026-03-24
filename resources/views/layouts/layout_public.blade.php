<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I-PEX Elearning|แหล่งเรียนรู้ออนไลน์</title>

    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        kanit: ['Kanit', 'sans-serif'],
                    },
                    container: {
                        center: true,
                        padding: '1rem',
                    },
                }
            }
        }

    </script>

    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }

        .sticky-nav {
            position: sticky;
            top: 0;
            z-index: 50;
        }

        /* ตกแต่ง Scrollbar ให้ดูทันสมัย */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            bg: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

    </style>
</head>

<body class="bg-slate-50 text-slate-900 font-kanit">

    <nav class="bg-white border-b border-slate-100 sticky-nav shadow-sm">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex h-20 items-center justify-between gap-8">

                <a href="/" class="flex-shrink-0 flex items-center gap-2">
                    <img src="{{ URL::asset('images/icont/logo.jpg') }}" alt="I-PEX" style="width: 220px">
                </a>
                <div class="hidden md:flex flex-1 max-w-xl relative">
                    <form action="#" method="GET" class="w-full">
                        <input type="text" name="search" placeholder="วันนี้คุณอยากเรียนรู้อะไร..."
                            class="w-full bg-slate-100 border border-transparent rounded-2xl py-3 pl-12 pr-4 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm outline-none">
                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </form>
                </div>
                <div class="hidden lg:flex items-center gap-8 text-[15px] font-semibold text-slate-600">
                    <a href="{{ route('categories.all') }}"
                        class="hover:text-blue-600 transition flex items-center gap-2">
                        <i class="fas fa-th-large text-xs text-slate-400"></i> หมวดหมู่
                    </a>
                    <a href="{{ route('courses.all') }}" class="hover:text-blue-600 transition flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-xs text-slate-400"></i> คอร์สทั้งหมด
                    </a>
                    <a href="{{ route('courses.all') }}" class="hover:text-blue-600 transition flex items-center gap-2">
                        <i class="fas fa-folder-open text-xs text-slate-400"></i> ระบบอื่นๆ
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    @guest
                    <a href="{{ route('login') }}"
                        class="hidden sm:block text-sm font-bold text-slate-600 px-4 py-2 hover:bg-slate-50 rounded-xl transition">เข้าสู่ระบบ</a>
                    <a href="{{ route('login') }}"
                        class="text-sm font-bold bg-blue-600 text-white px-6 py-3 rounded-2xl hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all active:scale-95">เริ่มเรียนเลย</a>
                    @else
                    <div class="flex items-center gap-5">
                        <a href="#" class="text-slate-400 hover:text-blue-600 relative transition">
                            <i class="far fa-bell text-xl"></i>
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full border-2 border-white flex items-center justify-center font-bold">2</span>
                        </a>
                        <div class="relative group">
                            <button
                                class="flex items-center gap-3 pl-4 border-l border-slate-100 hover:text-blue-600 transition">
                                <div class="text-right hidden sm:block">
                                    <p class="text-xs font-bold text-slate-800 leading-none">{{ Auth::user()->name }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 mt-1">ผู้เรียน</p>
                                </div>
                                <div
                                    class="h-10 w-10 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-100">
                                    {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                                </div>
                            </button>

                            <div
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="p-4 border-b border-slate-100">
                                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-400 mt-1">
                                        {{ Auth::user()->email ?? 'user@example.com' }}</p>
                                </div>
                                <div class="p-2">
                                    <a href="{{ route('profile.index') }}"
                                        class="block px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 rounded-lg transition flex items-center gap-2">
                                        <i class="fas fa-user text-xs"></i> โปรไฟล์ของฉัน
                                    </a>

                                </div>
                                <div class="p-2 border-t border-slate-100">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition flex items-center gap-2">
                                            <i class="fas fa-sign-out-alt text-xs"></i> ออกจากระบบ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main class="min-h-[70vh]">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-100 pt-20 pb-10">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-16">
                <div class="space-y-6">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-15 h-8 flex items-center justify-center">
                            <img src="{{ URL::asset('images/icont/logo.jpg') }}" alt="I-PEX" style="width: 200px;">
                        </div>
                    </a>
                    <p class="text-slate-500 text-sm leading-loose">
                        เราคือแพลตฟอร์มการเรียนรู้ยุคใหม่ที่มุ่งเน้นการเสริมสร้างทักษะดิจิทัล
                        เพื่อให้คุณก้าวทันโลกที่ไม่เคยหยุดนิ่ง
                    </p>
                    <div class="flex gap-4">
                        <a href="#"
                            class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-all"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-all"><i
                                class="fab fa-line"></i></a>
                        <a href="#"
                            class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-all"><i
                                class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div>
                    <h5 class="font-bold text-slate-800 mb-8 uppercase tracking-widest text-xs">คอร์สยอดนิยม</h5>
                    <ul class="text-sm text-slate-500 space-y-4">
                        <li><a href="#" class="hover:text-blue-600 flex items-center gap-2"><i
                                    class="fas fa-chevron-right text-[10px] opacity-30"></i> Web Development</a></li>
                        <li><a href="#" class="hover:text-blue-600 flex items-center gap-2"><i
                                    class="fas fa-chevron-right text-[10px] opacity-30"></i> Data Analytics</a></li>
                        <li><a href="#" class="hover:text-blue-600 flex items-center gap-2"><i
                                    class="fas fa-chevron-right text-[10px] opacity-30"></i> UX/UI Design</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-bold text-slate-800 mb-8 uppercase tracking-widest text-xs">เกี่ยวกับเรา</h5>
                    <ul class="text-sm text-slate-500 space-y-4">
                        <li><a href="#" class="hover:text-blue-600">ร่วมงานกับเรา</a></li>
                        <li><a href="#" class="hover:text-blue-600">คำถามที่พบบ่อย</a></li>
                        <li><a href="#" class="hover:text-blue-600">นโยบายความเป็นส่วนตัว</a></li>
                    </ul>
                </div>

                {{-- <div>
                    <h5 class="font-bold text-slate-800 mb-8 uppercase tracking-widest text-xs">ติดดาวน์โหลดแอป</h5>
                    <div class="flex flex-col gap-3">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" class="h-11 w-fit cursor-pointer hover:opacity-80 transition">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" class="h-11 w-fit cursor-pointer hover:opacity-80 transition">
                    </div>
                </div> --}}
            </div>

            <div
                class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-400 font-medium">
                <p>© 2026 I-PEX Platform. ยกระดับการเรียนรู้โดยคนไทย</p>
                <div class="flex gap-8">
                    <a href="#" class="hover:text-slate-800 transition">Terms of Service</a>
                    <a href="#" class="hover:text-slate-800 transition">Cookies Settings</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
