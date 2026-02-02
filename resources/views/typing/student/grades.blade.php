<x-typing-app :role="'student'" :title="'คะแนนของฉัน - ระบบวิชาพิมพ์หนังสือราชการ 1'">

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
            <i class="fas fa-star text-amber-500 mr-2"></i>
            คะแนนของฉัน
        </h1>
        <p class="text-gray-500 mt-1">สรุปผลคะแนนและความก้าวหน้าของคุณ</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Score -->
        <div class="card bg-gradient-to-br from-primary-500 to-primary-600 text-white">
            <div class="flex items-center justify-between mb-4">
                <span class="text-white/80">คะแนนรวม</span>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-trophy text-2xl"></i>
                </div>
            </div>
            <p class="text-4xl font-bold">{{ $totalScore }}</p>
            <p class="text-white/80 mt-1">จากคะแนนเต็ม</p>
            <div class="mt-4 bg-white/20 rounded-full h-2">
                <div class="bg-white rounded-full h-2" style="width: 100%"></div>
            </div>
            <p class="text-sm text-white/80 mt-2">สะสมจากการฝึกฝน</p>
        </div>

        <!-- Average Score -->
        <div class="card bg-gradient-to-br from-secondary-500 to-secondary-600 text-white">
            <div class="flex items-center justify-between mb-4">
                <span class="text-white/80">คะแนนเฉลี่ย</span>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <p class="text-4xl font-bold">{{ number_format($avgScore, 1) }}</p>
            <p class="text-white/80 mt-1">คะแนนเฉลี่ยต่อชิ้นงาน</p>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-sm">WPM เฉลี่ย: {{ number_format($avgWpm, 0) }}</span>
            </div>
        </div>

        <!-- Ranking -->
        <div class="card bg-gradient-to-br from-amber-500 to-orange-500 text-white">
            <div class="flex items-center justify-between mb-4">
                <span class="text-white/80">อันดับในชั้น</span>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-medal text-2xl"></i>
                </div>
            </div>
            <p class="text-4xl font-bold">#{{ $userRank }}</p>
            <p class="text-white/80 mt-1">จากทั้งหมด {{ $totalStudents }} คน</p>
            <div class="mt-4 flex items-center gap-2">
                @if($userRank <= 3)
                    <i class="fas fa-crown"></i>
                    <span class="text-sm">อันดับต้นๆ!</span>
                @else
                    <i class="fas fa-chart-line"></i>
                    <span class="text-sm">พัฒนาต่อไป!</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Chapter Scores -->
    @if(isset($chapterScores) && $chapterScores->count() > 0)
        <div class="card mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">
                <i class="fas fa-book-reader text-primary-500 mr-2"></i>
                คะแนนแยกตามบทเรียน
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($chapterScores as $chapter => $stats)
                    <div class="p-4 rounded-xl border border-gray-100 bg-white hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-gray-800">{{ $chapter ?: 'บทเรียนทั่วไป' }}</h3>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $stats['count'] }}
                                งาน</span>
                        </div>

                        <div class="flex items-end gap-2 mb-3">
                            <span class="text-3xl font-bold text-primary-600">{{ $stats['total'] }}</span>
                            <span class="text-sm text-gray-400 mb-1">/ {{ $stats['max'] }} คะแนน</span>
                        </div>

                        <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                            <div class="bg-primary-500 h-2 rounded-full"
                                style="width: {{ $stats['max'] > 0 ? ($stats['total'] / $stats['max']) * 100 : 0 }}%"></div>
                        </div>

                        <div class="flex justify-between text-xs text-gray-500">
                            <span>เฉลี่ย: {{ number_format($stats['avg'], 1) }}</span>
                            <span>{{ $stats['max'] > 0 ? number_format(($stats['total'] / $stats['max']) * 100, 0) : 0 }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Detailed Scores -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Score Chart -->
        <div class="card">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">
                <i class="fas fa-chart-bar text-primary-500 mr-2"></i>
                กราฟคะแนน
            </h2>
            <div class="h-64 bg-gray-50 rounded-xl flex items-center justify-center">
                @if($submissions->count() > 0)
                    <!-- Dynamic bar chart from real data -->
                    <div class="w-full px-6 flex items-end justify-around gap-4 h-48">
                        @foreach($submissions->take(5)->reverse() as $index => $submission)
                            @php
                                $maxScore = $submission->assignment->max_score ?? 100;
                                $heightPercent = $maxScore > 0 ? ($submission->score / $maxScore) * 100 : 0;
                                $isGood = $submission->score >= ($maxScore * 0.8);
                            @endphp
                            <div class="flex flex-col items-center">
                                <div class="w-12 bg-gradient-to-t {{ $isGood ? 'from-secondary-500 to-secondary-400' : 'from-primary-500 to-primary-400' }} rounded-t-lg"
                                    style="height: {{ $heightPercent }}%"></div>
                                <span
                                    class="text-xs text-gray-500 mt-2 truncate w-12 text-center">{{ Str::limit($submission->assignment->title, 6) }}</span>
                                <span class="text-sm font-bold text-gray-700">{{ $submission->score }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-gray-400 text-center">
                        <i class="fas fa-chart-bar text-4xl mb-2"></i>
                        <p>ยังไม่มีข้อมูลคะแนน</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Score Breakdown -->
        <div class="card">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">
                <i class="fas fa-list-ol text-primary-500 mr-2"></i>
                รายละเอียดคะแนน
            </h2>
            <div class="space-y-4">
                @forelse($submissions as $submission)
                    @if($submission->score !== null)
                        <div
                            class="flex items-center justify-between p-4 rounded-xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $submission->assignment->title }}</p>
                                    <p class="text-xs text-gray-500">ตรวจเมื่อ {{ $submission->updated_at->format('d M Y') }}
                                    </p>
                                    @if($submission->feedback)
                                        <div
                                            class="text-sm text-gray-600 mt-2 bg-gradient-to-r from-amber-50 to-orange-50 p-3 rounded-lg border border-amber-200">
                                            <div class="flex items-start gap-2">
                                                <i class="fas fa-clipboard-check text-amber-500 mt-0.5"></i>
                                                <div class="flex-1 whitespace-pre-line font-mono text-xs leading-relaxed">
                                                    {{ $submission->feedback }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-primary-600">{{ $submission->score }}</p>
                                <p class="text-xs text-gray-500">/{{ $submission->assignment->max_score }}</p>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-8 text-gray-500">
                        ยังไม่มีคะแนน
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Performance Badges -->
    <div class="mt-6 card">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">
            <i class="fas fa-award text-amber-500 mr-2"></i>
            เหรียญรางวัล
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div class="text-center p-4 rounded-xl bg-amber-50">
                <div
                    class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center shadow-lg mb-2">
                    <i class="fas fa-fire text-white text-2xl"></i>
                </div>
                <p class="font-medium text-gray-800 text-sm">ส่งงานทันเวลา</p>
                <p class="text-xs text-gray-500">5 ครั้งติดต่อกัน</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-secondary-50">
                <div
                    class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-secondary-400 to-secondary-500 flex items-center justify-center shadow-lg mb-2">
                    <i class="fas fa-star text-white text-2xl"></i>
                </div>
                <p class="font-medium text-gray-800 text-sm">คะแนนเกิน 90</p>
                <p class="text-xs text-gray-500">3 งานติดต่อกัน</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-primary-50">
                <div
                    class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-primary-400 to-primary-500 flex items-center justify-center shadow-lg mb-2">
                    <i class="fas fa-rocket text-white text-2xl"></i>
                </div>
                <p class="font-medium text-gray-800 text-sm">ก้าวหน้าเร็ว</p>
                <p class="text-xs text-gray-500">พัฒนาต่อเนื่อง</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-gray-100 opacity-50">
                <div class="w-16 h-16 mx-auto rounded-full bg-gray-300 flex items-center justify-center mb-2">
                    <i class="fas fa-lock text-gray-500 text-2xl"></i>
                </div>
                <p class="font-medium text-gray-500 text-sm">Perfect Score</p>
                <p class="text-xs text-gray-400">ยังไม่ได้รับ</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-gray-100 opacity-50">
                <div class="w-16 h-16 mx-auto rounded-full bg-gray-300 flex items-center justify-center mb-2">
                    <i class="fas fa-lock text-gray-500 text-2xl"></i>
                </div>
                <p class="font-medium text-gray-500 text-sm">Top 1</p>
                <p class="text-xs text-gray-400">ยังไม่ได้รับ</p>
            </div>
            <div class="text-center p-4 rounded-xl bg-gray-100 opacity-50">
                <div class="w-16 h-16 mx-auto rounded-full bg-gray-300 flex items-center justify-center mb-2">
                    <i class="fas fa-lock text-gray-500 text-2xl"></i>
                </div>
                <p class="font-medium text-gray-500 text-sm">ส่งครบ 100%</p>
                <p class="text-xs text-gray-400">ยังไม่ได้รับ</p>
            </div>
        </div>
    </div>

</x-typing-app>