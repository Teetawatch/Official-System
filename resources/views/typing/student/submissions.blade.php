<x-typing-app :role="'student'" :title="'งานที่ส่งแล้ว - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                <i class="fas fa-paper-plane text-primary-500 mr-2"></i>
                งานที่ส่งแล้ว
            </h1>
            <p class="text-gray-500 mt-1">ประวัติการส่งงานทั้งหมดของคุณ</p>
        </div>
        <div class="flex items-center gap-3">
            <select class="input py-2">
                <option value="">ทุกสถานะ</option>
                <option value="pending">รอตรวจ</option>
                <option value="graded">ตรวจแล้ว</option>
            </select>
        </div>
    </div>
    
    <!-- Submissions Table -->
    <div class="card">
        <div class="overflow-x-auto w-full">
            <table class="table">
                <thead>
                    <tr>
                        <th>งาน</th>
                        <th>วันที่ส่ง</th>
                        <th>ไฟล์ที่ส่ง</th>
                        <th>สถานะ</th>
                        <th>คะแนน</th>
                        <th>การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-file-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $submission->assignment->title }}</p>
                                    <p class="text-xs text-gray-500">คะแนนเต็ม {{ $submission->assignment->max_score }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-gray-800">{{ $submission->created_at->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $submission->created_at->format('H:i น.') }}</p>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-700">WPM: {{ $submission->wpm }}</span>
                                <span class="text-xs text-gray-500">ความแม่นยำ: {{ $submission->accuracy }}%</span>
                            </div>
                        </td>
                        <td>
                            @if($submission->score !== null)
                                <span class="badge-secondary">ตรวจแล้ว</span>
                            @else
                                <span class="badge-warning">รอตรวจ</span>
                            @endif
                        </td>
                        <td>
                            @if($submission->score !== null)
                                <span class="text-lg font-bold text-secondary-600">{{ $submission->score }}</span>
                                <span class="text-gray-500">/{{ $submission->assignment->max_score }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('typing.student.submissions.show', $submission->id) }}" class="btn-ghost text-sm inline-flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                ดู
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            ยังไม่มีประวัติการส่งงาน
                        </td>
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
    
</x-typing-app>
