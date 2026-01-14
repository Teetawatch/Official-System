<x-typing-app :role="'student'" :title="'แดชบอร์ดนักเรียน - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden bg-white border border-gray-100 rounded-3xl p-8 mb-8 text-gray-800 shadow-xl group hover:shadow-2xl transition-all duration-500">
        <!-- Modern Mesh Gradients (Warm & Visible at Top Right) -->
        <div class="absolute top-[-20%] right-[-5%] w-[500px] h-[500px] bg-gradient-to-br from-orange-200/50 via-amber-200/50 to-yellow-100/50 rounded-full blur-3xl animate-pulse-slow pointer-events-none"></div>
        <div class="absolute top-[10%] right-[15%] w-[400px] h-[400px] bg-gradient-to-tr from-rose-200/40 via-pink-200/40 to-red-100/40 rounded-full blur-3xl animate-pulse-slow delay-1000 pointer-events-none"></div>
        
        <!-- Geometric Patterns -->
        <svg class="absolute top-6 right-6 w-40 h-40 text-orange-200/60 rotate-12" viewBox="0 0 100 100" fill="currentColor">
            <rect x="10" y="10" width="20" height="20" rx="4" />
            <rect x="60" y="10" width="30" height="30" rx="6" />
            <rect x="10" y="50" width="40" height="40" rx="8" />
            <circle cx="80" cy="80" r="10" />
        </svg>
        <svg class="absolute bottom-0 right-1/4 w-64 h-64 text-rose-50/80 -translate-x-1/2 translate-y-1/4 z-0" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="currentColor" d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,79.6,-46.9C87.4,-34.7,90.1,-20.4,90.9,-6.2C91.7,8,90.6,22.1,84.3,34.9C78,47.7,66.5,59.3,53.2,67.6C39.9,76,24.8,81.2,9.3,82.4C-6.2,83.6,-22.1,80.8,-36.5,73.7C-50.9,66.6,-63.8,55.2,-73.2,41.9C-82.6,28.6,-88.4,13.4,-88.1,-1.6C-87.8,-16.6,-81.4,-31.4,-70.9,-43.3C-60.4,-55.2,-45.8,-64.2,-31.2,-71.1C-16.6,-78,-2,-82.8,11.5,-81.2C25,-79.6,30.5,-83.6,44.7,-76.4Z" transform="translate(100 100)" />
        </svg>

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 md:gap-8">
            <!-- Avatar Section -->
            <div class="relative group cursor-pointer">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                <div class="relative">
                    <img 
                        src="{{ $user->avatar_url }}" 
                        alt="Avatar" 
                        class="w-24 h-24 md:w-28 md:h-28 rounded-full shadow-lg object-cover ring-4 ring-white transition-transform duration-500 group-hover:scale-105"
                    >
                    <div class="absolute bottom-0 right-0 bg-white p-1 rounded-full shadow-md">
                        <div class="w-5 h-5 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full border-2 border-white animate-pulse"></div>
                    </div>
                </div>
            </div>

            <!-- Text Content -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-3 text-transparent bg-clip-text bg-gradient-to-r from-gray-900 via-gray-700 to-gray-800 drop-shadow-sm">
                    สวัสดีคุณ <span class="text-blue-600   decoration-blue-200 decoration-2 underline-offset-4">{{ $user->name }}</span>
                </h1>
                
                <p class="text-gray-500 text-lg mb-8 max-w-2xl font-medium leading-relaxed">
                    "ความพยายามในวันนี้ คือความสำเร็จในวันข้างหน้า" <br>
                    <span class="text-sm text-gray-400 font-normal">มาฝึกพิมพ์วันละนิด เพื่ออนาคตที่สดใสกันเถอะ! ✨</span>
                </p>

                <!-- Stats Badges -->
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <div class="group/badge flex items-center gap-3 px-5 py-3 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-100 hover:bg-blue-50/30 transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg group-hover/badge:scale-110 transition-transform">
                            <i class="fas fa-id-card-clip"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">รหัสนักเรียน</p>
                            <p class="text-base font-bold text-gray-800">{{ $user->student_id ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="group/badge flex items-center gap-3 px-5 py-3 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-md hover:border-purple-100 hover:bg-purple-50/30 transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-lg group-hover/badge:scale-110 transition-transform">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">ชั้นเรียน</p>
                            <p class="text-base font-bold text-gray-800">{{ $user->class_name ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <div class="mt-6 md:mt-0 relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl blur opacity-20 group-hover:opacity-40 transition duration-300"></div>
                <a href="{{ route('typing.profile') }}" class="relative flex items-center gap-3 px-6 py-4 bg-white text-gray-800 rounded-xl font-bold border border-gray-100 shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-8 h-8 rounded-lg bg-gray-900 text-white flex items-center justify-center">
                        <i class="fas fa-pen-nib text-sm"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 font-medium">ต้องการแก้ไข?</span>
                        <span>แก้ไขข้อมูลส่วนตัว</span>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <!-- My Score -->
        <div class="card group hover:shadow-lg">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">คะแนนรวม</span>
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-star text-white"></i>
                    </div>
                </div>
                <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ number_format($totalScore, 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">สะสมจากงานที่ตรวจแล้ว</p>
                <div class="mt-2 progress">
                    @php $scorePercent = $submissions->count() > 0 ? min(100, ($avgScore ?? 0)) : 0; @endphp
                    <div class="progress-bar bg-gradient-to-r from-primary-500 to-primary-400" style="width: {{ $scorePercent }}%"></div>
                </div>
            </div>
        </div>
        
        <!-- Ranking -->
        <div class="card group hover:shadow-lg">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">อันดับ</span>
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-trophy text-white"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-1">
                    <p class="text-2xl md:text-3xl font-bold text-gray-800">#{{ $userRank }}</p>
                    <span class="text-sm text-gray-500">/ {{ $totalStudents }} คน</span>
                </div>
                <p class="text-xs text-secondary-600 mt-1">
                    @if($userRank <= 3)
                        <i class="fas fa-medal mr-1"></i> Top 3!
                    @else
                        <i class="fas fa-chart-line mr-1"></i> สู้ต่อไป!
                    @endif
                </p>
            </div>
        </div>
        
        <!-- Completed Tasks -->
        <div class="card group hover:shadow-lg">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">ส่งงานแล้ว</span>
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-secondary-500 to-secondary-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-1">
                    <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ $submittedCount }}</p>
                    <span class="text-sm text-gray-500">/ {{ $totalAssignments }} งาน</span>
                </div>
                @php $remaining = max(0, $totalAssignments - $submittedCount); @endphp
                <p class="text-xs {{ $remaining > 0 ? 'text-amber-600' : 'text-secondary-600' }} mt-1">
                    @if($remaining > 0)
                        <i class="fas fa-clock mr-1"></i> เหลืออีก {{ $remaining }} งาน
                    @else
                        <i class="fas fa-check mr-1"></i> ส่งครบแล้ว!
                    @endif
                </p>
            </div>
        </div>
        
        <!-- Average Score -->
        <div class="card group hover:shadow-lg">
            <div class="flex flex-col">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">คะแนนเฉลี่ย</span>
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                </div>
                <p class="text-2xl md:text-3xl font-bold text-gray-800">{{ number_format($avgScore, 1) }}%</p>
                <p class="text-xs {{ $avgScore >= 80 ? 'text-secondary-600' : ($avgScore >= 60 ? 'text-primary-600' : 'text-amber-600') }} mt-1">
                    @if($avgScore >= 80)
                        <i class="fas fa-fire mr-1"></i> ยอดเยี่ยม!
                    @elseif($avgScore >= 60)
                        <i class="fas fa-thumbs-up mr-1"></i> ดีมาก!
                    @else
                        <i class="fas fa-chart-line mr-1"></i> พัฒนาต่อไป!
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Pending Assignments (2 cols) -->
        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-clipboard-list text-primary-500 mr-2"></i>
                    งานที่ต้องทำ
                </h2>
                <a href="{{ route('typing.student.assignments') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    ดูทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($pendingAssignments as $assignment)
                    @php
                        $isUrgent = $assignment->due_date && $assignment->due_date->isFuture() && $assignment->due_date->diffInDays(now()) <= 2;
                    @endphp
                    <div class="flex gap-4 p-4 rounded-xl {{ $isUrgent ? 'border-l-4 border-red-500 bg-red-50 hover:bg-red-100' : 'border border-gray-200 hover:border-primary-300 hover:shadow-md' }} transition-all">
                        <div class="w-12 h-12 rounded-xl {{ $isUrgent ? 'bg-red-500' : 'bg-amber-500' }} flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $isUrgent ? 'fa-fire' : 'fa-file-alt' }} text-white text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $assignment->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">คะแนนเต็ม {{ $assignment->max_score }} คะแนน</p>
                                </div>
                                @if($isUrgent)
                                    <span class="badge-danger flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        ด่วน!
                                    </span>
                                @else
                                    <span class="badge-warning flex-shrink-0">เปิดรับงาน</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-4 mt-3">
                                @if($assignment->due_date)
                                    <span class="text-xs {{ $isUrgent ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                        @if($assignment->due_date && $assignment->due_date->isFuture())
                                            @if($isUrgent)
                                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 border border-red-100 rounded-lg shadow-sm" 
                                                     x-data="{ 
                                                        timeLeft: '', 
                                                        target: {{ $assignment->due_date->timestamp * 1000 }},
                                                        update() {
                                                            const now = new Date().getTime();
                                                            const diff = this.target - now;
                                                            
                                                            if (diff <= 0) {
                                                                this.timeLeft = 'หมดเวลา';
                                                                return;
                                                            }
                                                            
                                                            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                                            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                                            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                                                            
                                                            let text = '';
                                                            if(days > 0) text += days + 'วัน ';
                                                            text += hours.toString().padStart(2, '0') + ':' + 
                                                                    minutes.toString().padStart(2, '0') + ':' + 
                                                                    seconds.toString().padStart(2, '0');
                                                            this.timeLeft = text;
                                                        }
                                                     }"
                                                     x-init="update(); setInterval(() => update(), 1000)"
                                                >
                                                    <i class="fas fa-stopwatch text-lg animate-pulse"></i>
                                                    <span class="font-mono text-lg font-bold tracking-wide text-red-600" x-text="timeLeft">กำลังโหลด...</span>
                                                </div>
                                            @else
                                                <i class="fas fa-calendar mr-1"></i>
                                                กำหนดส่ง {{ $assignment->due_date->format('d/m/Y H:i') }}
                                            @endif
                                        @else
                                           <span class="text-gray-400">ไม่มีกำหนดส่ง</span>
                                        @endif
                                    </span>
                                @endif
                                @if($assignment->submission_type === 'file')
                                    <a href="{{ route('typing.student.upload', $assignment->id) }}" class="btn-primary text-xs py-1.5">
                                        <i class="fas fa-file-upload mr-1"></i>
                                        อัปโหลดไฟล์
                                    </a>
                                @else
                                    <a href="{{ route('typing.student.practice', $assignment->id) }}" class="btn-primary text-l py-1.5">
                                        <i class="fas fa-keyboard mr-1"></i>
                                        เริ่มพิมพ์
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-check-circle text-4xl text-secondary-400 mb-2"></i>
                        <p>ส่งงานครบทุกชิ้นแล้ว!</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- My Scores -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-star text-amber-500 mr-2"></i>
                    คะแนนล่าสุด
                </h2>
                <a href="{{ route('typing.student.grades') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    ดูทั้งหมด
                </a>
            </div>
            
            <div class="space-y-4">
                @php
                    $colors = ['secondary', 'primary', 'accent', 'amber'];
                @endphp
                @forelse($submissions->take(4) as $index => $submission)
                    @php
                        $color = $colors[$index % count($colors)];
                        $percent = $submission->assignment ? min(100, ($submission->score / $submission->assignment->max_score) * 100) : 0;
                    @endphp
                    <div class="p-3 rounded-xl bg-{{ $color }}-50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-800">{{ Str::limit($submission->assignment->title ?? 'N/A', 20) }}</span>
                            <span class="text-lg font-bold text-{{ $color }}-600">{{ $submission->score }}/{{ $submission->assignment->max_score ?? 100 }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-to-r from-{{ $color }}-500 to-{{ $color }}-400" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-2"></i>
                        <p>ยังไม่มีคะแนน</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Progress Chart -->
    <div class="card mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-chart-area text-primary-500 mr-2"></i>
                พัฒนาการการพิมพ์ (10 ครั้งล่าสุด)
            </h2>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="progressChart"></canvas>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('progressChart').getContext('2d');
            const chartData = @json($chartData);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'ความแม่นยำ (%)',
                            data: chartData.accuracy,
                            borderColor: '#10b981', // green-500
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            yAxisID: 'y'
                        },
                        {
                            label: 'ความเร็ว (WPM)',
                            data: chartData.wpm,
                            borderColor: '#3b82f6', // blue-500
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: { display: true, text: 'ความแม่นยำ (%)' },
                            min: 0,
                            max: 100
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: { display: true, text: 'WPM' },
                            grid: {
                                drawOnChartArea: false,
                            },
                        },
                    }
                }
            });
        });
    </script>
    
    <!-- Mini Leaderboard -->
    <div class="mt-6 card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-trophy text-amber-500 mr-2"></i>
                Top 5 ผู้นำ
            </h2>
            <a href="{{ route('typing.leaderboard') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                ดูทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @forelse($leaderboard as $index => $leader)
                @php
                    $rank = $index + 1;
                    $isCurrentUser = $leader->id === $user->id;
                    $score = $leader->typing_submissions_sum_score ?? 0;
                @endphp
                
                @if($rank == 1)
                    {{-- Gold --}}
                    <div class="relative p-4 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 text-center text-white shadow-lg {{ $isCurrentUser ? 'ring-4 ring-primary-300' : '' }}">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-8 h-8 bg-yellow-300 rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-crown text-amber-600"></i>
                        </div>
                        @if($isCurrentUser)
                            <span class="absolute top-2 right-2 text-xs bg-white/30 px-2 py-0.5 rounded-full">คุณ</span>
                        @endif
                        <img src="{{ $leader->avatar_url }}" alt="" class="w-14 h-14 rounded-full mx-auto mt-4 border-4 border-white/30 object-cover">
                        <p class="font-bold mt-2">{{ Str::limit($leader->name, 12) }}</p>
                        <p class="text-sm opacity-90">{{ number_format($score, 0) }} คะแนน</p>
                    </div>
                @elseif($rank == 2)
                    {{-- Silver --}}
                    <div class="relative p-4 rounded-xl bg-gradient-to-br from-gray-400 to-gray-500 text-center text-white shadow-lg {{ $isCurrentUser ? 'ring-4 ring-primary-300' : '' }}">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold shadow-lg">
                            2
                        </div>
                        @if($isCurrentUser)
                            <span class="absolute top-2 right-2 text-xs bg-white/30 px-2 py-0.5 rounded-full">คุณ</span>
                        @endif
                        <img src="{{ $leader->avatar_url }}" alt="" class="w-14 h-14 rounded-full mx-auto mt-4 border-4 border-white/30 object-cover">
                        <p class="font-bold mt-2">{{ Str::limit($leader->name, 12) }}</p>
                        <p class="text-sm opacity-90">{{ number_format($score, 0) }} คะแนน</p>
                    </div>
                @elseif($rank == 3)
                    {{-- Bronze --}}
                    <div class="relative p-4 rounded-xl bg-gradient-to-br from-amber-600 to-orange-600 text-center text-white shadow-lg {{ $isCurrentUser ? 'ring-4 ring-primary-300' : '' }}">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                            3
                        </div>
                        @if($isCurrentUser)
                            <span class="absolute top-2 right-2 text-xs bg-white/30 px-2 py-0.5 rounded-full">คุณ</span>
                        @endif
                        <img src="{{ $leader->avatar_url }}" alt="" class="w-14 h-14 rounded-full mx-auto mt-4 border-4 border-white/30 object-cover">
                        <p class="font-bold mt-2">{{ Str::limit($leader->name, 12) }}</p>
                        <p class="text-sm opacity-90">{{ number_format($score, 0) }} คะแนน</p>
                    </div>
                @else
                    {{-- Rank 4 and 5 --}}
                    <div class="relative p-4 rounded-xl bg-gray-100 text-center shadow {{ $isCurrentUser ? 'ring-4 ring-primary-300' : '' }}">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-bold shadow">
                            {{ $rank }}
                        </div>
                        @if($isCurrentUser)
                            <span class="absolute top-2 right-2 text-xs bg-gray-300 px-2 py-0.5 rounded-full text-gray-600">คุณ</span>
                        @endif
                        <img src="{{ $leader->avatar_url }}" alt="" class="w-14 h-14 rounded-full mx-auto mt-4 border-4 border-gray-200 object-cover">
                        <p class="font-bold mt-2 text-gray-800">{{ Str::limit($leader->name, 12) }}</p>
                        <p class="text-sm text-gray-500">{{ number_format($score, 0) }} คะแนน</p>
                    </div>
                @endif
            @empty
                <div class="col-span-5 text-center py-8 text-gray-500">
                    <i class="fas fa-trophy text-4xl text-gray-300 mb-2"></i>
                    <p>ยังไม่มีข้อมูลผู้นำ</p>
                </div>
            @endforelse
        </div>
    </div>
    
</x-typing-app>
