@extends('layouts.layout_public')

@section('content')

    <div class="bg-white border-b border-slate-100 pt-16 pb-12">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h3 class="text-3xl font-black text-slate-700 mb-6 tracking-tight">
                    ค้นพบศักยภาพในตัวคุณ
                </h3>
                <p class="text-slate-500 text-lg mb-10">เลือกเรียนรู้ทักษะที่โลกต้องการ กับผู้เชี่ยวชาญตัวจริง</p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 mt-12">
        <div class="flex flex-col lg:flex-row gap-12">

            <aside class="lg:w-1/4 space-y-10">
                <div>
                    <h3 class="font-black text-slate-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-th-large text-blue-500 text-sm"></i> หมวดหมู่เรียน
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('courses.all') }}"
                            class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all {{ !request('category_id') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="font-bold">ทั้งหมด</span>
                            <i class="fas fa-chevron-right text-[10px] opacity-50"></i>
                        </a>

                        @foreach($categories as $cat)
                        <a href="{{ route('courses.all', ['category_id' => $cat->id]) }}"
                            class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all {{ request('category_id') == $cat->id ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span class="font-bold">{{ $cat->category_name }}</span>
                            <span class="text-[10px] opacity-60 bg-black/10 px-2 py-1 rounded-md">
                                {{ $cat->courses_count ?? $cat->courses->count() }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            <div class="lg:w-3/4">
                <div class="flex justify-between items-center mb-10">
                    <p class="text-slate-500 font-medium">แสดงผลทั้งหมด <span
                            class="text-slate-900 font-black">{{ $courses->count() }}</span> รายการ</p>
                    <select class="bg-transparent border-none text-slate-600 font-bold focus:ring-0 cursor-pointer">
                        <option>ล่าสุด</option>
                        {{--  <option>ราคาสูง - ต่ำ</option>
                        <option>ราคาต่ำ - สูง</option>  --}}
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-3 gap-8">
                    @foreach($courses as $course)
                    @php
                    // เช็คสถานะการสมัครของแต่ละคอร์ส
                    $enrollment = Auth::check() ? \App\Models\Enrollment::where('user_id',
                    Auth::id())->where('course_id', $course->id)->first() : null;
                    @endphp

                    <div
                        class="group bg-white rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-900/10 hover:-translate-y-2 transition-all duration-500">
                        <div class="relative aspect-[16/10] overflow-hidden">
                            <img src="{{ asset('storage/'.$course->thumbnail) }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute top-5 right-5">
                                <span
                                    class="px-4 py-1.5 bg-white/90 backdrop-blur-md text-slate-900 rounded-full text-xs font-black shadow-sm">
                                    {{ $course->category_name ?? "General" }}
                                </span>
                            </div>
                            @if($enrollment && $enrollment->status == '1')
                            <div
                                class="absolute inset-0 bg-blue-600/20 backdrop-blur-[2px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span
                                    class="bg-white text-blue-600 px-6 py-2 rounded-full font-black shadow-xl">เรียนต่อไปเลย</span>
                            </div>
                            @endif
                        </div>

                        <div class="p-8">
                            <h3
                                class="text-xl font-black text-slate-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $course->title }}
                            </h3>
                            <p class="text-slate-500 text-sm line-clamp-2 mb-6 leading-relaxed">
                                {{ Str::limit($course->description, 40) }}
                            </p>

                            <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                                @if($enrollment && $enrollment->status == '1')
                                <div class="flex items-center gap-2 text-green-500 font-bold text-sm">
                                    <i class="fas fa-check-circle"></i> ลงทะเบียนแล้ว
                                </div>
                                <a href="{{ route('courses.show', $course->id) }}"
                                    class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                @else
                                <div class="flex flex-col">
                                    <span class="text-2xl font-black text-slate-900">
                                        {{--  {{ $course->price > 0 ? '฿'.number_format($course->price) : 'FREE' }}  --}}
                                    </span>
                                </div>
                                <a href="{{ route('courses.show', $course->id) }}"
                                    class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-blue-600 transition-all shadow-lg shadow-slate-200">
                                    รายละเอียด
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
