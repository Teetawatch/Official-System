<x-typing-app :role="auth()->user()->role" :title="'บอร์ดผู้นำ - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    @php
        // Check once if reward system is available
        $rewardSystemAvailable = false;
        try {
            $rewardSystemAvailable = \Illuminate\Support\Facades\Schema::hasColumn('users', 'equipped_frame')
                && \Illuminate\Support\Facades\Schema::hasTable('reward_items')
                && class_exists(\App\Models\RewardItem::class);
        } catch (\Exception $e) {
            $rewardSystemAvailable = false;
        }
    @endphp

    <!-- Background Decoration -->
    <div class="fixed inset-0 min-h-screen pointer-events-none -z-10 overflow-hidden">
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-blue-400/20 rounded-full blur-[120px] animate-pulse-slow mix-blend-multiply"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-purple-400/20 rounded-full blur-[120px] animate-pulse-slow animation-delay-2000 mix-blend-multiply"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-pink-400/10 rounded-full blur-[150px] animate-pulse-slow animation-delay-4000 mix-blend-multiply"></div>
    </div>

    <!-- Header Section -->
    <div class="relative mb-12 text-center" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)">
        <div class="inline-block relative mb-4" x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-50 rotate-12" x-transition:enter-end="opacity-100 scale-100 rotate-0">
            <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl blur opacity-40 animate-pulse"></div>
            <div class="relative bg-gradient-to-br from-amber-300 to-orange-500 p-4 rounded-2xl shadow-lg ring-4 ring-white/30 transform rotate-[-5deg] hover:rotate-[5deg] transition-transform duration-500">
                <i class="fas fa-trophy text-4xl text-white drop-shadow-md"></i>
            </div>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-slate-800 via-indigo-600 to-slate-800 mb-4 tracking-tight"
            ทำเนียบยอดฝีมือ
        </h1>
        <p class="text-slate-500 text-lg font-medium max-w-2xl mx-auto"
            x-show="shown" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            ทำเนียบสุดยอดนักพิมพ์จากสมรภูมิ Classroom Battle <br class="hidden md:inline">แห่งความเร็วและความแม่นยำ
        </p>

        <!-- Search & Filter Bar -->
        <div class="mt-8 max-w-4xl mx-auto px-4" x-show="shown" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white/80 backdrop-blur-xl border border-white/50 p-2 rounded-2xl shadow-xl shadow-indigo-100/50 flex flex-col md:flex-row gap-2">
                <form method="GET" action="{{ route('typing.leaderboard') }}" class="flex-1 flex gap-2">
                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" 
                            class="block w-full pl-10 pr-3 py-3 border-none rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 transition-all font-medium text-slate-700 placeholder-slate-400" 
                            placeholder="ค้นหาชื่อนักเรียน...">
                    </div>
                    <div class="relative w-full md:w-64">
                        <select name="class" onchange="this.form.submit()" 
                            class="block w-full pl-4 pr-10 py-3 border-none rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/50 transition-all font-medium text-slate-700 cursor-pointer appearance-none">
                            <option value="all" {{ ($classFilter ?? '') === 'all' || !($classFilter ?? '') ? 'selected' : '' }}>🏛️ ทุกห้องเรียน</option>
                            @foreach($classes ?? [] as $class)
                                <option value="{{ $class }}" {{ ($classFilter ?? '') === $class ? 'selected' : '' }}>📚 {{ $class }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($students->isNotEmpty())
        <!-- Podium Section -->
        <div class="relative px-4 mb-20">
            <!-- Floor Effect -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 w-4/5 h-20 bg-indigo-500/10 blur-3xl rounded-[100%] pointer-events-none"></div>

            <div class="flex flex-col md:flex-row items-end justify-center gap-4 md:gap-8 perspective-1000">

                <!-- 2nd Place -->
                @if(isset($top3[1]))
                    @php
                        $u = $top3[1];
                        $f = $u->equippedFrame;
                        $ft = $u->equippedTitle;
                        $ftData = optional($ft)->data ?? [];
                        $fData = optional($f)->data ?? [];
                        $fGrad = $fData['gradient'] ?? 'from-slate-300 to-slate-400';
                        $score = number_format($u->typing_submissions_sum_score ?? 0);
                        $points = number_format($u->points ?? 0);
                    @endphp
                    <div class="order-1 w-full max-w-xs md:w-1/3 md:translate-y-0 z-10 group" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                        <div class="relative flex flex-col items-center">
                            <!-- Crown/Rank -->
                            <div class="absolute -top-12 opacity-80 animate-bounce-slow animation-delay-1000 transition-all duration-300 group-hover:-translate-y-2">
                                <div class="w-10 h-10 bg-slate-200 text-slate-600 rounded-full flex items-center justify-center font-bold text-xl shadow-lg border-2 border-white">2</div>
                            </div>

                            <!-- Avatar -->
                            <div class="relative mb-[-40px] z-20 transition-transform duration-500 group-hover:scale-105 group-hover:-translate-y-2">
                                 <div class="w-24 h-24 rounded-full p-[3px] bg-gradient-to-br {{ $fGrad }} shadow-[0_10px_40px_-10px_rgba(0,0,0,0.2)]">
                                    <div class="w-full h-full rounded-full border-[3px] border-white overflow-hidden bg-white">
                                        <img src="{{ $u->avatar_url }}" class="w-full h-full object-cover">
                                    </div>
                                 </div>
                                 @if($f && isset($fData['icon']))
                                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md border border-slate-100 text-sm animate-pulse-slow">
                                        {{ $fData['icon'] }}
                                    </div>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="w-full bg-white/60 backdrop-blur-md rounded-2xl pt-12 pb-6 px-4 shadow-xl border border-white/50 text-center relative overflow-hidden transition-all duration-300 group-hover:bg-white/80 group-hover:shadow-2xl group-hover:shadow-slate-200/50">
                                <!-- Shine Effect -->
                                <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/40 to-transparent translate-x-[-150%] skew-x-[-20deg] group-hover:translate-x-[150%] transition-transform duration-1000 ease-in-out"></div>

                                <h3 class="text-xl font-bold text-slate-800 truncate mb-1 relative z-10">{{ $u->name }}</h3>

                                @if($ft)
                                    <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-gradient-to-r {{ optional($ft)->rarity_color ?? 'from-slate-100 to-slate-200' }} text-[10px] font-bold shadow-sm mb-3 relative z-10">
                                        @if(isset($ftData['emoji']))<span>{{ $ftData['emoji'] }}</span>@endif
                                        <span>{{ optional($ft)->name ?? '' }}</span>
                                    </div>
                                @endif

                               <div class="grid grid-cols-2 gap-2 mt-2">
                                    <div class="bg-indigo-50/50 rounded-lg p-2">
                                        <div class="text-[10px] text-indigo-400 font-semibold uppercase">คะแนนรวม</div>
                                        <div class="text-indigo-700 font-bold">{{ $score }}</div>
                                    </div>
                                    <div class="bg-amber-50/50 rounded-lg p-2">
                                         <div class="text-[10px] text-amber-500 font-semibold uppercase">คะแนนประลอง</div>
                                        <div class="text-amber-700 font-bold">{{ $points }}</div>
                                    </div>
                               </div>
                            </div>

                            <!-- Stand base -->
                             <div class="h-4 w-4/5 bg-gradient-to-b from-slate-200 to-transparent opacity-50 mx-auto blur-sm"></div>
                        </div>
                    </div>
                @endif

                <!-- 1st Place -->
                @if(isset($top3[0]))
                    @php
                        $u = $top3[0];
                        $f = $u->equippedFrame;
                        $ft = $u->equippedTitle;
                        $ftData = optional($ft)->data ?? [];
                        $fData = optional($f)->data ?? [];
                        $fGrad = $fData['gradient'] ?? 'from-yellow-300 to-amber-500';
                        $score = number_format($u->typing_submissions_sum_score ?? 0);
                        $points = number_format($u->points ?? 0);
                    @endphp
                    <div class="order-2 w-full max-w-xs md:w-1/3 -mt-8 md:-mt-16 z-20 group" x-data="{ hover: false }">
                        <div class="relative flex flex-col items-center">
                             <!-- Crown -->
                            <div class="absolute -top-16 z-30 animate-bounce-slow transition-all duration-300 group-hover:-translate-y-4 filter drop-shadow-[0_0_15px_rgba(251,191,36,0.5)]">
                                <i class="fas fa-crown text-5xl text-yellow-400"></i>
                            </div>

                            <!-- Avatar -->
                            <div class="relative mb-[-50px] z-20 transition-transform duration-500 group-hover:scale-110 group-hover:-translate-y-2">
                                 <div class="absolute inset-0 bg-yellow-400 rounded-full blur-xl opacity-40 animate-pulse"></div>
                                 <div class="w-32 h-32 rounded-full p-1 bg-gradient-to-br {{ $fGrad }} shadow-[0_20px_50px_-10px_rgba(245,158,11,0.4)] relative">
                                    <div class="w-full h-full rounded-full border-4 border-white overflow-hidden bg-white">
                                        <img src="{{ $u->avatar_url }}" class="w-full h-full object-cover">
                                    </div>
                                 </div>
                                 @if($f && isset($fData['icon']))
                                    <div class="absolute 0 -right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg border border-yellow-100 text-xl animate-spin-slow-pause">
                                        {{ $fData['icon'] }}
                                    </div>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="w-full bg-white/70 backdrop-blur-xl rounded-2xl pt-16 pb-8 px-6 shadow-2xl border-t border-white/80 shadow-yellow-500/20 text-center relative overflow-hidden transition-all duration-300 group-hover:bg-white/90 group-hover:scale-[1.02]">
                                 <!-- Confetti Effect (CSS only simple) -->
                                 <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-yellow-400 via-orange-500 to-yellow-400"></div>

                                <h3 class="text-2xl font-black text-slate-800 truncate mb-1 relative z-10 bg-clip-text text-transparent bg-gradient-to-r from-amber-600 to-yellow-600">{{ $u->name }}</h3>

                                @if($ft)
                                    <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gradient-to-r {{ optional($ft)->rarity_color ?? 'from-yellow-100 to-amber-200' }} text-xs font-bold shadow-sm mb-4 relative z-10 border border-white/50">
                                        @if(isset($ftData['emoji']))<span>{{ $ftData['emoji'] }}</span>@endif
                                        <span>{{ optional($ft)->name ?? '' }}</span>
                                    </div>
                                @endif

                               <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl p-2.5 text-white shadow-lg shadow-indigo-500/20">
                                        <div class="text-[10px] opacity-80 font-bold uppercase tracking-wider">คะแนนรวม</div>
                                        <div class="text-xl font-black">{{ $score }}</div>
                                    </div>
                                    <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl p-2.5 text-white shadow-lg shadow-orange-500/20">
                                         <div class="text-[10px] opacity-80 font-bold uppercase tracking-wider">คะแนนประลอง</div>
                                        <div class="text-xl font-black">{{ $points }}</div>
                                    </div>
                               </div>
                            </div>
                        </div>
                    </div>
                @endif

                 <!-- 3rd Place -->
                 @if(isset($top3[2]))
                    @php
                        $u = $top3[2];
                        $f = $u->equippedFrame;
                        $ft = $u->equippedTitle;
                        $ftData = optional($ft)->data ?? [];
                        $fData = optional($f)->data ?? [];
                        $fGrad = $fData['gradient'] ?? 'from-orange-700 to-amber-800';
                        $score = number_format($u->typing_submissions_sum_score ?? 0);
                        $points = number_format($u->points ?? 0);
                    @endphp
                    <div class="order-3 w-full max-w-xs md:w-1/3 md:translate-y-0 z-10 group" x-data="{ hover: false }">
                        <div class="relative flex flex-col items-center">
                            <div class="absolute -top-12 opacity-80 animate-bounce-slow animation-delay-2000 transition-all duration-300 group-hover:-translate-y-2">
                                <div class="w-10 h-10 bg-orange-100 text-orange-800 rounded-full flex items-center justify-center font-bold text-xl shadow-lg border-2 border-white">3</div>
                            </div>

                            <!-- Avatar -->
                            <div class="relative mb-[-40px] z-20 transition-transform duration-500 group-hover:scale-105 group-hover:-translate-y-2">
                                 <div class="w-24 h-24 rounded-full p-[3px] bg-gradient-to-br {{ $fGrad }} shadow-[0_10px_40px_-10px_rgba(0,0,0,0.2)]">
                                    <div class="w-full h-full rounded-full border-[3px] border-white overflow-hidden bg-white">
                                        <img src="{{ $u->avatar_url }}" class="w-full h-full object-cover">
                                    </div>
                                 </div>
                                 @if($f && isset($fData['icon']))
                                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md border border-orange-50 text-sm animate-pulse-slow">
                                        {{ $fData['icon'] }}
                                    </div>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="w-full bg-white/60 backdrop-blur-md rounded-2xl pt-12 pb-6 px-4 shadow-xl border border-white/50 text-center relative overflow-hidden transition-all duration-300 group-hover:bg-white/80 group-hover:shadow-2xl group-hover:shadow-orange-200/50">
                                 <!-- Shine Effect -->
                                <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/40 to-transparent translate-x-[-150%] skew-x-[-20deg] group-hover:translate-x-[150%] transition-transform duration-1000 ease-in-out"></div>

                                <h3 class="text-xl font-bold text-slate-800 truncate mb-1 relative z-10">{{ $u->name }}</h3>

                                @if($ft)
                                    <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-gradient-to-r {{ optional($ft)->rarity_color ?? 'from-orange-100 to-amber-200' }} text-[10px] font-bold shadow-sm mb-3 relative z-10">
                                        @if(isset($ftData['emoji']))<span>{{ $ftData['emoji'] }}</span>@endif
                                        <span>{{ optional($ft)->name ?? '' }}</span>
                                    </div>
                                @endif

                               <div class="grid grid-cols-2 gap-2 mt-2">
                                    <div class="bg-indigo-50/50 rounded-lg p-2">
                                        <div class="text-[10px] text-indigo-400 font-semibold uppercase">คะแนนรวม</div>
                                        <div class="text-indigo-700 font-bold">{{ $score }}</div>
                                    </div>
                                    <div class="bg-amber-50/50 rounded-lg p-2">
                                         <div class="text-[10px] text-amber-500 font-semibold uppercase">คะแนนประลอง</div>
                                        <div class="text-amber-700 font-bold">{{ $points }}</div>
                                    </div>
                               </div>
                            </div>

                            <!-- Stand base -->
                             <div class="h-4 w-4/5 bg-gradient-to-b from-slate-200 to-transparent opacity-50 mx-auto blur-sm"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
    
    <!-- Table Container -->
    <div class="bg-white/80 backdrop-blur-2xl rounded-[2.5rem] shadow-2xl border border-white/60 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-500 opacity-50"></div>
        
        <!-- Table Header Info -->
        <div class="p-6 md:p-8 flex items-center justify-between border-b border-indigo-100/50 bg-gradient-to-b from-white/50 to-transparent">
             <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner">
                    <i class="fas fa-list-ol"></i>
                </div>
                <span>ตารางอันดับ</span>
            </h2>
             <div class="flex items-center gap-4 text-sm font-medium text-slate-500">
                <div class="hidden md:flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span> คะแนนรวม
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> คะแนนประลอง
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-xs uppercase tracking-wider font-bold">
                        <th class="py-5 pl-8 pr-4 w-24">อันดับ</th>
                        <th class="py-5 px-4">นักเรียน</th>
                        <th class="py-5 px-4 hidden md:table-cell">ห้องเรียน</th>
                        <th class="py-5 px-4 hidden md:table-cell w-1/4">ความคืบหน้า</th>
                        <th class="py-5 px-4 text-right">คะแนนรวม</th>
                        <th class="py-5 px-4 text-right">คะแนนประลอง</th>
                        <th class="py-5 pl-4 pr-8 hidden sm:table-cell text-center w-24">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $index => $student)
                        @php
                            $rank = ($students->currentPage() - 1) * $students->perPage() + $index + 1;
                            $isCurrentUser = $student->id === auth()->id();
                            $battlePoints = $student->points ?? 0;
                            $assignmentScore = $student->typing_submissions_sum_score ?? 0;
                            $submittedCount = $student->typing_submissions_count ?? 0;
                            $submittedPercent = $totalAssignments > 0 ? min(100, ($submittedCount / $totalAssignments) * 100) : 0;

                            // Row styling for top 3 in list (optional, but nice)
                            $rowClass = $isCurrentUser ? 'bg-indigo-50/60' : 'hover:bg-slate-50/80';
                            $medalColor = match ($rank) {
                                1 => 'text-yellow-500 bg-yellow-50 border-yellow-200',
                                2 => 'text-slate-400 bg-slate-50 border-slate-200',
                                3 => 'text-orange-500 bg-orange-50 border-orange-200',
                                default => 'text-slate-500 bg-slate-50 border-slate-200'
                            };
                        @endphp
                        <tr class="group transition-all duration-200 border-l-[3px] border-l-transparent {{ $isCurrentUser ? '!border-l-indigo-500 shadow-sm z-10 relative' : 'hover:border-l-indigo-300' }} {{ $rowClass }}">
                            <td class="py-4 pl-8 pr-4">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm border shadow-sm {{ $medalColor }}">
                                    {{ $rank }}
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-4">
                                    @php
                                        $studentFrame = $student->equippedFrame;
                                        $studentFrameData = optional($studentFrame)->data ?? [];
                                        $studentFrameGradient = $studentFrameData['gradient'] ?? null;
                                    @endphp
                                    <div class="relative group-hover:scale-110 transition-transform duration-300">
                                        @if($studentFrameGradient)
                                            <div class="w-11 h-11 rounded-full p-[2px] bg-gradient-to-br {{ $studentFrameGradient }}">
                                                <img src="{{ $student->avatar_url }}" class="w-full h-full rounded-full object-cover border border-white">
                                            </div>
                                        @else
                                            <img src="{{ $student->avatar_url }}" class="w-11 h-11 rounded-full object-cover border-2 border-slate-100 shadow-sm">
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $student->name }}</span>
                                            @if($student->equippedTitle)
                                                @php $tData = $student->equippedTitle->data ?? []; @endphp
                                                 <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                                    @if(isset($tData['emoji']))<span class="mr-1">{{ $tData['emoji'] }}</span>@endif
                                                    {{ $student->equippedTitle->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $student->student_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 hidden md:table-cell">
                                 <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600 shadow-sm">
                                    {{ $student->class_name ?? '-' }}
                                </div>
                            </td>
                            <td class="py-4 px-4 hidden md:table-cell">
                                <div class="w-full max-w-[140px]">
                                    <div class="flex justify-between text-[10px] text-slate-400 mb-1">
                                        <span>งานที่ส่ง</span>
                                        <span>{{ $submittedCount }}/{{ $totalAssignments }}</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $submittedPercent }}%"></div>
                                    </div>
                                </div>
                            </td>
                             <td class="py-4 px-4 text-right">
                                 <span class="font-black text-slate-800 text-lg group-hover:text-indigo-600 transition-colors">{{ number_format($assignmentScore) }}</span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                 <span class="font-bold text-amber-600 text-base bg-amber-50 px-2 py-1 rounded-md">{{ number_format($battlePoints) }}</span>
                            </td>
                            <td class="py-4 pl-4 pr-8 hidden sm:table-cell text-center">
                                @if($rank <= 3)
                                    <i class="fas fa-fire text-orange-500 animate-pulse drop-shadow-sm"></i>
                                @else
                                    <div class="w-2 h-2 rounded-full bg-slate-200 mx-auto"></div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                             <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center opacity-50">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-search text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">ไม่พบข้อมูลที่ค้นหา</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $students->links() }}
        </div>
    </div>

    <!-- User Floating Rank (Visible on Mobile/Sticky) -->
    @if(auth()->user()->role === 'student' && !collect($students->items())->contains('id', auth()->id()))
        <div class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] max-w-md bg-slate-900/90 backdrop-blur-md text-white p-4 rounded-2xl shadow-2xl z-50 flex items-center justify-between border border-white/10 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full border-2 border-indigo-500 bg-slate-800 overflow-hidden">
                     <img src="{{ auth()->user()->avatar_url }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <div class="text-sm font-bold">อันดับของคุณ</div>
                    <div class="text-xs text-slate-300">จากทั้งหมด {{ $totalStudents ?? 0 }} คน</div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-xl font-bold text-indigo-400">#{{ $userRank ?? '-' }}</div>
                <div class="text-[10px] text-slate-400">{{ number_format(auth()->user()->typing_submissions_sum_score ?? 0) }} คะแนน</div>
            </div>
        </div>
    @endif
    
</x-typing-app>
