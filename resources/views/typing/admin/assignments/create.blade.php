<x-typing-app :role="'admin'" :title="'สร้างบทเรียนใหม่'">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">สร้างบทเรียนใหม่</h1>
            <a href="{{ route('typing.admin.assignments.index') }}" class="btn-ghost">
                <i class="fas fa-arrow-left mr-2"></i>ย้อนกลับ
            </a>
        </div>

        <!-- Form -->
        <div class="card p-6">
            <form action="{{ route('typing.admin.assignments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Chapter -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="chapter" class="block text-sm font-medium text-gray-700 mb-1">บทที่
                            (Chapter)</label>
                        <input type="text" name="chapter" id="chapter" class="form-input w-full"
                            placeholder="Ex. บทที่ 1">
                    </div>

                    <!-- Title -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">หัวข้อบทเรียน</label>
                        <input type="text" name="title" id="title" class="form-input w-full"
                            placeholder="Ex. การพิมพ์สัมผัสเบื้องต้น" required>
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">ประเภทเอกสาร</label>
                        <select name="type" id="type" class="form-select w-full">
                            <option value="memo">บันทึกข้อความ</option>
                            <option value="external">หนังสือภายนอก</option>
                            <option value="command">คำสั่ง</option>
                        </select>
                    </div>

                    <!-- Submission Type -->
                    <div x-data="{ submissionType: 'file' }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">รูปแบบการส่งงาน</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer p-3 rounded-lg border-2 transition-all"
                                :class="submissionType === 'file' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'">
                                <input type="radio" name="submission_type" value="file" x-model="submissionType"
                                    class="form-radio text-primary-600">
                                <div>
                                    <i class="fas fa-file-upload text-primary-500 mr-1"></i>
                                    <span class="font-medium">แนบไฟล์</span>
                                    <p class="text-xs text-gray-500">นักเรียนอัปโหลด .docx/.pdf</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer p-3 rounded-lg border-2 transition-all"
                                :class="submissionType === 'typing' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'">
                                <input type="radio" name="submission_type" value="typing" x-model="submissionType"
                                    class="form-radio text-primary-600">
                                <div>
                                    <i class="fas fa-keyboard text-primary-500 mr-1"></i>
                                    <span class="font-medium">พิมพ์ในระบบ</span>
                                    <p class="text-xs text-gray-500">นักเรียนพิมพ์ตามต้นฉบับ</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Difficulty -->
                    <div>
                        <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-1">ระดับความยาก
                            (1-5)</label>
                        <input type="number" name="difficulty_level" id="difficulty_level" min="1" max="5" value="1"
                            class="form-input w-full">
                    </div>

                    <!-- Max Score -->
                    <div>
                        <label for="max_score" class="block text-sm font-medium text-gray-700 mb-1">คะแนนเต็ม</label>
                        <input type="number" name="max_score" id="max_score" value="100" class="form-input w-full">
                    </div>

                    <!-- Time Limit (for typing mode only) -->
                    <div x-data="{ show: false }" x-init="
                        show = document.querySelector('input[name=submission_type]:checked')?.value === 'typing';
                        document.querySelectorAll('input[name=submission_type]').forEach(el => {
                            el.addEventListener('change', () => show = el.value === 'typing' && el.checked);
                        });
                    " x-show="show" x-cloak>
                        <label for="time_limit" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-clock text-primary-500 mr-1"></i>
                            เวลาในการพิมพ์ (นาที)
                        </label>
                        <input type="number" name="time_limit" id="time_limit" min="1" max="60" value="5"
                            class="form-input w-full" placeholder="เช่น 5">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            กำหนดเวลาในการพิมพ์ เช่น 1, 2, 5 นาที (ไม่ใช่กำหนดส่ง)
                        </p>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">กำหนดส่ง
                            (Optional)</label>
                        <input type="datetime-local" name="due_date" id="due_date" class="form-input w-full">
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked
                                class="form-checkbox text-primary-600 rounded">
                            <span class="text-sm text-gray-700">เปิดใช้งานทันที</span>
                        </label>
                    </div>
                </div>

                <!-- Content (for typing mode) -->
                <div class="mb-6" x-data="{ show: false }" x-init="
                    show = document.querySelector('input[name=submission_type]:checked')?.value === 'typing';
                    document.querySelectorAll('input[name=submission_type]').forEach(el => {
                        el.addEventListener('change', () => show = el.value === 'typing' && el.checked);
                    });
                " x-show="show" x-cloak>
                    <label for="content"
                        class="block text-sm font-medium text-gray-700 mb-1">เนื้อหาที่ต้องพิมพ์</label>
                    <textarea name="content" id="content" rows="10" class="form-textarea w-full font-mono text-base"
                        placeholder="พิมพ์เนื้อหาที่ต้องการให้นักเรียนพิมพ์ที่นี่..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">แนะนำให้ใช้ภาษาที่ถูกต้องตามหลักราชการ</p>
                </div>

                <!-- File upload info -->
                <div class="mb-6" x-data="{ show: true }" x-init="
                    show = document.querySelector('input[name=submission_type]:checked')?.value === 'file';
                    document.querySelectorAll('input[name=submission_type]').forEach(el => {
                        el.addEventListener('change', () => show = el.value === 'file' && el.checked);
                    });
                " x-show="show" x-cloak>
                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex items-start gap-3 mb-4">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <div>
                                <p class="font-medium text-blue-800">โหมดแนบไฟล์</p>
                                <p class="text-sm text-blue-600">นักเรียนจะอัปโหลดไฟล์ .docx
                                    ระบบจะเปรียบเทียบกับไฟล์ต้นฉบับโดยอัตโนมัติ</p>
                            </div>
                        </div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-word text-primary-500 mr-1"></i>
                            ไฟล์ต้นฉบับ (สำหรับตรวจอัตโนมัติ)
                        </label>
                        <input type="file" name="master_file" accept=".docx" class="form-input w-full">
                        <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i>รองรับไฟล์ .docx
                            เท่านั้น (ขนาดไม่เกิน 10MB)</p>

                        <!-- Formatting Check Toggle -->
                        <div class="mt-4 pt-4 border-t border-blue-200">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="check_formatting" value="1"
                                    class="form-checkbox text-primary-600 rounded w-5 h-5">
                                <div>
                                    <span class="font-medium text-gray-800">ตรวจรูปแบบเอกสาร</span>
                                    <p class="text-xs text-gray-500">ตรวจฟอนต์ (TH SarabunPSK 16pt), ระยะขอบ,
                                        ขนาดกระดาษ, หัวเรื่อง</p>
                                </div>
                            </label>
                        </div>

                        <p class="text-xs text-blue-600 mt-3"><i class="fas fa-calculator mr-1"></i>คะแนน = ตัวอักษร 70%
                            + รูปแบบ 30%</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="reset" class="btn-ghost">รีเซ็ต</button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>บันทึกบทเรียน
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-typing-app>