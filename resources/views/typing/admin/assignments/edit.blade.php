<x-typing-app :role="'admin'" :title="'แก้ไขบทเรียน'">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">แก้ไขบทเรียน</h1>
            <a href="{{ route('typing.admin.assignments.index') }}" class="btn-ghost">
                <i class="fas fa-arrow-left mr-2"></i>ย้อนกลับ
            </a>
        </div>

        <!-- Form -->
        <div class="card p-6">
            <form action="{{ route('typing.admin.assignments.update', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Title -->
                    <div class="col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">หัวข้อบทเรียน</label>
                        <input type="text" name="title" id="title" class="form-input w-full" value="{{ old('title', $assignment->title) }}" required>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">ประเภทเอกสาร</label>
                        <select name="type" id="type" class="form-select w-full">
                            <option value="internal" {{ $assignment->type == 'internal' ? 'selected' : '' }}>หนังสือภายใน (Internal)</option>
                            <option value="external" {{ $assignment->type == 'external' ? 'selected' : '' }}>หนังสือภายนอก (External)</option>
                            <option value="command" {{ $assignment->type == 'command' ? 'selected' : '' }}>คำสั่ง (Command)</option>
                            <option value="memo" {{ $assignment->type == 'memo' ? 'selected' : '' }}>บันทึกข้อความ (Memo)</option>
                        </select>
                    </div>

                    <!-- Difficulty -->
                    <div>
                        <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-1">ระดับความยาก (1-5)</label>
                        <input type="number" name="difficulty_level" id="difficulty_level" min="1" max="5" value="{{ old('difficulty_level', $assignment->difficulty_level) }}" class="form-input w-full">
                    </div>

                    <!-- Max Score -->
                    <div>
                        <label for="max_score" class="block text-sm font-medium text-gray-700 mb-1">คะแนนเต็ม</label>
                        <input type="number" name="max_score" id="max_score" value="{{ old('max_score', $assignment->max_score) }}" class="form-input w-full">
                    </div>

                    <!-- Time Limit (for typing mode only) -->
                    @if($assignment->submission_type === 'typing')
                    <div>
                        <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-clock text-primary-500 mr-1"></i>
                            เวลาในการพิมพ์ (นาที)
                        </label>
                        <input type="number" name="time_limit" id="time_limit" min="1" max="60" value="{{ old('time_limit', $assignment->time_limit ?? 5) }}" class="form-input w-full">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            กำหนดเวลาในการพิมพ์ เช่น 1, 2, 5 นาที
                        </p>
                    </div>
                    @endif

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">กำหนดส่ง (Optional)</label>
                        <input type="datetime-local" name="due_date" id="due_date" value="{{ $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '' }}" class="form-input w-full">
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $assignment->is_active ? 'checked' : '' }} class="form-checkbox text-primary-600 rounded">
                            <span class="text-sm text-gray-700">เปิดใช้งาน</span>
                        </label>
                    </div>
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">เนื้อหาที่ต้องพิมพ์ (สำหรับโหมดพิมพ์ในระบบ)</label>
                    <textarea name="content" id="content" rows="8" class="form-textarea w-full font-mono text-base">{{ old('content', $assignment->content) }}</textarea>
                </div>

                <!-- Master File for Auto-Grading -->
                <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-word text-primary-500 mr-1"></i>
                        ไฟล์ต้นฉบับ (สำหรับตรวจอัตโนมัติ)
                    </label>
                    
                    @if($assignment->master_file_path)
                    <div class="mb-3 p-3 bg-white rounded-lg border border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-word text-blue-500 text-xl"></i>
                            <div>
                                <p class="font-medium text-gray-800">{{ $assignment->master_file_name ?? 'ไฟล์ต้นฉบับ' }}</p>
                                <p class="text-xs text-gray-500">อัปโหลดไฟล์ใหม่เพื่อเปลี่ยน</p>
                            </div>
                        </div>
                        <a href="{{ asset($assignment->master_file_path) }}" target="_blank" class="btn-ghost text-sm">
                            <i class="fas fa-download mr-1"></i>ดาวน์โหลด
                        </a>
                    </div>
                    @endif
                    
                    <input type="file" name="master_file" accept=".docx" class="form-input w-full">
                    <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i>รองรับไฟล์ .docx เท่านั้น (ขนาดไม่เกิน 10MB)</p>
                    
                    <!-- Formatting Check Toggle -->
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="check_formatting" value="1" {{ old('check_formatting', $assignment->check_formatting) ? 'checked' : '' }} class="form-checkbox text-primary-600 rounded w-5 h-5">
                            <div>
                                <span class="font-medium text-gray-800">ตรวจรูปแบบเอกสาร</span>
                                <p class="text-xs text-gray-500">ตรวจฟอนต์ (TH SarabunPSK 16pt), ระยะขอบ, ขนาดกระดาษ, หัวเรื่อง</p>
                            </div>
                        </label>
                    </div>
                    
                    <p class="text-xs text-blue-600 mt-3"><i class="fas fa-calculator mr-1"></i>คะแนน = ตัวอักษร 70% + รูปแบบ 30%</p>
                </div>

                <!-- Hidden submission_type to preserve value -->
                <input type="hidden" name="submission_type" value="{{ $assignment->submission_type ?? 'file' }}">

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('typing.admin.assignments.index') }}" class="btn-ghost">ยกเลิก</a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-typing-app>
