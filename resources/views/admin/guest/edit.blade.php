@extends('layouts.layout_admin')

@section('content')
<div class="max-w-12xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
        <div class="flex justify-between items-center mb-5">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">แก้ไขปฏิทิน</h3>
                <p class="text-sm text-gray-500">กิจกรรมหรือตารางเวลา</p>
            </div>
        </div>
        <div class="space-y-3 mt-3">
            <form action="{{ route('guest.store', $calendarEvent->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 gap-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-7">
                        <div class="md:col-span-2">
                            <label for="members" class="block text-sm font-semibold text-gray-700 mb-2">Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="members" id="members"
                                value="{{ old('members', $calendarEvent->members) }}" required
                                class="w-full border rounded-lg p-2">
                        </div>
                        <div class="md:col-span-1">
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Form Date
                                <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="start_date" id="start_date"
                                value="{{ old('start_date', $calendarEvent->start_date) }}" required
                                class="w-full border rounded-lg p-2">
                        </div>
                        <div class="md:col-span-1">
                            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">To Date<span
                                    class="text-red-500">*</span></label>
                            <input type="datetime-local" name="end_date" id="end_date"
                                value="{{ old('end_date', $calendarEvent->end_date) }}" required
                                class="w-full border rounded-lg p-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap5">
                        <div class="md:col-span-2">
                            <label class="block font-bold  mb-2">Hotel <span class="text-red-500">*</span></label>
                            <input type="text" name="hotel_name" id="hotel_name"
                                value="{{ old('hotel_name', $calendarEvent->hotel_name) }}" required
                                class="w-full border rounded-lg p-2">

                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
                        <div class="md:col-span-2">
                            <label class="block font-bold  mb-2">Request booking from <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="request_booking" id="request_booking"
                                value="{{ old('request_booking', $calendarEvent->request_booking) }}" required
                                class="w-full border rounded-lg p-2">
                        </div>

                        <div>
                            <label class="block font-bold  mb-2">Category Color</label>
                            <select class="w-full border rounded-lg p-2" name="category_color" id="category_color">
                                <option value="#3b82f6"
                                    {{ old('category_color', $calendarEvent->category_color) == '#3b82f6' ? 'selected' : '' }}>
                                    Blue </option>
                                <option value="#10b981"
                                    {{ old('category_color', $calendarEvent->category_color) == '#10b981' ? 'selected' : '' }}>
                                    Green</option>
                                <option value="#f59e0b"
                                    {{ old('category_color', $calendarEvent->category_color) == '#f59e0b' ? 'selected' : '' }}>
                                    Yellow</option>
                                <option value="#ef4444"
                                    {{ old('category_color', $calendarEvent->category_color) == '#ef4444' ? 'selected' : '' }}>
                                    Red</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 p-3 bg-gray-50 rounded-xl">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" id="status" value="0" class="sr-only peer"
                                {{ old('status', $calendarEvent->status) == 0 ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                            </div>
                            <span class="ml-3 text-sm font-semibold text-gray-700">เปิดใช้งาน</span>
                        </label>
                    </div>

                    <div>
                        <input type="hidden" name="id" value="{{ $calendarEvent->id }}">
                    </div>
                </div>
                <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                    <a href="{{ route('guest.index') }}"
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
