<x-typing-app :role="'admin'" :title="'ตรวจงานที่ส่ง - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    <div x-data="gradingApp" class="space-y-8 pb-10">

        <!-- Aurora & Glass Header -->
        <div class="relative overflow-hidden bg-white border border-white/40 rounded-[2.5rem] p-8 shadow-2xl group transition-all duration-500 hover:shadow-primary-500/10">
            <!-- Aurora Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-white via-blue-50/50 to-indigo-50/30 opacity-80"></div>
            <div class="absolute top-[-30%] right-[-10%] w-[600px] h-[600px] bg-gradient-to-br from-blue-300/20 via-primary-300/20 to-indigo-200/20 rounded-full blur-[80px] animate-pulse-slow pointer-events-none mix-blend-multiply"></div>
            <div class="absolute bottom-[-20%] left-[-10%] w-[500px] h-[500px] bg-gradient-to-tr from-indigo-200/20 via-blue-200/20 to-purple-200/20 rounded-full blur-[80px] animate-pulse-slow delay-1000 pointer-events-none mix-blend-multiply"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-primary-500/30 transform group-hover:rotate-6 transition-transform">
                        <i class="fas fa-file-upload text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tight">ตรวจงานที่ส่ง</h1>
                        <p class="text-gray-500 mt-1 font-medium flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                            ตรวจสอบและให้คะแนนงานที่นักเรียนส่งเข้ามา
                        </p>
                    </div>
                </div>

                <!-- Admin Action Buttons (Glass style) -->
                <div class="flex flex-wrap items-center gap-3">
                    @if(request('assignment_id'))
                        <button @click="autoGradeAllSubmissions({{ request('assignment_id') }})"
                            class="flex items-center gap-2 px-5 py-3 rounded-2xl bg-white/60 backdrop-blur-md border border-white text-gray-700 font-bold hover:bg-white hover:shadow-xl hover:-translate-y-0.5 transition-all"
                            :disabled="isAutoGrading">
                            <i class="fas fa-robot text-primary-500" :class="{ 'fa-spin': isAutoGrading }"></i>
                            <span x-text="isAutoGrading ? 'กำลังตรวจอาศัย AI...' : 'ตรวจอัตโนมัติทั้งหมด'"></span>
                        </button>
                        <a href="{{ route('typing.admin.submissions.export.zip', ['assignment_id' => request('assignment_id')]) }}"
                            class="flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-900 text-white font-bold hover:bg-black hover:shadow-xl hover:-translate-y-0.5 transition-all shadow-lg shadow-gray-200">
                            <i class="fas fa-file-archive text-amber-400"></i>
                            ดาวน์โหลดไฟล์ (ZIP)
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bento Grid Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="group relative bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-primary-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-primary-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-primary-500 group-hover:text-white transition-colors">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">{{ $totalSubmissions }}</p>
                        <p class="text-sm text-gray-400 font-bold uppercase tracking-wider">งานทั้งหมด</p>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-amber-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-amber-500 group-hover:text-white transition-colors">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-amber-600 tracking-tight">{{ $pendingSubmissions }}</p>
                        <p class="text-sm text-gray-400 font-bold uppercase tracking-wider">รอตรวจ</p>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-secondary-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-secondary-50 text-secondary-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-secondary-500 group-hover:text-white transition-colors">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">{{ $gradedSubmissions }}</p>
                        <p class="text-sm text-gray-400 font-bold uppercase tracking-wider">ให้คะแนนแล้ว</p>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-accent-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-accent-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-accent-50 text-accent-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-accent-500 group-hover:text-white transition-colors">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">{{ number_format($averageScore, 1) }}</p>
                        <p class="text-sm text-gray-400 font-bold uppercase tracking-wider">คะแนนเฉลี่ย</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters (Modern Card) -->
        <form action="{{ route('typing.admin.submissions') }}" method="GET" class="bg-white rounded-[2rem] p-4 shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-5 relative group">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ค้นหาชื่อนักเรียน หรือ รหัสนักเรียน..." 
                        class="w-full pl-12 pr-4 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <select name="sort" class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all appearance-none cursor-pointer" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>ล่าสุด</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>เก่าสุด</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>ก - ฮ</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>ฮ - ก</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <select name="assignment_id" class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all appearance-none cursor-pointer" onchange="this.form.submit()">
                        <option value="">ทุกงาน</option>
                        @foreach($allAssignments as $assignment)
                            <option value="{{ $assignment->id }}" {{ request('assignment_id') == $assignment->id ? 'selected' : '' }}>{{ Str::limit($assignment->title, 20) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <select name="status" class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all appearance-none cursor-pointer" onchange="this.form.submit()">
                        <option value="">ทุกสถานะ</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รอตรวจ</option>
                        <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>ตรวจแล้ว</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <button type="submit" class="w-full h-full rounded-2xl bg-primary-500 text-white hover:bg-primary-600 transition-colors shadow-lg shadow-primary-500/20">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Submissions Table Card -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100 overflow-hidden relative">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <h2 class="text-xl font-black text-gray-800">รายการงานที่ส่ง</h2>
                </div>
                <button class="flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-primary-500 transition-colors">
                    <i class="fas fa-download"></i>
                    ส่งออก CSV
                </button>
            </div>

            <div class="overflow-x-auto -mx-8">
                <table class="w-full text-left border-separate border-spacing-y-4 px-8">
                    <thead>
                        <tr class="text-gray-400 text-xs font-black uppercase tracking-widest">
                            <th class="pb-2 px-6">นักเรียน</th>
                            <th class="pb-2">งาน / ประเภท</th>
                            <th class="pb-2 text-center">หลักฐานที่ส่ง</th>
                            <th class="pb-2">เวลาส่ง</th>
                            <th class="pb-2">สถานะ</th>
                            <th class="pb-2">คะแนน</th>
                            <th class="pb-2 text-right">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr class="group transition-all duration-300">
                                <td class="bg-gray-50/50 group-hover:bg-primary-50 px-6 py-4 rounded-l-[1.5rem] border-y border-l border-transparent group-hover:border-primary-100">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <img src="{{ $submission->user->avatar_url }}" alt=""
                                                class="w-12 h-12 rounded-full object-cover ring-2 ring-white shadow-md">
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-green-500 border-2 border-white"></div>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800">{{ $submission->user->name ?? 'Unknown' }}</p>
                                            <p class="text-xs font-bold text-gray-400 font-mono">{{ $submission->user->student_id ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="bg-gray-50/50 group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100">
                                    <p class="font-bold text-gray-700 leading-tight">{{ Str::limit($submission->assignment->title ?? '-', 30) }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] uppercase font-black px-2 py-0.5 rounded-md {{ $submission->file_path ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600' }}">
                                            {{ $submission->file_path ? 'File Upload' : 'Typing Test' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="bg-gray-50/50 group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100 text-center">
                                    @if($submission->file_path)
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ asset($submission->file_path) }}" target="_blank"
                                                class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary-600 hover:text-white hover:bg-primary-500 transition-all border border-gray-100">
                                                @if(Str::endsWith($submission->file_name, '.pdf'))
                                                    <i class="fas fa-file-pdf text-red-500 group-hover:text-white"></i>
                                                @else
                                                    <i class="fas fa-file-word text-blue-500 group-hover:text-white"></i>
                                                @endif
                                            </a>
                                            <button @click="openIntegrityModal({{ json_encode($submission) }})"
                                                class="w-8 h-8 rounded-lg text-gray-300 hover:text-blue-500 transition-colors"
                                                title="Check Integrity">
                                                <i class="fas fa-fingerprint"></i>
                                            </button>
                                        </div>
                                    @else
                                        <div class="inline-flex flex-col items-center">
                                            <span class="text-sm font-black text-gray-700">{{ $submission->wpm }} <small class="text-[10px] text-gray-400 uppercase">WPM</small></span>
                                            <span class="text-[10px] font-bold text-emerald-500">{{ $submission->accuracy }}% ACC</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="bg-gray-50/50 group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100">
                                    <p class="text-xs font-bold text-gray-700">{{ $submission->created_at->format('d/m/Y') }}</p>
                                    <p class="text-[10px] font-bold text-gray-400">{{ $submission->created_at->format('H:i น.') }}</p>
                                </td>
                                <td class="bg-gray-50/50 group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100">
                                    @if($submission->score === null)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-600 text-[10px] font-black uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            รอตรวจ
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-100 text-secondary-600 text-[10px] font-black uppercase tracking-wider">
                                            <i class="fas fa-check-double"></i>
                                            เรียบร้อย
                                        </span>
                                    @endif
                                </td>
                                <td class="bg-gray-50/50 group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100">
                                    <div class="flex flex-col">
                                        @if($submission->score === null)
                                            <span class="text-gray-300 font-black italic">--</span>
                                        @else
                                            <div class="flex items-baseline gap-0.5">
                                                <span class="text-xl font-black text-primary-600">{{ $submission->score }}</span>
                                                <span class="text-[10px] font-bold text-gray-400">/{{ $submission->assignment->max_score ?? 100 }}</span>
                                            </div>
                                            @if($submission->feedback && str_contains($submission->feedback, 'โหมดเข้มงวด'))
                                                <span class="text-[9px] font-black text-amber-500 uppercase flex items-center gap-1 mt-0.5">
                                                    <i class="fas fa-shield-halved"></i> Strict
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="bg-gray-50/50 group-hover:bg-primary-50 px-6 py-4 rounded-r-[1.5rem] border-y border-r border-transparent group-hover:border-primary-100 text-right">
                                    <div class="flex items-center justify-end gap-2"
                                        x-data="{ loading: false }">
                                        
                                        <!-- Quick Grade Buttons Group -->
                                        <div class="flex items-center bg-white p-1 rounded-xl shadow-sm border border-gray-100">
                                            @foreach([10, 8, 6] as $qScore)
                                                <button
                                                    @click="quickGrade({{ $submission->id }}, {{ $qScore }}, $el, {{ $submission->assignment->max_score ?? 100 }})"
                                                    class="w-8 h-8 rounded-lg text-xs font-black transition-all 
                                                            {{ $submission->score == $qScore ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/30' : 'text-gray-400 hover:text-primary-600 hover:bg-primary-50' }}"
                                                    :disabled="loading">
                                                    {{ $qScore }}
                                                </button>
                                            @endforeach
                                        </div>

                                        <!-- Actions -->
                                        <button @click="openGradingModal({{ $submission->id }}, {{ $submission->score ?? 'null' }}, '{{ addslashes($submission->feedback) }}', {{ $submission->assignment->max_score ?? 100 }})"
                                            class="w-10 h-10 rounded-xl bg-white border border-gray-100 shadow-sm text-gray-400 hover:text-primary-500 hover:border-primary-200 transition-all flex items-center justify-center">
                                            <i class="fas fa-pen-nib"></i>
                                        </button>

                                        <button @click="deleteSubmission({{ $submission->id }})"
                                            class="w-10 h-10 rounded-xl bg-white border border-gray-100 shadow-sm text-gray-300 hover:text-red-500 hover:border-red-200 transition-all flex items-center justify-center">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center text-gray-200 text-4xl">
                                            <i class="fas fa-ghost"></i>
                                        </div>
                                        <p class="text-gray-400 font-bold">ไม่พบข้อมูลการส่งงานในขณะนี้</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Modern) -->
            <div class="mt-10 pt-8 border-t border-gray-50 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">
                    Showing <span class="text-gray-800">{{ $submissions->firstItem() ?? 0 }}-{{ $submissions->lastItem() ?? 0 }}</span> 
                    of <span class="text-gray-800">{{ $submissions->total() }}</span> entries
                </p>
                <div class="premium-pagination">
                    {{ $submissions->links() }}
                </div>
            </div>
        </div>

        <!-- Premium Grading Modal -->
        <div x-show="isGradingModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="closeGradingModal"></div>

                <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="scale-95 translate-y-8"
                    x-transition:enter-end="scale-100 translate-y-0">
                    
                    <div class="bg-gradient-to-r from-primary-600 to-indigo-700 px-8 py-6 text-white">
                        <h3 class="text-2xl font-black">ให้คะแนนและคำแนะนำ</h3>
                        <p class="text-primary-100 text-sm opacity-80 mt-1">แจ้งคะแนนให้นักเรียนทราบทันที</p>
                    </div>

                    <div class="p-8 space-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest">คะแนนที่ได้ (เต็ม <span x-text="maxScore"></span>)</label>
                            <div class="relative">
                                <input type="number" x-model="currentScore" class="w-full pl-6 pr-12 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 text-2xl font-black transition-all" :max="maxScore">
                                <span class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 font-bold">PTS</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-black text-gray-400 uppercase tracking-widest">ข้อเสนอแนะเพิ่มเติม</label>
                            <textarea x-model="currentFeedback" rows="8"
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 text-sm font-medium leading-relaxed transition-all resize-none"
                                placeholder="เขียนคำแนะนำดีๆ ให้นักเรียนกันเถอะ..."></textarea>
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-gray-50 flex items-center gap-4">
                        <button type="button" @click="closeGradingModal"
                            class="flex-1 py-4 rounded-2xl text-sm font-black text-gray-500 hover:bg-gray-100 transition-all uppercase tracking-widest">
                            ยกเลิก
                        </button>
                        <button type="button" @click="submitGrading" :disabled="isSubmitting"
                            class="flex-[2] py-4 rounded-2xl bg-primary-600 text-white shadow-xl shadow-primary-500/30 font-black text-sm hover:bg-primary-700 hover:-translate-y-1 active:translate-y-0 transition-all uppercase tracking-widest disabled:opacity-50">
                            <span x-show="!isSubmitting">บันทึกคะแนน</span>
                            <i class="fas fa-spinner fa-spin" x-show="isSubmitting"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Integrity Modal (Premium Design) -->
        <div x-show="isIntegrityModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="closeIntegrityModal"></div>
                
                <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl overflow-hidden">
                    <div class="p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                                <i class="fas fa-shield-halved"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-gray-800">ตรวจสอบความถูกต้อง</h3>
                                <p class="text-sm font-bold text-gray-400">Metadata & Anti-Plagiarism Check</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 p-5 rounded-3xl border border-gray-100">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">ชื่อไฟล์เดิม</p>
                                <p class="text-sm font-black text-gray-700 break-all" x-text="currentIntegrity?.file_metadata?.original_name || '-'"></p>
                            </div>
                            <div class="bg-gray-50 p-5 rounded-3xl border border-gray-100">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">ขนาดไฟล์</p>
                                <p class="text-sm font-black text-gray-700" x-text="currentIntegrity?.file_metadata?.size ? (currentIntegrity.file_metadata.size / 1024).toFixed(2) + ' KB' : '-'"></p>
                            </div>
                            <div class="bg-gray-50 p-5 rounded-3xl border border-gray-100">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">ประเภทไฟล์</p>
                                <p class="text-sm font-black text-gray-700 uppercase" x-text="currentIntegrity?.file_metadata?.mime_type?.split('/')[1] || '-'"></p>
                            </div>
                            <div class="bg-gray-50 p-5 rounded-3xl border border-gray-100">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">อัปโหลดเมื่อ</p>
                                <p class="text-sm font-black text-gray-700" x-text="currentIntegrity?.file_metadata?.uploaded_at || '-'"></p>
                            </div>
                        </div>

                        <div class="bg-blue-600 rounded-3xl p-6 text-white shadow-xl shadow-blue-500/20">
                            <p class="text-[10px] font-black opacity-60 uppercase tracking-widest mb-2">Digital Signature (MD5 Hash)</p>
                            <code class="block font-mono text-xs break-all bg-white/20 p-3 rounded-xl border border-white/20 mb-3" x-text="currentIntegrity?.file_hash || 'No Hash Data'"></code>
                            <div class="flex items-center gap-2 text-[10px] font-bold opacity-80">
                                <i class="fas fa-info-circle"></i>
                                หากค่า Hash ตรงกับงานอื่น แสดงว่าเป็นไฟล์เดียวกัน 100%
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 text-center">
                        <button @click="closeIntegrityModal" class="px-8 py-3 rounded-2xl text-xs font-black text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-widest">
                            ปิดหน้าต่าง
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inject Alpine Data (Same Logic, New Toast) -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('gradingApp', () => ({
                    isGradingModalOpen: false,
                    currentSubmissionId: null,
                    currentScore: 0,
                    currentFeedback: '',
                    maxScore: 100,
                    isSubmitting: false,
                    isIntegrityModalOpen: false,
                    currentIntegrity: null,
                    isAutoGrading: false,

                    async autoGradeAllSubmissions(assignmentId) {
                        if (this.isAutoGrading) return;
                        this.isAutoGrading = true;
                        try {
                            const response = await fetch(`{{ url('typing/admin/submissions/auto-grade-all') }}/${assignmentId}`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                            });
                            const data = await response.json();
                            if (response.ok && data.success) {
                                this.showToast(data.message, 'success');
                                setTimeout(() => window.location.reload(), 1500);
                            } else {
                                this.showToast(data.error || 'เกิดข้อผิดพลาด', 'error');
                            }
                        } catch (error) {
                            this.showToast('เกิดข้อผิดพลาดระบบ AI', 'error');
                        } finally { this.isAutoGrading = false; }
                    },

                    openGradingModal(id, score, feedback, max) {
                        this.currentSubmissionId = id;
                        this.currentScore = score === 'null' ? 0 : score;
                        this.currentFeedback = feedback;
                        this.maxScore = max;
                        this.isGradingModalOpen = true;
                    },

                    closeGradingModal() { this.isGradingModalOpen = false; },
                    openIntegrityModal(submission) { this.currentIntegrity = submission; this.isIntegrityModalOpen = true; },
                    closeIntegrityModal() { this.isIntegrityModalOpen = false; },

                    async submitGrading() {
                        if (this.isSubmitting) return;
                        this.isSubmitting = true;
                        try {
                            const response = await fetch(`{{ url('typing/admin/submissions') }}/${this.currentSubmissionId}/score`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({ score: this.currentScore, feedback: this.currentFeedback })
                            });
                            if (response.ok) {
                                this.showToast('บันทึกคะแนนเข้าสู่ระบบแล้ว', 'success');
                                setTimeout(() => window.location.reload(), 800);
                            }
                        } catch (e) { this.showToast('บันทึกล้มเหลว', 'error'); } 
                        finally { this.isSubmitting = false; }
                    },

                    async quickGrade(id, score, btnEl, maxScore) {
                        const row = btnEl.closest('tr');
                        const btns = row.querySelectorAll('button');
                        btns.forEach(b => b.classList.add('opacity-50'));
                        
                        try {
                            const response = await fetch(`{{ url('typing/admin/submissions') }}/${id}/score`, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({ score: score, feedback: '' })
                            });
                            if (response.ok) {
                                this.showToast(`บันทึก {{ '${score}' }} คะแนนแล้ว`, 'success');
                                setTimeout(() => window.location.reload(), 500);
                            }
                        } catch (e) { this.showToast('ผิดพลาด', 'error'); }
                    },

                    async deleteSubmission(id) {
                        if(!confirm('ลบงานนี้ถาวร?')) return;
                        try {
                            const response = await fetch(`{{ url('typing/admin/submissions') }}/${id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            });
                            if (response.ok) {
                                this.showToast('ลบงานเรียบร้อย', 'success');
                                setTimeout(() => window.location.reload(), 800);
                            }
                        } catch (e) { this.showToast('ลบล้มเหลว', 'error'); }
                    },

                    showToast(message, type = 'success') {
                        const toast = document.createElement('div');
                        toast.className = `fixed bottom-8 right-8 px-8 py-4 rounded-[1.5rem] shadow-2xl text-white z-[200] transform transition-all duration-500 translate-y-20 flex items-center gap-3 backdrop-blur-md ${type === 'success' ? 'bg-emerald-500/90' : 'bg-red-500/90'}`;
                        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} text-xl"></i><span class="font-black text-sm uppercase tracking-widest">${message}</span>`;
                        document.body.appendChild(toast);
                        setTimeout(() => toast.classList.remove('translate-y-20'), 10);
                        setTimeout(() => {
                            toast.classList.add('opacity-0', 'scale-90');
                            setTimeout(() => toast.remove(), 500);
                        }, 3000);
                    }
                }));
            });
        </script>

        <style>
            .animate-pulse-slow { animation: pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
            @keyframes pulse { 0%, 100% { opacity: 0.2; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.1); } }
            
            .premium-pagination nav span[aria-current="page"] > span {
                @apply bg-primary-500 text-white rounded-xl px-4 py-2 font-black border-none shadow-lg shadow-primary-500/30;
            }
            .premium-pagination nav a {
                @apply bg-white text-gray-400 rounded-xl px-4 py-2 font-black border border-gray-100 hover:bg-primary-50 hover:text-primary-500 transition-all mx-1;
            }
        </style>
    </div>
</x-typing-app>