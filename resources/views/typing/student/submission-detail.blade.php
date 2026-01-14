<x-typing-app :role="'student'" :title="'รายละเอียดงานที่ส่ง - ' . $submission->assignment->title">
    
    <!-- Back Button & Header -->
    <div class="mb-8">
        <a href="{{ route('typing.student.submissions') }}" class="inline-flex items-center text-gray-500 hover:text-primary-600 transition-colors mb-4">
            <i class="fas fa-arrow-left mr-2"></i>
            กลับไปหน้ารายการส่งงาน
        </a>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <span class="text-primary-600">รายละเอียดการส่งงาน</span>
                </h1>
                <p class="text-lg text-gray-600 mt-1">{{ $submission->assignment->title }}</p>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-lg bg-gray-100 text-gray-600 font-medium">
                    <i class="far fa-clock mr-2"></i>
                    ส่งเมื่อ: {{ $submission->created_at->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
    </div>
    
    <!-- Score Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Score -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">คะแนนที่ได้</p>
            <div class="flex items-end gap-2">
                <h3 class="text-4xl font-bold text-primary-600">{{ $submission->score ?? '-' }}</h3>
                <span class="text-gray-400 mb-1">/ {{ $submission->assignment->max_score }}</span>
            </div>
            @if($submission->score !== null)
            <div class="mt-2 text-xs text-green-600 font-medium bg-green-50 inline-block px-2 py-1 rounded">
                <i class="fas fa-check-circle mr-1"></i> ตรวจแล้ว
            </div>
            @else
            <div class="mt-2 text-xs text-amber-600 font-medium bg-amber-50 inline-block px-2 py-1 rounded">
                <i class="fas fa-clock mr-1"></i> รอตรวจ
            </div>
            @endif
        </div>
        
        <!-- WPM -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">ความเร็ว (คำ/นาที)</p>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">{{ $submission->wpm }}</h3>
            </div>
        </div>
        
        <!-- Accuracy -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">ความแม่นยำ</p>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-xl">
                    <i class="fas fa-crosshairs"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">{{ $submission->accuracy }}%</h3>
            </div>
        </div>
        
        <!-- Time Taken -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-gray-500 text-sm mb-1">เวลาที่ใช้</p>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 text-xl">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">{{ gmdate('i:s', $submission->time_taken) }}</h3>
                <span class="text-sm text-gray-500 self-end mb-1">นาที</span>
            </div>
        </div>
    </div>
    
    <!-- Details Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Assignment Info -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-primary-500"></i>
                    ข้อมูลแบบฝึกหัด
                </h3>
                <div class="bg-gray-50 rounded-xl p-6">
                    <h4 class="font-bold text-gray-800 mb-2">{{ $submission->assignment->title }}</h4>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $submission->assignment->description }}</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                         <div>
                             <span class="text-xs text-gray-500 block">ประเภทการส่ง</span>
                             <span class="font-medium text-gray-800">
                                 @if($submission->assignment->submission_type === 'typing')
                                    <i class="fas fa-keyboard mr-1 text-gray-400"></i> พิมพ์ในระบบ
                                 @else
                                    <i class="fas fa-file-upload mr-1 text-gray-400"></i> อัปโหลดไฟล์
                                 @endif
                             </span>
                         </div>
                         <div>
                             <span class="text-xs text-gray-500 block">กำหนดส่ง</span>
                             <span class="font-medium {{ $submission->created_at->gt($submission->assignment->due_date) ? 'text-red-600' : 'text-green-600' }}">
                                 {{ $submission->assignment->due_date ? $submission->assignment->due_date->format('d/m/Y H:i') : 'ไม่มีกำหนด' }}
                             </span>
                         </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">ต้องการฝึกซ้ำหรือไม่?</h3>
                    <p class="text-sm text-gray-500">คุณสามารถกลับไปฝึกพิมพ์บทเรียนนี้ใหม่อีกครั้งได้</p>
                </div>
                <a href="{{ route('typing.student.practice', $submission->assignment_id) }}" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-primary-500/30 flex items-center gap-2">
                    <i class="fas fa-redo"></i>
                    ฝึกอีกครั้ง
                </a>
            </div>
        </div>
        
        <!-- Sidebar Info (File / Stats) -->
        <div class="lg:col-span-1">
             @if($submission->file_path)
             <!-- File Download Card -->
             <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                 <h3 class="text-lg font-bold text-gray-800 mb-4">ไฟล์งานที่ส่ง</h3>
                 <div class="p-4 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 text-center hover:bg-gray-100 transition-colors">
                     <i class="fas fa-file-pdf text-4xl text-red-500 mb-3 block"></i>
                     <p class="text-gray-800 font-medium truncate px-4 mb-1">{{ $submission->file_name }}</p>
                     <p class="text-xs text-gray-500 mb-4">{{ number_format($submission->file_metadata['size'] / 1024, 2) }} KB</p>
                     <a href="{{ asset($submission->file_path) }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                         ดาวน์โหลดไฟล์
                     </a>
                 </div>
             </div>
             @endif
             
             <!-- Tips Card -->
             <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
                 <h3 class="font-bold text-lg mb-2"><i class="fas fa-lightbulb text-yellow-300 mr-2"></i>รู้หรือไม่?</h3>
                 <p class="text-blue-100 text-sm leading-relaxed">
                     การฝึกพิมพ์วันละ 15 นาทีอย่างสม่ำเสมอ จะช่วยพัฒนาความเร็วได้ดีกว่าการฝึกหนักๆ เพียงวันเดียว
                 </p>
             </div>
        </div>
    </div>

</x-typing-app>
