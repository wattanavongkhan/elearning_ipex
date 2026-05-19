<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="min-h-screen" style="margin-top: 50px;">
    <div class="max-w-8xl mx-auto mt-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
            <div class="flex items-center gap-4">
                <span class="w-12 h-1.5 bg-blue-600 rounded-full"></span>
                <h2 class="text-3xl md:text-4xl font-black text-slate-950 tracking-tight">
                    ปฏิทินกิจกรรมและการจองห้องประชุม
                </h2>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3 bg-red p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div id="calendar" class="min-h-[400px]"></div>
            </div>

            <div class="space-y-7">
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm sticky top-6">
                    <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4">
                        <i class="fas fa-info-circle text-slate-400 mr-2"></i>รายละเอียดกิจกรรม
                    </h3>
                    <div id="event-detail-placeholder" class="text-center py-12 text-slate-400">
                        <i class="far fa-calendar-alt text-4xl mb-3 block"></i>
                        <p class="text-sm">คลิกเลือกกิจกรรมบนปฏิทิน<br>เพื่อดูรายละเอียด</p>
                    </div>
                    <div id="event-detail-content" class="hidden space-y-3">
                        <div class="mb-5">
                            <span id="detail-color-tag" 
                                class="inline-block text-xs font-bold px-2.5 py-1 rounded-md text-white mb-2">Meeting</span>
                            <h6 id="detail-title" class="font-black text-slate-800 leading-tight" style="font-size: 1.10rem;"></h6>
                        </div>
                        <div class="space-y-3 text-sm text-slate-600">
                            <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div
                                    class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center text-md shadow-sm">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 font-semibold">ช่วงเวลาดำเนินการ</p>
                                    <p id="detail-time" class="font-medium text-slate-700 mt-0.2"></p>
                                </div>
                            </div>

                            <div id="dynamic-detail-fields" class="space-y-3"></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        
        const eventData = @json($events);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', // หน้าแรกแสดงแบบรายเดือน
            locale: 'th', // ถ้าต้องการเมนูภาษาไทย (ต้องโหลดไฟล์ locale เพิ่มเติม หรือตั้งค่าตามมาตรฐาน)
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay' // ปุ่มสลับมุมมอง เดือน/สัปดาห์/วัน
            },
            events: eventData,
            editable: false,
            selectable: true,
            eventDisplay: 'block', // แสดงผลกิจกรรมเป็นแถบสีเต็มบล็อกตัวอักษรไม่หลุด
            
        eventClick: function(info) {
        const event = info.event;
        const props = event.extendedProps;

        // 1. เปิดกล่องเนื้อหา และซ่อนหน้าต่างเริ่มต้น
        document.getElementById('event-detail-placeholder').classList.add('hidden');
        document.getElementById('event-detail-content').classList.remove('hidden');
        
        // 2. อัปเดตข้อมูลพื้นฐาน (ชื่อเรื่อง และสีแท็ก)
        document.getElementById('detail-title').innerText = event.title;
        const tag = document.getElementById('detail-color-tag');
        tag.style.backgroundColor = event.backgroundColor;

        // 3. จัดการฟอร์แมตวันเวลาหลัก
        const dateOptions = { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const startStr = event.start.toLocaleString('th-TH', dateOptions);
        const endStr = event.end ? event.end.toLocaleString('th-TH', dateOptions) : 'ไม่มีกำหนดสิ้นสุด';
        if (props.type === 'trip') {
            document.getElementById('detail-time').innerHTML = `From Thailand  : ${startStr}<br> Arrive to Thailand  : ${endStr}`;
        } else {
            document.getElementById('detail-time').innerText = `${startStr} - ${endStr}`;
        }   
        // 4. ตรวจสอบเงื่อนไขประเภท เพื่อพ่น HTML ไอคอนให้ตรงตามตารางข้อมูล
        const dynamicContainer = document.getElementById('dynamic-detail-fields');
        let htmlContent = '';

        if (props.type === 'activity') {
            tag.innerText = 'Purpose';
            htmlContent = `
                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-md"><i class="fas fa-door-open"></i></div>
                    <div><p class="text-xs text-slate-400 font-semibold">ห้องประชุม / สถานที่</p><p class="font-bold text-slate-700 mt-0.5"> ${props.info1 ?? '-'}</p></div>
                </div>
                <div class="flex items-start gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-md mt-0.5"><i class="fas fa-users"></i></div>
                    <div class="flex-1"><p class="text-xs text-slate-400 font-semibold">ผู้เข้าร่วมกิจกรรม</p><p class="font-medium text-slate-700 bg-white p-2 rounded-lg mt-1 text-xs border border-slate-200/60 whitespace-pre-line">${props.info2 ?? '-'}</p></div>
                </div>`;
                
        } else if (props.type === 'guest') {
            tag.innerText = 'ต้อนรับลูกค้า (Guest)';
            htmlContent = `
                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center text-md"><i class="fas fa-hotel"></i></div>
                    <div><p class="text-xs text-slate-400 font-semibold">สถานที่พัก / โรงแรม</p><p class="font-bold text-slate-700 mt-0.5">${props.info1 ?? '-'}</p></div>
                </div>
                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center text-md"><i class="fas fa-user-tag"></i></div>
                    <div><p class="text-xs text-slate-400 font-semibold">Request booking from</p><p class="font-medium text-slate-700 mt-0.5">${props.info2 ?? '-'}</p></div>
                </div>

                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center text-md"><i class="fas fa-file-alt"></i></div>
                    <div><p class="text-xs text-slate-400 font-semibold">Booking Document</p><p class="font-medium text-slate-700 mt-0.5">${props.info3 ?? '-'}</p></div>
                </div>

                `;

        } else if (props.type === 'trip') {
            tag.innerText = 'ปฏิบัติงานนอกสถานที่ (Trip)';
            htmlContent = `
                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-md"><i class="fas fa-plane-departure"></i></div>
                    <div><p class="text-xs text-slate-400 font-semibold">เที่ยวบินขาไป (Departure Flight)</p><p class="font-bold text-slate-700 mt-0.5">${props.info1 ?? '-'}</p></div>
                </div>
                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-md"><i class="fas fa-plane-arrival"></i></div>
                    <div><p class="text-xs text-slate-400 font-semibold">เที่ยวบินขากลับ (Arrive Flight)</p><p class="font-bold text-slate-700 mt-0.5">${props.info2 ?? '-'}</p></div>
                </div>
                <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-md"><i class="fas fa-sticky-note"></i></div>
                    <div><p class="text-xs text-slate-400 font-semibold">Remarks</p><p class="font-bold text-slate-700 mt-0.5">${props.info3 ?? '-'}</p></div>
                </div>
                `;
        }
        dynamicContainer.innerHTML = htmlContent;
    }

    });

    calendar.render();
    });
</script>

<style>
    /* ปรับแต่งสไตล์ปฏิทินให้ดูคลีนสไตล์ Minimal Modern */
    .fc { --fc-border-color: #f1f5f9; --fc-button-bg-color: #ffffff; --fc-button-text-color: #334155; --fc-button-border-color: #e2e8f0; --fc-button-hover-bg-color: #f8fafc; --fc-button-active-bg-color: #cbd5e1; }
    .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 800; color: #1e293b; font-family: 'Kanit', sans-serif; }
    .fc-theme-standard .fc-scrollgrid { border-radius: 1rem; border: 1px solid #f1f5f9; }
    .fc .fc-col-header-cell-cushion { padding: 8px 4px; font-weight: 700; color: #64748b; font-size: 0.85rem; }
    .fc-event { cursor: pointer; padding: 3px 6px; border-radius: 6px !important; font-family: 'Kanit', sans-serif; font-size: 0.8rem; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
</style>
