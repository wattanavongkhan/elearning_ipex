@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">เพิ่มปฏิทินใหม่</h3>
                <p class="text-sm text-gray-500">กิจกรรมหรือตารางเวลาใหม่</p>
            </div>
        </div>
        <div class="space-y-3 mt-3">
            <form action="{{ route('schedule.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-y-6">
                    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-7">
                        <div class="md:col-span-2">
                            <label for="purpose" class="block text-sm font-semibold text-gray-700 mb-2">Purpose <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="purpose" id="purpose" value="{{ old('purpose') }}" required
                                class="w-full border rounded-lg p-2">
                        </div>
                        <div class="md:col-span-1">
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Start Date
                                <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="start_date" id="start_date"
                                value="{{ old('start_date') }}" required class="w-full border rounded-lg p-2">
                        </div>
                        <div class="md:col-span-1">
                            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date <span
                                    class="text-red-500">*</span></label>
                            <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                required class="w-full border rounded-lg p-2">
                        </div>
                    </div>
                </div>
                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-7">
                    <div>
                        <label class="block font-bold mb-2">Room</label>
                        <select name="room_id" class="w-full border rounded-lg p-2">
                            @foreach(\App\Models\Room::all() as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-2">Category Color</label>
                        <select class="w-full border rounded-lg p-2" name="category_color" id="category_color">
                            <option value="#3b82f6">Blue </option>
                            <option value="#10b981">Green</option>
                            <option value="#f59e0b">Yellow</option>
                            <option value="#ef4444">Red</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-2">Members</label>
                    <textarea name="members" class="w-full border rounded-lg p-2">{{ old('members') }}</textarea>
                </div>
        </div>
        <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
            <a href="{{ route('schedule.index') }}"
                class="bg-red-600 hover:bg-red-700 text-white font-bold px-5 py-3 rounded-lg shadow-lg transition-all active:scale-95">
                <i class="fa fa-repeat" aria-hidden="true"></i> Cancel
            </a>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-lg shadow-lg transition-all active:scale-95">
                <i class="fas fa-save mr-2"></i> Save
            </button>

        </div>
        </form>
    </div>
</div>
</div>
@endsection
@section('scripts')
@endsection
