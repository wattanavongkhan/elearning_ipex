@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">คำถามใน: {{ $quiz->title }}</h3>
            <p class="text-sm text-gray-500">ทั้งหมด {{ $questions->count() }} ข้อ</p>
        </div>
        <a href="{{ route('admin.questions.create', $quiz->id) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-sm transition-all">
            <i class="fas fa-plus mr-1"></i> เพิ่มคำถามใหม่
        </a>
    </div>

    <div class="space-y-4">
        @forelse($questions as $index => $question)
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">
                            ข้อที่ {{ $index + 1 }}
                        </span>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ $question->question_text }}</h4>

                    @if($question->question_image)
                    <div class="mb-5">
                        <img src="{{ asset($question->question_image) }}"
                            class="object-cover rounded-lg shadow" style="max-width: 100%; max-height: 200px;">
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($question->options as $key => $value)
                        <div
                            class="p-3 rounded-xl border {{ $question->correct_answer == $key ? 'bg-green-50 border-green-200 
                            text-green-700 font-bold' : 'bg-gray-50 border-gray-100 text-gray-600' }}">
                            <span class="mr-2">{{ $key }}.</span> {{ $value }}
                            @if($question->correct_answer == $key)
                            <i class="fas fa-check-circle ml-2"></i>
                            @endif
                            <br>
                          @if(isset($question->option_images[$key]))
                         <img src="{{ asset($question->option_images[$key]) }}"
                                    class="object-cover rounded-lg shadow" style="max-width: 100px; max-height: 50px;">
                        
                            @endif 
                        </div>
                         {{--  @if(isset($question->option_images[$key]))
                         <img src="{{ asset($question->option_images[$key]) }}"
                                    class="object-cover rounded-lg shadow" style="max-width: 100%; max-height: 150px;">
                        
                            @endif  --}}
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-3 ml-6">
                    <a href="{{ route('admin.questions.edit',  [$quiz->id, $question->id]) }}"
                        class="bg-yellow-50 text-yellow-500 hover:bg-yellow-100 hover:text-yellow-700 transition px-3 py-1.5 rounded-lg">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.questions.destroy', [$quiz->id, $question->id]) }}" method="POST"
                        onsubmit="return confirm('ยืนยันการลบคำถามข้อนี้?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition px-3 py-1.5 rounded-lg">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
            <p class="text-gray-400 font-medium text-lg">ยังไม่มีคำถามในชุดข้อสอบนี้</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
