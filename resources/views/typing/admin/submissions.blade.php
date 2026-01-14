<x-typing-app :role="'admin'" :title="'ตรวจงานที่ส่ง - ระบบวิชาพิมพ์หนังสือราชการ 1'">
<div x-data="gradingApp">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                <i class="fas fa-file-upload text-primary-500 mr-2"></i>
                ตรวจงานที่ส่ง
            </h1>
            <p class="text-gray-500 mt-1">ตรวจสอบและให้คะแนนงานที่นักเรียนส่ง</p>
        </div>
    </div>
    
    <!-- Filters -->
    <form action="{{ route('typing.admin.submissions') }}" method="GET" class="card mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อนักเรียน/รหัสนักเรียน..." class="input pl-10">
                <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <select name="sort" class="input w-full md:w-48" onchange="this.form.submit()">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>เวลาส่ง (ล่าสุด)</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>เวลาส่ง (เก่าสุด)</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>ชื่อ (ก - ฮ)</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>ชื่อ (ฮ - ก)</option>
            </select>
            <select name="assignment_id" class="input w-full md:w-48" onchange="this.form.submit()">
                <option value="">ทุกงาน</option>
                @foreach($allAssignments as $assignment)
                    <option value="{{ $assignment->id }}" {{ request('assignment_id') == $assignment->id ? 'selected' : '' }}>{{ $assignment->title }}</option>
                @endforeach
            </select>
            <select name="status" class="input w-full md:w-48" onchange="this.form.submit()">
                <option value="">ทุกสถานะ</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รอตรวจ</option>
                <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>ให้คะแนนแล้ว</option>
            </select>
        </div>
    </form>
    
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                <i class="fas fa-inbox text-primary-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalSubmissions }}</p>
                <p class="text-xs text-gray-500">งานทั้งหมด</p>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-3 bg-amber-50 border-amber-200">
            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                <i class="fas fa-clock text-amber-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-600">{{ $pendingSubmissions }}</p>
                <p class="text-xs text-gray-500">รอตรวจ</p>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-secondary-100 flex items-center justify-center">
                <i class="fas fa-check-circle text-secondary-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $gradedSubmissions }}</p>
                <p class="text-xs text-gray-500">ให้คะแนนแล้ว</p>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-accent-100 flex items-center justify-center">
                <i class="fas fa-chart-line text-accent-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($averageScore, 1) }}</p>
                <p class="text-xs text-gray-500">คะแนนเฉลี่ย</p>
            </div>
        </div>
    </div>
    
    <!-- Submissions Table -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list text-primary-500 mr-2"></i>
                รายการงานที่ส่ง
            </h2>
            <div class="flex items-center gap-2">
                @if(request('assignment_id'))
                <button 
                    @click="autoGradeAllSubmissions({{ request('assignment_id') }})"
                    class="btn-secondary text-sm"
                    :disabled="isAutoGrading"
                >
                    <i class="fas fa-robot mr-1" :class="{ 'fa-spin': isAutoGrading }"></i>
                    <span x-text="isAutoGrading ? 'กำลังตรวจ...' : 'ตรวจอัตโนมัติทั้งหมด'"></span>
                </button>
                <a href="{{ route('typing.admin.submissions.export.zip', ['assignment_id' => request('assignment_id')]) }}" class="btn-primary text-sm">
                    <i class="fas fa-file-archive mr-1"></i>
                    ดาวน์โหลดไฟล์ทั้งหมด (ZIP)
                </a>
                @endif
                <button class="btn-outline text-sm">
                    <i class="fas fa-download mr-1"></i>
                    ส่งออก CSV
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto w-full">
            <table class="table">
                <thead>
                    <tr>
                        <th>นักเรียน</th>
                        <th>งาน</th>
                        <th>ไฟล์ที่ส่ง</th>
                        <th>เวลาส่ง</th>
                        <th>สถานะ</th>
                        <th>คะแนน</th>
                        <th>การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                    <tr class="{{ $submission->score === null ? 'bg-amber-50/50' : '' }}">
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $submission->user->avatar_url }}" alt="" class="avatar-sm object-cover">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $submission->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ $submission->user->student_id ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $submission->assignment->title ?? '-' }}</td>
                        <td>
                            @if($submission->file_path)
                                {{-- File Upload Submission --}}
                                <a href="{{ asset($submission->file_path) }}" target="_blank" class="flex items-center gap-2 text-primary-600 hover:text-primary-700">
                                    @if(Str::endsWith($submission->file_name, '.pdf'))
                                        <i class="fas fa-file-pdf text-red-500"></i>
                                    @else
                                        <i class="fas fa-file-word text-blue-500"></i>
                                    @endif
                                    <span class="text-sm">{{ Str::limit($submission->file_name, 20) }}</span>
                                    <i class="fas fa-download text-xs"></i>
                                </a>
                                <button 
                                    @click="openIntegrityModal({{ json_encode($submission) }})"
                                    class="text-gray-400 hover:text-blue-500 transition-colors"
                                    title="ดูข้อมูลไฟล์ (Check Integrity)"
                                >
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            @else
                                {{-- Typing Submission --}}
                                <div class="text-sm">
                                    <span class="font-medium text-gray-700">WPM: {{ $submission->wpm }}</span>
                                    <span class="text-gray-400 mx-1">|</span>
                                    <span class="text-gray-500">{{ $submission->accuracy }}%</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <p class="text-gray-800">{{ $submission->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $submission->created_at->format('H:i น.') }}</p>
                        </td>
                        <td>
                            @if($submission->score === null)
                                <span class="badge-warning">รอตรวจ</span>
                            @else
                                <span class="badge-secondary">ให้คะแนนแล้ว</span>
                            @endif
                        </td>
                        <td>
                            @if($submission->score === null)
                                <span class="text-gray-400">-</span>
                            @else
                                <span class="text-lg font-bold text-primary-600">{{ $submission->score }}</span>
                                <span class="text-gray-500">/{{ $submission->assignment->max_score ?? 100 }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center gap-2" x-data="{ loading: false, scored: {{ $submission->score !== null ? 'true' : 'false' }} }">
                                {{-- Quick Score Buttons --}}
                                <div class="flex items-center gap-1">
                                    @foreach([10, 8, 6, 4] as $quickScore)
                                    <button 
                                        @click="quickGrade({{ $submission->id }}, {{ $quickScore }}, $el, {{ $submission->assignment->max_score ?? 100 }})"
                                        class="w-8 h-8 rounded-full text-xs font-bold transition-all duration-200 
                                            {{ $submission->score == $quickScore ? 'bg-primary-600 text-white ring-2 ring-primary-300' : 'bg-gray-100 hover:bg-primary-100 text-gray-700 hover:text-primary-600' }}"
                                        :class="{ 'opacity-50 cursor-not-allowed': loading }"
                                        :disabled="loading"
                                        title="ให้ {{ $quickScore }} คะแนน"
                                    >
                                        {{ $quickScore }}
                                    </button>
                                    @endforeach
                                </div>
                                
                                {{-- Advanced Grading Button --}}
                                <button 
                                    data-feedback="{{ $submission->feedback ?? '' }}"
                                    @click="openGradingModal({{ $submission->id }}, {{ $submission->score ?? 'null' }}, $el.dataset.feedback, {{ $submission->assignment->max_score ?? 100 }})"
                                    class="btn-outline py-1.5 px-2 text-sm"
                                    title="ให้คะแนนแบบละเอียด + ข้อเสนอแนะ"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">ไม่มีข้อมูลการส่งงาน</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-500">แสดง {{ $submissions->firstItem() ?? 0 }}-{{ $submissions->lastItem() ?? 0 }} จาก {{ $submissions->total() }} รายการ</p>
            {{ $submissions->links() }}
        </div>
    </div>
    
    <!-- Grading Modal -->
    <div 
        x-show="isGradingModalOpen" 
        style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto" 
        aria-labelledby="grading-modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div 
                x-show="isGradingModalOpen" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                @click="closeGradingModal"
                aria-hidden="true"
            ></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div 
                x-show="isGradingModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
            >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-edit text-primary-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="grading-modal-title">
                                ให้คะแนนและข้อเสนอแนะ
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="score" class="block text-sm font-medium text-gray-700">คะแนน (เต็ม <span x-text="maxScore"></span>)</label>
                                    <input 
                                        type="number" 
                                        id="score" 
                                        x-model="currentScore" 
                                        class="input mt-1 w-full"
                                        min="0" 
                                        :max="maxScore"
                                    >
                                </div>
                                <div>
                                    <label for="feedback" class="block text-sm font-medium text-gray-700">ข้อเสนอแนะ (Feedback)</label>
                                    <textarea 
                                        id="feedback" 
                                        x-model="currentFeedback" 
                                        rows="4" 
                                        class="input mt-1 w-full"
                                        placeholder="พิมพ์ข้อเสนอแนะให้นักเรียน..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button 
                        type="button" 
                        @click="submitGrading" 
                        :disabled="isSubmitting"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                    >
                        <i class="fas fa-spinner fa-spin mr-2" x-show="isSubmitting"></i>
                        บันทึกคะแนน
                    </button>
                    <button 
                        type="button" 
                        @click="closeGradingModal" 
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:w-auto sm:text-sm"
                    >
                        ยกเลิก
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- File Integrity Modal -->
    <div 
        x-show="isIntegrityModalOpen" 
        style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto" 
        aria-labelledby="integrity-modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div 
                x-show="isIntegrityModalOpen" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                @click="closeIntegrityModal"
                aria-hidden="true"
            ></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div 
                x-show="isIntegrityModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full"
            >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-shield-alt text-blue-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="integrity-modal-title">
                                ข้อมูลไฟล์และการตรวจสอบ (File Integrity)
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    ข้อมูล metadata และ Hash ของไฟล์เพื่อใช้ตรวจสอบความถูกต้องและป้องกันการคัดลอก (Plagiarism)
                                </p>
                                
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 space-y-3 text-sm">
                                    <div class="grid grid-cols-3 gap-2">
                                        <span class="font-semibold text-gray-600">ชื่อไฟล์เดิม:</span>
                                        <span class="col-span-2 text-gray-800 break-all" x-text="currentIntegrity?.file_metadata?.original_name || '-'"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2">
                                        <span class="font-semibold text-gray-600">ขนาดไฟล์:</span>
                                        <span class="col-span-2 text-gray-800" x-text="currentIntegrity?.file_metadata?.size ? (currentIntegrity.file_metadata.size / 1024).toFixed(2) + ' KB' : '-'"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2">
                                        <span class="font-semibold text-gray-600">ประเภทไฟล์:</span>
                                        <span class="col-span-2 text-gray-800" x-text="currentIntegrity?.file_metadata?.mime_type || '-'"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2">
                                        <span class="font-semibold text-gray-600">เวลาอัปโหลด:</span>
                                        <span class="col-span-2 text-gray-800" x-text="currentIntegrity?.file_metadata?.uploaded_at || '-'"></span>
                                    </div>
                                    <div class="border-t border-gray-200 my-2"></div>
                                    <div class="space-y-1">
                                        <span class="font-semibold text-gray-600 block">File Hash (MD5):</span>
                                        <code class="block w-full bg-gray-100 p-2 rounded border border-gray-200 text-xs font-mono break-all text-gray-600" x-text="currentIntegrity?.file_hash || 'No Hash Data'"></code>
                                        <p class="text-xs text-blue-500 mt-1">
                                            <i class="fas fa-info-circle"></i> หากค่า Hash ตรงกับไฟล์อื่น แสดงว่าเป็นไฟล์เดียวกัน 100%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="closeIntegrityModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        ปิดหน้าต่าง
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inject Alpine Data -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('gradingApp', () => ({
                isGradingModalOpen: false,
                currentSubmissionId: null,
                currentScore: 0,
                currentFeedback: '',
                maxScore: 100,
                isSubmitting: false,

                // Integrity Modal Logic
                isIntegrityModalOpen: false,
                currentIntegrity: null,

                // Auto-Grading
                isAutoGrading: false,

                async autoGradeAllSubmissions(assignmentId) {
                    if (this.isAutoGrading) return;
                    this.isAutoGrading = true;

                    try {
                        const response = await fetch(`{{ url('typing/admin/submissions/auto-grade-all') }}/${assignmentId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            this.showToast(data.message, 'success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            this.showToast(data.error || 'เกิดข้อผิดพลาด', 'error');
                        }
                    } catch (error) {
                        console.error('Auto-grade error:', error);
                        this.showToast('เกิดข้อผิดพลาดในการตรวจอัตโนมัติ', 'error');
                    } finally {
                        this.isAutoGrading = false;
                    }
                },

                async autoGradeSubmission(submissionId, btnEl) {
                    btnEl.disabled = true;
                    btnEl.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    try {
                        const response = await fetch(`{{ url('typing/admin/submissions') }}/${submissionId}/auto-grade`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            this.showToast(`ให้คะแนน ${data.score} (${data.accuracy}%)`, 'success');
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            this.showToast(data.error || 'เกิดข้อผิดพลาด', 'error');
                            btnEl.disabled = false;
                            btnEl.innerHTML = '<i class="fas fa-robot"></i>';
                        }
                    } catch (error) {
                        console.error('Auto-grade error:', error);
                        this.showToast('เกิดข้อผิดพลาด', 'error');
                        btnEl.disabled = false;
                        btnEl.innerHTML = '<i class="fas fa-robot"></i>';
                    }
                },

                openGradingModal(id, score, feedback, max) {
                    this.currentSubmissionId = id;
                    this.currentScore = score;
                    this.currentFeedback = feedback;
                    this.maxScore = max;
                    this.isGradingModalOpen = true;
                    this.isSubmitting = false;
                },

                closeGradingModal() {
                    this.isGradingModalOpen = false;
                    this.currentSubmissionId = null;
                },

                openIntegrityModal(submission) {
                    this.currentIntegrity = submission;
                    this.isIntegrityModalOpen = true;
                },

                closeIntegrityModal() {
                    this.isIntegrityModalOpen = false;
                    this.currentIntegrity = null;
                },

                async submitGrading() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;

                    try {
                        const response = await fetch(`{{ url('typing/admin/submissions') }}/${this.currentSubmissionId}/score`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ 
                                score: this.currentScore, 
                                feedback: this.currentFeedback 
                            })
                        });

                        if (response.ok) {
                            this.showToast('บันทึกคะแนนเรียบร้อย', 'success');
                            this.closeGradingModal();
                            setTimeout(() => {
                                window.location.reload(); // Reload to reflect changes
                            }, 1000);
                        } else {
                            throw new Error('Failed to save score');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.showToast('เกิดข้อผิดพลาดในการบันทึก', 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                async quickGrade(id, score, btnEl, maxScore) {
                    // Find the row and update UI
                    const row = btnEl.closest('tr');
                    const allBtns = row.querySelectorAll('.flex.items-center.gap-1 button');
                    
                    // Disable all buttons in this row
                    allBtns.forEach(btn => {
                        btn.disabled = true;
                        btn.classList.add('opacity-50');
                    });

                    try {
                        const response = await fetch(`{{ url('typing/admin/submissions') }}/${id}/score`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ score: score, feedback: '' })
                        });

                        if (response.ok) {
                            // Update button styles - highlight the selected score
                            allBtns.forEach(btn => {
                                const btnScore = parseInt(btn.textContent.trim());
                                if (btnScore === score) {
                                    btn.classList.remove('bg-gray-100', 'hover:bg-primary-100', 'text-gray-700', 'hover:text-primary-600');
                                    btn.classList.add('bg-primary-600', 'text-white', 'ring-2', 'ring-primary-300');
                                } else {
                                    btn.classList.remove('bg-primary-600', 'text-white', 'ring-2', 'ring-primary-300');
                                    btn.classList.add('bg-gray-100', 'hover:bg-primary-100', 'text-gray-700', 'hover:text-primary-600');
                                }
                            });

                            // Update score display in the row
                            const scoreCell = row.querySelector('td:nth-child(6)');
                            if (scoreCell) {
                                scoreCell.innerHTML = `<span class="text-lg font-bold text-primary-600">${score}</span><span class="text-gray-500">/${maxScore}</span>`;
                            }

                            // Update status badge
                            const statusCell = row.querySelector('td:nth-child(5)');
                            if (statusCell) {
                                statusCell.innerHTML = '<span class="badge-secondary">ให้คะแนนแล้ว</span>';
                            }

                            // Remove pending highlight
                            row.classList.remove('bg-amber-50/50');

                            // Show success toast
                            this.showToast(`ให้ ${score} คะแนนเรียบร้อย`, 'success');
                        } else {
                            throw new Error('Failed to save score');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.showToast('เกิดข้อผิดพลาด กรุณาลองใหม่', 'error');
                    } finally {
                        // Re-enable all buttons
                        allBtns.forEach(btn => {
                            btn.disabled = false;
                            btn.classList.remove('opacity-50');
                        });
                    }
                },

                showToast(message, type = 'success') {
                    const toast = document.createElement('div');
                    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 transform transition-all duration-300 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
                    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>${message}`;
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.classList.add('opacity-0', 'translate-y-2');
                        setTimeout(() => toast.remove(), 300);
                    }, 2000);
                }
            }));
        });
    </script>
</div>
</x-typing-app>
