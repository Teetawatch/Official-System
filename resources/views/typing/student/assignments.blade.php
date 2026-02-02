<x-typing-app :role="'student'" :title="'งานที่ได้รับ - ระบบวิชาพิมพ์หนังสือราชการ 1'">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                <i class="fas fa-clipboard-list text-primary-500 mr-2"></i>
                งานที่ได้รับ
            </h1>
            <p class="text-gray-500 mt-1">ดูรายการงานทั้งหมดที่คุณได้รับมอบหมาย</p>
        </div>
        <div class="flex items-center gap-3">
            <select class="input py-2">
                <option value="">สถานะทั้งหมด</option>
                <option value="pending">รอส่งงาน</option>
                <option value="submitted">ส่งแล้ว</option>
                <option value="graded">ให้คะแนนแล้ว</option>
            </select>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                    <i class="fas fa-list-check text-primary-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalAssignments }}</p>
                    <p class="text-xs text-gray-500">งานทั้งหมด</p>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ max(0, $pendingCount) }}</p>
                    <p class="text-xs text-gray-500">รอส่งงาน</p>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-paper-plane text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $waitingGrade }}</p>
                    <p class="text-xs text-gray-500">รอตรวจ</p>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-secondary-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-secondary-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $gradedCount }}</p>
                    <p class="text-xs text-gray-500">ให้คะแนนแล้ว</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments List (Grouped by Chapter) -->
    <div class="space-y-8">
        @forelse($assignments->groupBy('chapter') as $chapter => $chapterAssignments)
            @php
                $chapterTitle = $chapter ?: 'บทเรียนทั่วไป';
            @endphp
            <div>
                <h3
                    class="text-xl font-bold text-gray-800 mb-4 pl-3 border-l-4 border-primary-500 shadow-sm rounded-r-lg bg-gray-50 py-2">
                    {{ $chapterTitle }}
                </h3>
                <div class="space-y-4">
                    @foreach($chapterAssignments as $assignment)
                        @php
                            $submission = $assignment->submissions->first();
                            $status = 'pending';
                            if ($submission) {
                                $status = $submission->score !== null ? 'graded' : 'submitted';
                            }

                            // Determine styling based on status and urgency
                            $isExpired = $status == 'pending' && $assignment->due_date && now()->greaterThan($assignment->due_date);
                            $isUrgent = !$isExpired && $status == 'pending' && $assignment->due_date && $assignment->due_date->isFuture() && $assignment->due_date->diffInDays(now()) <= 2;

                            if ($status == 'graded') {
                                $borderColor = 'border-secondary-500';
                                $iconBg = 'bg-gradient-to-br from-secondary-500 to-secondary-600';
                                $icon = 'fa-check-circle';
                                $statusBadge = '<span class="badge-secondary">ให้คะแนนแล้ว</span>';
                            } elseif ($status == 'submitted') {
                                $borderColor = 'border-blue-500';
                                $iconBg = 'bg-gradient-to-br from-blue-500 to-blue-600';
                                $icon = 'fa-hourglass-half';
                                $statusBadge = '<span class="badge-primary">รอตรวจ</span>';
                            } elseif ($isExpired) {
                                $borderColor = 'border-gray-400';
                                $iconBg = 'bg-gradient-to-br from-gray-400 to-gray-500';
                                $icon = 'fa-lock';
                                $statusBadge = '<span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">หมดเวลาส่ง</span>';
                            } elseif ($isUrgent) {
                                $borderColor = 'border-red-500';
                                $iconBg = 'bg-gradient-to-br from-red-500 to-red-600';
                                $icon = 'fa-fire';
                                $statusBadge = '<span class="badge-danger"><i class="fas fa-exclamation-triangle mr-1"></i>ด่วน!</span>';
                            } else {
                                $borderColor = 'border-amber-500';
                                $iconBg = 'bg-gradient-to-br from-amber-500 to-orange-500';
                                $icon = 'fa-file-alt';
                                $statusBadge = '<span class="badge-warning">เปิดรับงาน</span>';
                            }
                        @endphp

                        <div
                            class="card p-0 overflow-hidden border-l-4 {{ $borderColor }} {{ $isExpired ? 'opacity-75 grayscale-[0.5]' : '' }}">
                            <div class="p-5">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-14 h-14 rounded-xl {{ $iconBg }} flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <i class="fas {{ $icon }} text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <h3 class="text-lg font-semibold text-gray-800">{{ $assignment->title }}</h3>
                                                {!! $statusBadge !!}
                                            </div>
                                            <p class="text-sm text-gray-500 mb-2 truncate max-w-md">
                                                {{ Str::limit($assignment->content, 80) }}</p>
                                            <div class="flex flex-wrap items-center gap-4 text-sm">
                                                <span class="text-gray-500">
                                                    <i class="fas fa-star mr-1"></i>
                                                    คะแนนเต็ม: {{ $assignment->max_score }}
                                                </span>

                                                @if($assignment->time_limit)
                                                    <span class="text-gray-500">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        จำกัดเวลา: {{ $assignment->time_limit }} นาที
                                                    </span>
                                                @endif

                                                @if($status == 'graded')
                                                    <span class="text-secondary-600 font-semibold">
                                                        <i class="fas fa-check mr-1"></i>
                                                        คะแนนที่ได้: {{ $submission->score }}
                                                    </span>
                                                @elseif($status == 'submitted')
                                                    <span class="text-blue-600">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        ส่งเมื่อ: {{ $submission->created_at->format('d/m/Y') }}
                                                    </span>
                                                @elseif($assignment->due_date)
                                                    <span
                                                        class="{{ $isUrgent ? 'text-red-600 font-medium' : ($isExpired ? 'text-red-500 font-medium' : 'text-gray-500') }}">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $isExpired ? 'หมดเขตเมื่อ:' : 'ครบกำหนด:' }}
                                                        {{ $assignment->due_date->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 md:flex-shrink-0">
                                        {{-- Actions --}}
                                        @if($status == 'pending')
                                            <a href="#"
                                                class="btn-outline py-2 {{ $isExpired ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                                <i class="fas fa-eye mr-1"></i>
                                                รายละเอียด
                                            </a>
                                            @if($isExpired)
                                                <button disabled
                                                    class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed font-medium text-sm flex items-center">
                                                    <i class="fas fa-lock mr-2"></i>
                                                    ปิดรับแล้ว
                                                </button>
                                            @elseif($assignment->submission_type === 'file')
                                                <a href="{{ route('typing.student.upload', $assignment->id) }}"
                                                    class="btn-primary py-2">
                                                    <i class="fas fa-file-upload mr-1"></i>
                                                    อัปโหลดไฟล์
                                                </a>
                                            @else
                                                <a href="{{ route('typing.student.practice', $assignment->id) }}"
                                                    class="btn-primary py-2">
                                                    <i class="fas fa-keyboard mr-1"></i>
                                                    เริ่มพิมพ์
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('typing.student.submissions.show', $submission->id) }}"
                                                class="btn-ghost py-2">
                                                <i class="fas fa-eye mr-1"></i>
                                                ดูผลงาน
                                            </a>
                                            @if($status == 'graded')
                                                <a href="#" class="btn-outline py-2">
                                                    <i class="fas fa-comment mr-1"></i>
                                                    Feedback
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clipboard-check text-gray-300 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">ไม่มีงานที่ได้รับมอบหมาย</h3>
                <p class="text-gray-500">คุณไม่มีงานที่ต้องทำในขณะนี้</p>
            </div>
        @endforelse
    </div>

</x-typing-app>