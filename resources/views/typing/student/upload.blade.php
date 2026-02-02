<x-typing-app :role="'student'" :title="'อัปโหลดไฟล์ - ' . $assignment->title">

    <div class="max-w-2xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('typing.student.assignments') }}" class="btn-ghost">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-file-upload text-primary-500 mr-2"></i>
                    อัปโหลดไฟล์
                </h1>
                <p class="text-gray-500">{{ $assignment->title }}</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-secondary-50 border border-secondary-200 text-secondary-700">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Assignment Info -->
        <div class="card mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-primary-500 mr-2"></i>
                ข้อมูลงาน
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">ประเภท:</span>
                    <span class="font-medium text-gray-800">
                        @if($assignment->type === 'memo') บันทึกข้อความ
                        @elseif($assignment->type === 'external') หนังสือภายนอก
                        @elseif($assignment->type === 'command') คำสั่ง
                        @else {{ $assignment->type }}
                        @endif
                    </span>
                </div>
                <div>
                    <span class="text-gray-500">คะแนนเต็ม:</span>
                    <span class="font-medium text-gray-800">{{ $assignment->max_score }}</span>
                </div>
                @if($assignment->due_date)
                    <div>
                        <span class="text-gray-500">กำหนดส่ง:</span>
                        <span class="font-medium text-gray-800">{{ $assignment->due_date->format('d/m/Y') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Master File Preview (Instruction) -->
        @if($assignment->master_file_path)
            <div class="card mb-6 overflow-hidden">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center justify-between">
                    <span>
                        <i class="fas fa-file-word text-blue-600 mr-2"></i>
                        โจทย์: {{ $assignment->master_file_name }}
                    </span>
                    <a href="{{ asset($assignment->master_file_path) }}" download class="btn-outline text-xs px-3 py-1">
                        <i class="fas fa-download mr-1"></i> ดาวน์โหลด
                    </a>
                </h3>

                <!-- DOCX Viewer Container -->
                <div id="docx-container"
                    class="bg-gray-100 p-4 rounded-xl border border-gray-200 min-h-[500px] overflow-auto relative">
                    <div id="loading-preview"
                        class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-80 z-10">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin text-3xl text-primary-500 mb-2"></i>
                            <p class="text-gray-500">กำลังโหลดตัวอย่างไฟล์...</p>
                        </div>
                    </div>
                </div>

                <!-- Load Preview Libraries -->
                <script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
                <script src="https://unpkg.com/docx-preview/dist/docx-preview.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const docData = "{{ asset($assignment->master_file_path) }}";
                        const container = document.getElementById('docx-container');
                        const loading = document.getElementById('loading-preview');

                        fetch(docData)
                            .then(response => response.blob())
                            .then(blob => {
                                loading.style.display = 'none';
                                docx.renderAsync(blob, container, container, {
                                    className: "docx", // class name/prefix for default styles
                                    inWrapper: true, // enable around wrapper
                                    ignoreWidth: false, // disable rendering of document width
                                    ignoreHeight: false, // disable rendering of document height
                                    ignoreFonts: false, // disable fonts rendering
                                    breakPages: true, // enable page breaking on page breaks
                                    ignoreLastRenderedPageBreak: true, // disable page breaking on lastRenderedPageBreak elements
                                    experimental: false, // enable experimental features (tab stops calculation)
                                    trimXmlDeclaration: true, // if true, xml declaration will be removed from xml documents before parsing
                                    debug: false // enables additional logging
                                })
                                    .then(x => console.log("docx: finished"))
                                    .catch(e => {
                                        console.error(e);
                                        container.innerHTML = '<div class="text-center py-10 text-red-500"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p>ไม่สามารถแสดงตัวอย่างไฟล์ได้ กรุณาดาวน์โหลดเพื่อดู</p></div>';
                                    });
                            })
                            .catch(err => {
                                console.error(err);
                                loading.style.display = 'none';
                                container.innerHTML = '<div class="text-center py-10 text-red-500"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p>โหลดไฟล์ไม่สำเร็จ</p></div>';
                            });
                    });
                </script>
            </div>
        @else
            <div class="card mb-6">
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-exclamation-circle text-2xl mb-2 text-gray-400"></i>
                    <p>อาจารย์ยังไม่ได้อัปโหลดไฟล์โจทย์ต้นฉบับ</p>
                </div>
            </div>
        @endif

        <!-- Existing Submission -->
        @if($existingSubmission)
            <div class="card mb-6 bg-amber-50 border-amber-200">
                <h3 class="font-semibold text-amber-800 mb-4">
                    <i class="fas fa-file-alt text-amber-500 mr-2"></i>
                    ไฟล์ที่ส่งไปแล้ว
                </h3>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                            <i
                                class="fas fa-file-{{ Str::endsWith($existingSubmission->file_name, '.pdf') ? 'pdf text-red-500' : 'word text-blue-500' }} text-xl"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $existingSubmission->file_name }}</p>
                            <p class="text-sm text-gray-500">ส่งเมื่อ
                                {{ $existingSubmission->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    @if($existingSubmission->score !== null)
                        <span class="badge-secondary">
                            <i class="fas fa-check mr-1"></i>
                            คะแนน: {{ $existingSubmission->score }}/{{ $assignment->max_score }}
                        </span>
                    @else
                        <span class="badge-warning">
                            <i class="fas fa-clock mr-1"></i>
                            รอตรวจ
                        </span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Upload Form -->
        <div class="card">
            <h3 class="font-semibold text-gray-800 mb-6">
                <i class="fas fa-cloud-upload-alt text-primary-500 mr-2"></i>
                {{ $existingSubmission ? 'ส่งไฟล์ใหม่ (แทนที่ไฟล์เดิม)' : 'อัปโหลดไฟล์' }}
            </h3>

            <form action="{{ route('typing.student.upload.submit', $assignment->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-400 transition-colors"
                    id="drop-zone"
                    ondragover="event.preventDefault(); this.classList.add('border-primary-500', 'bg-primary-50');"
                    ondragleave="this.classList.remove('border-primary-500', 'bg-primary-50');"
                    ondrop="event.preventDefault(); this.classList.remove('border-primary-500', 'bg-primary-50'); document.getElementById('file-input').files = event.dataTransfer.files; updateFileName();">

                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cloud-upload-alt text-primary-500 text-2xl"></i>
                    </div>

                    <p class="text-gray-600 mb-2">ลากไฟล์มาวางที่นี่ หรือ</p>

                    <label for="file-input" class="btn-primary cursor-pointer inline-block">
                        <i class="fas fa-folder-open mr-2"></i>
                        เลือกไฟล์
                    </label>

                    <input type="file" name="file" id="file-input" class="hidden" accept=".docx,.pdf"
                        onchange="updateFileName()">

                    <p class="text-sm text-gray-400 mt-4">รองรับ .docx และ .pdf (สูงสุด 10MB)</p>

                    <div id="file-preview" class="hidden mt-4 p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center justify-center gap-3">
                            <i class="fas fa-file text-primary-500"></i>
                            <span id="file-name" class="font-medium text-gray-800"></span>
                            <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-100">
                    <div class="text-left">
                        <a href="{{ route('typing.student.editor', $assignment->id) }}"
                            class="inline-flex items-center text-primary-600 hover:text-primary-800 font-medium">
                            <span class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center mr-2">
                                <i class="fas fa-edit"></i>
                            </span>
                            สลับไปใช้โหมดพิมพ์เอกสารออนไลน์ (Online Editor)
                        </a>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('typing.student.assignments') }}" class="btn-ghost">ยกเลิก</a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>
                        ส่งงาน
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateFileName() {
            const input = document.getElementById('file-input');
            const preview = document.getElementById('file-preview');
            const nameSpan = document.getElementById('file-name');

            if (input.files.length > 0) {
                nameSpan.textContent = input.files[0].name;
                preview.classList.remove('hidden');
            }
        }

        function clearFile() {
            const input = document.getElementById('file-input');
            const preview = document.getElementById('file-preview');

            input.value = '';
            preview.classList.add('hidden');
        }
    </script>

</x-typing-app>