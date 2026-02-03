<x-typing-app :role="'admin'" :title="'แก้ไขเอกสารตัวอย่าง - ระบบวิชาพิมพ์หนังสือราชการ 1'">

    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('typing.admin.templates.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-violet-600 transition-colors mb-4">
            <i class="fas fa-arrow-left"></i>
            <span>กลับไปคลังเอกสาร</span>
        </a>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">แก้ไขเอกสารตัวอย่าง</h1>
        <p class="text-gray-500">แก้ไขข้อมูลเอกสาร: {{ $template->title }}</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('typing.admin.templates.update', $template) }}" method="POST"
            enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                            ชื่อเอกสาร <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $template->title) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                            หมวดหมู่ <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all">
                            @foreach($categories as $key => $value)
                                <option value="{{ $key }}" {{ old('category', $template->category) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            คำอธิบาย
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all resize-none">{{ old('description', $template->description) }}</textarea>
                    </div>

                    <!-- Toggles -->
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $template->is_featured) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-gray-300 text-violet-600 focus:ring-violet-500 transition-colors">
                            <div>
                                <span class="font-semibold text-gray-700">แนะนำเอกสารนี้</span>
                                <p class="text-sm text-gray-500">เอกสารจะแสดงในส่วนแนะนำสำหรับนักเรียน</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $template->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 transition-colors">
                            <div>
                                <span class="font-semibold text-gray-700">เปิดใช้งาน</span>
                                <p class="text-sm text-gray-500">นักเรียนจะสามารถดูเอกสารนี้ได้</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Current File Info -->
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <p class="text-sm font-semibold text-gray-700 mb-2">ไฟล์ปัจจุบัน</p>
                        <div class="flex items-center gap-3">
                            @php
                                $iconClass = match (strtolower($template->file_type)) {
                                    'pdf' => 'fa-file-pdf text-red-500',
                                    'doc', 'docx' => 'fa-file-word text-blue-500',
                                    default => 'fa-file text-gray-400'
                                };
                            @endphp
                            <i class="fas {{ $iconClass }} text-2xl"></i>
                            <div>
                                <p class="font-medium text-gray-800">{{ $template->file_name }}</p>
                                <p class="text-xs text-gray-500">{{ $template->formatted_file_size }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload (Replace) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            เปลี่ยนไฟล์เอกสาร
                        </label>
                        <div class="relative">
                            <input type="file" id="file" name="file" accept=".doc,.docx,.pdf,.odt" class="hidden"
                                onchange="updateFileName(this, 'file-name')">
                            <label for="file"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-violet-400 transition-all">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <i class="fas fa-sync-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">คลิกเพื่อเปลี่ยนไฟล์</p>
                                    <p id="file-name" class="mt-1 text-sm font-medium text-violet-600 hidden"></p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Current Thumbnail -->
                    @if($template->thumbnail)
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <p class="text-sm font-semibold text-gray-700 mb-2">รูปปกปัจจุบัน</p>
                            <img src="{{ asset('storage/' . $template->thumbnail) }}" alt=""
                                class="w-full h-32 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Thumbnail Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ $template->thumbnail ? 'เปลี่ยนรูปปก' : 'รูปปก (ตัวอย่าง)' }}
                        </label>
                        <div class="relative">
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="hidden"
                                onchange="previewThumbnail(this)">
                            <label for="thumbnail"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-violet-400 transition-all overflow-hidden">
                                <div id="thumbnail-preview-container" class="hidden w-full h-full">
                                    <img id="thumbnail-preview" src="" alt="" class="w-full h-full object-cover">
                                </div>
                                <div id="thumbnail-placeholder"
                                    class="flex flex-col items-center justify-center text-center">
                                    <i class="fas fa-image text-2xl text-gray-300 mb-2"></i>
                                    <p class="text-sm text-gray-600">คลิกเพื่อเลือกรูปใหม่</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="p-4 bg-violet-50 rounded-xl border border-violet-100">
                        <p class="text-sm font-semibold text-violet-700 mb-3">สถิติ</p>
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <p class="text-2xl font-bold text-violet-600">
                                    {{ number_format($template->download_count) }}
                                </p>
                                <p class="text-xs text-violet-500">ดาวน์โหลด</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-violet-600">{{ number_format($template->view_count) }}
                                </p>
                                <p class="text-xs text-violet-500">เข้าชม</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
                <a href="{{ route('typing.admin.templates.index') }}"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                    ยกเลิก
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>บันทึกการเปลี่ยนแปลง</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateFileName(input, targetId) {
            const target = document.getElementById(targetId);
            if (input.files && input.files[0]) {
                target.textContent = input.files[0].name;
                target.classList.remove('hidden');
            }
        }

        function previewThumbnail(input) {
            const preview = document.getElementById('thumbnail-preview');
            const previewContainer = document.getElementById('thumbnail-preview-container');
            const placeholder = document.getElementById('thumbnail-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</x-typing-app>