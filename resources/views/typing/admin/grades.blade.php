<x-typing-app :role="'admin'" :title="'ตารางคะแนน - ระบบวิชาพิมพ์หนังสือราชการ 1'">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                <i class="fas fa-chart-bar text-primary-500 mr-2"></i>
                ตารางคะแนน
            </h1>
            <p class="text-gray-500 mt-1">ดูและจัดการคะแนนนักเรียนทั้งหมด</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('typing.admin.grades.export.csv') }}" class="btn-outline">
                <i class="fas fa-file-excel mr-2"></i>
                ส่งออก Excel
            </a>
            <a href="{{ route('typing.admin.grades.export.pdf') }}" target="_blank" class="btn-outline">
                <i class="fas fa-print mr-2"></i>
                พิมพ์รายงาน
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <form action="{{ route('typing.admin.grades') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="ค้นหาชื่อ, รหัสนักเรียน..." class="input pl-10">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <select name="class" class="input w-full md:w-48" onchange="this.form.submit()">
                <option value="">ทุกห้อง</option>
                @foreach($classes as $class)
                    <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>{{ $class }}</option>
                @endforeach
            </select>
            <select name="sort" class="input w-full md:w-48" onchange="this.form.submit()">
                <option value="total_desc" {{ request('sort') == 'total_desc' ? 'selected' : '' }}>คะแนนรวม (มาก-น้อย)
                </option>
                <option value="total_asc" {{ request('sort') == 'total_asc' ? 'selected' : '' }}>คะแนนรวม (น้อย-มาก)
                </option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>ชื่อ ก-ฮ</option>
                <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>รหัสนักเรียน</option>
            </select>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="card p-4 text-center">
            <p class="text-3xl font-bold text-gray-800">{{ $totalStudents }}</p>
            <p class="text-sm text-gray-500">นักเรียน</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-3xl font-bold text-primary-600">{{ number_format($averageScore, 1) }}</p>
            <p class="text-sm text-gray-500">คะแนนเฉลี่ย</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-3xl font-bold text-secondary-600">{{ number_format($maxScore, 1) }}</p>
            <p class="text-sm text-gray-500">คะแนนสูงสุด</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-3xl font-bold text-red-600">{{ number_format($minScore, 1) }}</p>
            <p class="text-sm text-gray-500">คะแนนต่ำสุด</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-3xl font-bold text-amber-600">{{ $passingRate }}%</p>
            <p class="text-sm text-gray-500">ผ่านเกณฑ์</p>
        </div>
    </div>

    <!-- Grade Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-12">อันดับ</th>
                        <th>นักเรียน</th>
                        @foreach($assignments as $assignment)
                            <th class="text-center">{{ Str::limit($assignment->title, 15) }}</th>
                        @endforeach
                        <th class="text-center bg-gray-50">รวม</th>
                        <th class="text-center bg-gray-50">เฉลี่ย</th>
                        <th class="text-center bg-gray-50">เกรด</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                        @php
                            $rank = ($students->currentPage() - 1) * $students->perPage() + $index + 1;
                            $totalScore = $student->typingSubmissions->sum('score');
                            $avgScore = $student->typingSubmissions->count() > 0 ? $student->typingSubmissions->avg('score') : 0;

                            // Calculate grade
                            $grade = 'F';
                            if ($avgScore >= 80)
                                $grade = 'A';
                            elseif ($avgScore >= 75)
                                $grade = 'B+';
                            elseif ($avgScore >= 70)
                                $grade = 'B';
                            elseif ($avgScore >= 65)
                                $grade = 'C+';
                            elseif ($avgScore >= 60)
                                $grade = 'C';
                            elseif ($avgScore >= 55)
                                $grade = 'D+';
                            elseif ($avgScore >= 50)
                                $grade = 'D';
                        @endphp
                        <tr class="{{ $rank <= 3 ? 'bg-amber-50/50' : '' }}">
                            <td class="text-center">
                                @if($rank == 1)
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-400 text-white">
                                        <i class="fas fa-crown text-sm"></i>
                                    </span>
                                @elseif($rank == 2)
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-400 text-white font-bold">{{ $rank }}</span>
                                @elseif($rank == 3)
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-600 text-white font-bold">{{ $rank }}</span>
                                @else
                                    <span class="text-gray-500 font-medium">{{ $rank }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $student->avatar_url }}" alt="" class="avatar-sm object-cover">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->student_id }} •
                                            {{ $student->class_name }}</p>
                                    </div>
                                </div>
                            </td>
                            @foreach($assignments as $assignment)
                                @php
                                    $submission = $student->typingSubmissions->where('assignment_id', $assignment->id)->first();
                                @endphp
                                <td class="text-center">
                                    @if($submission && $submission->score !== null)
                                        @php
                                            $scoreColor = 'text-gray-800';
                                            if ($submission->score >= 80)
                                                $scoreColor = 'text-secondary-600';
                                            elseif ($submission->score >= 60)
                                                $scoreColor = 'text-primary-600';
                                            elseif ($submission->score < 50)
                                                $scoreColor = 'text-red-600';
                                        @endphp
                                        <span class="font-medium {{ $scoreColor }}">{{ $submission->score }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center bg-gray-50"><span
                                    class="text-lg font-bold text-gray-800">{{ number_format($totalScore, 0) }}</span></td>
                            <td class="text-center bg-gray-50">
                                @php
                                    $avgColor = 'text-gray-800';
                                    if ($avgScore >= 80)
                                        $avgColor = 'text-secondary-600';
                                    elseif ($avgScore >= 60)
                                        $avgColor = 'text-primary-600';
                                    elseif ($avgScore < 50)
                                        $avgColor = 'text-red-600';
                                @endphp
                                <span class="font-bold {{ $avgColor }}">{{ number_format($avgScore, 1) }}%</span>
                            </td>
                            <td class="text-center bg-gray-50">
                                @php
                                    $badgeClass = 'badge-danger';
                                    if (in_array($grade, ['A']))
                                        $badgeClass = 'badge-secondary';
                                    elseif (in_array($grade, ['B+', 'B']))
                                        $badgeClass = 'badge-primary';
                                    elseif (in_array($grade, ['C+', 'C']))
                                        $badgeClass = 'badge-warning';
                                @endphp
                                <span class="{{ $badgeClass }}">{{ $grade }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 6 + $assignments->count() }}" class="text-center py-8 text-gray-500">
                                ยังไม่มีข้อมูลนักเรียน</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-500">แสดง {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} จาก
                {{ $students->total() }} รายการ</p>
            {{ $students->links() }}
        </div>
    </div>

    <!-- Grade Legend -->
    <div class="mt-6 card">
        <h3 class="font-semibold text-gray-800 mb-4">
            <i class="fas fa-info-circle text-primary-500 mr-2"></i>
            เกณฑ์การให้เกรด
        </h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <span class="badge-secondary">4</span>
                <span class="text-sm text-gray-600">80-100%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="badge-primary">3.5</span>
                <span class="text-sm text-gray-600">75-79%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="badge-warning">3</span>
                <span class="text-sm text-gray-600">70-74%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">2.5</span>
                <span class="text-sm text-gray-600">65-69%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="badge-danger">2</span>
                <span class="text-sm text-gray-600">60-64%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">1.5</span>
                <span class="text-sm text-gray-600">55-59%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-200 text-red-800">1</span>
                <span class="text-sm text-gray-600">50-54%</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-700 text-white">0</span>
                <span class="text-sm text-gray-600">0-49%</span>
            </div>
        </div>
    </div>

</x-typing-app>