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
                        <i class="fas fa-file-{{ Str::endsWith($existingSubmission->file_name, '.pdf') ? 'pdf text-red-500' : 'word text-blue-500' }} text-xl"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $existingSubmission->file_name }}</p>
                        <p class="text-sm text-gray-500">ส่งเมื่อ {{ $existingSubmission->created_at->format('d/m/Y H:i') }}</p>
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
            
            <form action="{{ route('typing.student.upload.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
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
                    
                    <input type="file" name="file" id="file-input" class="hidden" accept=".docx,.pdf" onchange="updateFileName()">
                    
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
                
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
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
