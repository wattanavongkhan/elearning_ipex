@extends('layouts.layout_login')

@section('content')
<style>
    /* ปรับแต่งสีของ Video Control ให้เข้ากับธีม */
    video::-webkit-media-controls-panel {
        background-image: linear-gradient(transparent, rgba(15, 23, 42, 0.9));
    }

    video::-webkit-media-controls-play-button,
    video::-webkit-media-controls-current-time-display,
    video::-webkit-media-controls-time-remaining-display,
    video::-webkit-media-controls-timeline,
    video::-webkit-media-controls-mute-button,
    video::-webkit-media-controls-volume-slider,
    video::-webkit-media-controls-fullscreen-button {
        filter: invert(1);
    }

    #video-player {
        outline: none;
    }

    /* ซ่อน Scrollbar สำหรับ Sidebar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 5px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 2px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #475569;
    }

    

</style>

<div class="bg-slate-900 min-h-screen font-kanit">
    <div class="bg-slate-800 border-b border-slate-700 py-4 px-5">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('profile.index', $course->id) }}"
                    class="text-slate-600 hover:text-white transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <h1 class="text-white font-bold truncate max-w-md">{{ $course->title }}</h1>
            </div>

            <div class="hidden md:flex items-center gap-3">
                @php
                $totalLessons = $course->lessons->count();
                $doneCount = count($completedLessons ?? []);
                $percentage = $totalLessons > 0 ? round(($doneCount / $totalLessons) * 100) : 0;
                @endphp
                <span class="text-slate-400 text-xs uppercase tracking-widest font-black">Progress:
                    {{ $percentage }}%</span>
                <div class="w-32 h-2 bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 transition-all duration-700 ease-out"
                        style="width: {{ $percentage }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row">
        <div class="flex-1 p-6 lg:p-5">
            @if($currentLesson)
            @if($currentLesson->pre_quiz && !$userDonePreQuiz)
            <div
                class="bg-slate-800 rounded-[2.5rem] p-12 text-center border border-blue-500/30 shadow-2xl shadow-blue-500/10">
                <div id="qrcode" class="p-4 bg-white rounded-xl inline-block shadow-lg mb-5"></div>
                <br>
                <h2 class="text-2xl font-black text-white mb-2">แบบทดสอบก่อนเรียน</h2>
                <p class="text-slate-400 mb-8">กรุณาทำแบบทดสอบเพื่อวัดพื้นฐานก่อนเข้าสู่บทเรียน</p>
                <a href="{{ route('quiz.show', $currentLesson->pre_quiz->id) }}"
                    class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-black hover:bg-blue-700 transition-all">
                    เริ่มทำแบบทดสอบ
                </a>
            </div>
            @else
            <div class="relative aspect-video bg-black rounded-[2rem] overflow-hidden shadow-2xl">
                <video id="video-player" controls preload="none" autoplay muted controlsList="nodownload"
                    class="absolute inset-0 w-full h-full object-contain"
                    poster="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : asset('images/default-poster.jpg') }}">
                    @if($currentLesson->video_url!=null && $currentLesson->video_url!="")
                    <source src="{{ asset('storage/'.$currentLesson->video_url) }}" type="video/mp4">
                    @else
                    <source src="{{ asset('storage/'.$currentLesson->video_path) }}" type="video/mp4">
                    Your browser does not support the video tag.
                    @endif
                </video>
            </div>
            {{-- 3. เช็คหลังเรียนจบ (Post-test) --}}
            @if($currentLesson->post_quiz)
            <div id="post-quiz-trigger"
                class="hidden mt-8 p-8 bg-green-600/10 border border-green-500/20 rounded-3xl text-center">
                <h3 class="text-white font-bold mb-4">ยินดีด้วย! คุณดูวิดีโอจบแล้ว</h3>
                <a href="{{ route('quiz.show', $currentLesson->post_quiz->id) }}"
                    class="inline-block px-6 py-3 bg-green-600 text-white rounded-xl font-black">
                    ทำแบบทดสอบหลังเรียน
                </a>
            </div>
            @endif
            <div class="mt-8 text-white">
                <h2 class="text-2xl font-black mb-4">{{ $currentLesson->title }}</h2>
                <div class="prose prose-invert max-w-none text-slate-400">
                    {!! $currentLesson->content !!}
                </div>
                @if($file->count() > 0)
                <div class="mt-8 border-t border-slate-800 pt-6">
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <i class="fas fa-paperclip mr-2 text-blue-500"></i>
                        เอกสารประกอบการเรียน (Downloads)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($file as $files)
                        <a href="{{ asset('storage/' . $files->file_path) }}" target="_blank"
                            class="flex items-center p-4 bg-slate-800/50 rounded-2xl border border-slate-700 hover:border-blue-500 hover:bg-slate-800 transition-all group">

                            <div
                                class="w-10 h-10 rounded-xl bg-slate-700 flex items-center justify-center mr-4 group-hover:bg-blue-600 transition-colors">
                                @if(Str::endsWith($files->file_name, ['.pdf']))
                                <i class="fas fa-file-pdf text-red-400 group-hover:text-white"></i>
                                @elseif(Str::endsWith($files->file_name, ['.doc', '.docx']))
                                <i class="fas fa-file-word text-blue-400 group-hover:text-white"></i>
                                @elseif(Str::endsWith($files->file_name, ['.xls', '.xlsx']))
                                <i class="fas fa-file-excel text-green-400 group-hover:text-white"></i>
                                @else
                                <i class="fas fa-file-alt text-slate-400 group-hover:text-white"></i>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-200 truncate">{{ $files->file_name }}</p>
                                <p class="text-xs text-slate-500">คลิกเพื่อดาวน์โหลด</p>
                            </div>

                            <div class="text-slate-600 group-hover:text-blue-500">
                                <i class="fas fa-download"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif
            @else
            <div
                class="aspect-video bg-slate-800 rounded-[2.5rem] flex items-center justify-center text-slate-500 italic">
                ขออภัย ยังไม่มีบทเรียนในคอร์สนี้
            </div>
            @endif
        </div>

        <div
            class="w-full lg:w-96 bg-slate-800 border-l border-slate-700 h-auto lg:h-[calc(100vh-73px)] overflow-y-auto">
            <div class="p-6 border-b border-slate-700">
                <h3 class="text-white font-black flex items-center gap-2">
                    <i class="fas fa-list-ul text-blue-500"></i> เนื้อหาหลักสูตร
                </h3>
            </div>

            <div class="divide-y divide-slate-700/50">
                @foreach($course->lessons as $i => $lesson)
                @php
                $isCompleted = in_array($lesson->id, $completedLessons ?? []);
                @endphp

                <a href="{{ route('courses.learn', [$course->id, $lesson->id]) }}"
                    class="flex items-center gap-4 p-5 hover:bg-slate-700/50 transition-all {{ $currentLesson->id == $lesson->id ? 'bg-blue-600/10 border-l-4 border-blue-500' : '' }}">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-black
                            {{ $currentLesson->id == $lesson->id ? 'bg-blue-600 text-white' : ($isCompleted ? 'bg-green-500/20 text-green-500' : 'bg-slate-900 text-slate-500') }}">
                        @if($isCompleted)
                        <i class="fas fa-chevron-circle-right text-[10px]"></i>
                        @else
                        {{ $i + 1 }}
                        @endif
                    </div>

                    <div class="flex-1">
                        <p
                            class="text-sm font-bold {{ $currentLesson->id == $lesson->id ? 'text-white' : ($isCompleted ? 'text-slate-500' : 'text-slate-400') }}">
                            {{ $lesson->title }}
                        </p>

                        <div class="flex items-center gap-2 mt-1">
                            <span
                                class="text-[10px] {{ $isCompleted ? 'text-green-500/60' : 'text-slate-500' }} uppercase tracking-tighter">
                                {{ $isCompleted ? 'Completed' : 'Lesson ' . ($i + 1) }}
                            </span>

                            @if($currentLesson->id == $lesson->id)
                            {{-- บทที่กำลังเรียนอยู่ ให้ ID ไว้สำหรับ JS อัปเดตตัวเลข --}}
                            <span id="current-lesson-percent" class="text-[10px] text-blue-400 font-bold">0%</span>
                            @elseif($isCompleted)
                            {{-- บทที่จบแล้ว ให้แสดง 100% ค้างไว้ตลอด --}}
                            <span class="text-[10px] text-green-500 font-bold">100%</span>
                            @endif
                        </div>
                    </div>

                    @if($currentLesson->id == $lesson->id)
                    <i class="fas fa-play-circle text-blue-500 animate-pulse"></i>
                    @elseif($isCompleted)
                    <i class="fas fa-check-circle text-green-500"></i>
                    @else
                    <i class="far fa-circle text-slate-700"></i>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>

   document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('video-player');
    const percentText = document.getElementById('current-lesson-percent');
    const nextUrl = "{{ $nextLessonUrl }}";
    const storageKey = `video_progress_user_{{ auth()->id() }}_course_{{ $course->id }}_lesson_{{ $currentLesson->id }}`;

    let watchedTime = 0;
    let isSaving = false;

    if (video) {
        const attemptPlay = () => {
            video.muted = false;
            
            video.play().then(() => {
                console.log("Autoplay success!");
            }).catch(error => {
                console.log("Autoplay prevented. Waiting for user interaction.");
                const playAfterClick = () => {
                    video.play();
                    document.removeEventListener('click', playAfterClick);
                };
                document.addEventListener('click', playAfterClick);
            });
        };

        video.addEventListener('loadedmetadata', function () {
            const savedTime = localStorage.getItem(storageKey);
            if (savedTime) {
                video.currentTime = parseFloat(savedTime);
                watchedTime = parseFloat(savedTime);
            }
            attemptPlay();
        });

        video.addEventListener('timeupdate', function () {
            if (!video.seeking && video.currentTime > watchedTime) {
                watchedTime = video.currentTime;
            }

         
            if (video.duration > 0) {
                const percentage = Math.floor((video.currentTime / video.duration) * 100);
                if (percentText) { percentText.innerText = percentage + "%"; }
            }
            if (Math.floor(video.currentTime) % 2 === 0) {
                localStorage.setItem(storageKey, video.currentTime);
            }
        });

/*
        video.addEventListener('seeking', function () {
            if (video.currentTime > watchedTime) {
                video.currentTime = watchedTime;
            }
        });
        */

        video.onended = function () {
            const hasPostQuiz = {{ $currentLesson->post_quiz_id ? 'true' : 'false' }};
            const alreadyPassed = {{ $hasDonePostQuiz ? 'true' : 'false' }};

            console.log(hasPostQuiz,alreadyPassed);
            if (hasPostQuiz && !alreadyPassed) {
                const quizBox = document.getElementById('post-quiz-trigger');
                if (quizBox) {
                    quizBox.classList.remove('hidden');
                    quizBox.scrollIntoView({ behavior: 'smooth' });
                }
            } else {
                saveProgressAndNext();
            }
            localStorage.removeItem(storageKey);
        };
    }

    // ฟังก์ชันกลางสำหรับส่งเปอร์เซ็นต์
    function updateEnrollmentPercentage() {
        if (video.duration > 0) {
            const percentage = Math.floor((video.currentTime / video.duration) * 100);
            
            // ใช้ navigator.sendBeacon สำหรับกรณีปิดหน้าจอ (จะส่งข้อมูลได้ชัวร์กว่า fetch ธรรมดา)
            const url = "{{ route('course.enrollment.update_percent') }}";
            const data = JSON.stringify({
                _token: "{{ csrf_token() }}",
                course_id: "{{ $course->id }}",
                progress_percent: percentage
            });

            if (navigator.sendBeacon) {
                const blob = new Blob([data], { type: 'application/json' });
                navigator.sendBeacon(url, blob);
            } else {
                // fallback สำหรับ browser เก่า
                fetch(url, {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: data
                });
            }
        }
    }

    // 1. ดักจับตอนสลับ Tab หรือย่อหน้าต่าง (Visibility Change)
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'hidden') {
            updateEnrollmentPercentage();
        }
    });

    // 2. ดักจับตอนปิด Tab หรือ Refresh (Before Unload)
    window.addEventListener('beforeunload', function() {
        updateEnrollmentPercentage();
    });

   function saveProgressAndNext() 
   {
    if (isSaving) return;
    isSaving = true;

    // 1. ดักเช็คก่อนว่าบทเรียนนี้มี Post-Quiz และผู้ใช้ทำหรือยัง (เช็คจาก Blade Variable)
    const hasPostQuiz = {{ $currentLesson->post_quiz_id ? 'true' : 'false' }};
    const alreadyPassed = {{ $hasDonePostQuiz ? 'true' : 'false' }};
    if (hasPostQuiz && alreadyPassed) {
        const quizBox = document.getElementById('post-quiz-trigger');
        if (quizBox) {
            quizBox.classList.remove('hidden');
            quizBox.scrollIntoView({ behavior: 'smooth' });
        }
        isSaving = false;
        return;
    }

    // 2. ถ้าไม่มีควิซ หรือทำผ่านแล้ว ถึงจะรันส่วนการบันทึก Progress
    fetch("{{ route('course.progress.update') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            lesson_id: "{{ $currentLesson->id }}",
            course_id: "{{ $course->id }}"
        })
    })
    .then(response => response.json())
    .then(data => {
        if (nextUrl) {
            window.location.href = nextUrl;
        } else {
            alert("ยินดีด้วย! คุณเรียนจบหลักสูตรนี้แล้ว");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        isSaving = false;
    });
}
});

$(document).ready(function () {
    @if($currentLesson->pre_quiz && !$userDonePreQuiz)
        generateQRCode();
    @endif
});

function generateQRCode() {
    const qrcodeContainer = document.getElementById('qrcode');
    if (qrcodeContainer) {
        const quizUrl = "{{ route('quiz.show', $currentLesson->pre_quiz->id ?? 0) }}";
        new QRCode(qrcodeContainer, {
            text: quizUrl,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
        });
    }
}

</script>
@endsection
