@extends('layouts.layout_public')

@section('content')
<div class="bg-slate-50 min-h-screen py-20 font-kanit">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 mb-8 text-center">
            <span class="px-4 py-1 bg-blue-50 text-blue-600 text-xs font-black rounded-full uppercase tracking-widest">
                {{ $quiz->type == 'pre' ? 'Pre-test' : 'Post-test' }}
            </span>
            <h1 class="text-3xl font-black text-slate-900 mt-4">{{ $quiz->title }}</h1>
            <p class="text-slate-400 mt-2">กรุณาตอบคำถามให้ครบทุกข้อเพื่อบันทึกผลการเรียน</p>
        </div>

        <form id="quiz-form">
            @csrf
            <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

            @foreach($questions as $index => $question)
            <div
                class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 mb-6 transition-all hover:shadow-md">
                <div class="flex gap-4">
                    <span
                        class="flex-shrink-0 w-10 h-10 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 leading-relaxed">
                            {{ $question->question_text }}
                        </h3>
                        

                        <div class="space-y-3">
                            {{-- ปรับการ Loop ตาม JSON Options --}}
                            @php
                            // หากไม่ได้ Cast ใน Model ให้ใส่ json_decode($question->options, true)
                            $options = is_array($question->options) ? $question->options :
                            json_decode($question->options, true);

                            $option_images = is_array($question->option_images) ? $question->option_images :
                            json_decode($question->option_images, true);
                            @endphp

                            @foreach($options as $key => $text)
                            <label
                                class="group flex items-center p-4 rounded-2xl border-2 border-slate-50 cursor-pointer transition-all hover:border-blue-200 hover:bg-blue-50/50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                {{-- เก็บค่า Value เป็น A, B, C หรือ D --}}

                                @if(isset($option_images[$key]) && $option_images[$key])
                                <div class="aspect-video w-20 rounded-xl overflow-hidden bg-slate-100 mr-5">
                                    <img src="{{ asset($option_images[$key]) }}"
                                        class="w-20 transition-transform">
                                </div>
                                @endif

                                <input type="radio" name="question_{{ $question->id }}" value="{{ $key }}"
                                    class="hidden peer" required>

                                <div
                                    class="w-6 h-6 rounded-full border-2 border-slate-200 flex items-center justify-center mr-4 peer-checked:border-blue-600 peer-checked:bg-blue-600 transition-all">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>

                                <span class="text-slate-600 group-hover:text-blue-600 font-medium transition-colors">
                                    <strong class="mr-1">{{ $key }}.</strong> {{ $text }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <button type="submit" id="btn-submit"
                class="w-full py-5 bg-blue-600 text-white rounded-[2rem] font-black text-xl shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition-all">
                ส่งคำตอบ <i class="fas fa-paper-plane ml-2"></i>
            </button>
        </form>
    </div>
</div>

{{-- Result Modal (คงเดิม) --}}
<div id="result-modal"
    class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[3rem] p-10 max-w-sm w-full text-center shadow-2xl">
        <div id="result-icon" class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6"></div>
        <h2 id="result-title" class="text-2xl font-black mb-2"></h2>
        <p id="result-score" class="text-4xl font-black text-slate-900 mb-4"></p>
        <p id="result-text" class="text-slate-500 mb-8"></p>
        <button id="btn-action" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black">ตกลง</button>
    </div>
</div>

<script>
    document.getElementById('quiz-form').onsubmit = function (e) {
        e.preventDefault();
        const btn = document.getElementById('btn-submit');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังตรวจคำตอบ...';

        const formData = new FormData(this);

        fetch("{{ route('quiz.submit') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                const modal = document.getElementById('result-modal');
                const icon = document.getElementById('result-icon');
                const title = document.getElementById('result-title');
                const score = document.getElementById('result-score');
                const text = document.getElementById('result-text');
                const btnAction = document.getElementById('btn-action');

                modal.classList.remove('hidden');
                score.innerText = `${data.score}/${data.total}`;

                if (data.passed) {
                    icon.className =
                        "w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6";
                    icon.innerHTML = '<i class="fas fa-check-circle text-4xl"></i>';
                    title.innerText = "ยินดีด้วย คุณสอบผ่าน!";
                    text.innerText = "คุณทำคะแนนได้ยอดเยี่ยม ตอนนี้คุณสามารถเข้าเรียนต่อได้แล้ว";
                    btnAction.innerText = "เข้าเรียนต่อ";

                    // ใช้ตัวแปรที่ส่งมาจาก Controller เพื่อระบุ Route ให้ชัดเจน
                    btnAction.onclick = () => window.location.href ="{{route('profile.index')}}";
                } else {
                    icon.className =
                        "w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6";
                    icon.innerHTML = '<i class="fas fa-times-circle text-4xl"></i>';
                    title.innerText = "พยายามใหม่อีกครั้ง";
                    text.innerText = "คะแนนของคุณยังไม่ถึงเกณฑ์ที่กำหนด ลองทบทวนเนื้อหาและทำใหม่อีกครั้งนะ";
                    btnAction.innerText = "ทำใหม่";
                    btnAction.onclick = () => location.reload();
                }
            })
            .catch(err => {
                alert('เกิดข้อผิดพลาดในการส่งข้อมูล');
                btn.disabled = false;
                btn.innerHTML = 'ส่งคำตอบ <i class="fas fa-paper-plane ml-2"></i>';
            });
    };

</script>
@endsection
