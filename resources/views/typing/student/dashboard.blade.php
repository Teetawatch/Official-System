<x-typing-app :role="'student'" :title="'แดชบอร์ดนักเรียน - ระบบวิชาพิมพ์หนังสือราชการ 1'">

    <!-- Welcome Banner with Aurora Effect -->
    <div class="relative overflow-hidden bg-white border border-white/40 rounded-[2.5rem] p-8 mb-10 shadow-2xl group transition-all duration-500 hover:shadow-primary-500/20">
        <!-- Aurora Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-white via-indigo-50/50 to-blue-50/30 opacity-80"></div>
        
        <!-- Modern Mesh Gradients (Aurora) -->
        <div class="absolute top-[-30%] right-[-10%] w-[600px] h-[600px] bg-gradient-to-br from-orange-300/30 via-pink-300/30 to-rose-200/30 rounded-full blur-[80px] animate-pulse-slow pointer-events-none mix-blend-multiply"></div>
        <div class="absolute bottom-[-20%] left-[-10%] w-[500px] h-[500px] bg-gradient-to-tr from-blue-200/30 via-indigo-200/30 to-purple-200/30 rounded-full blur-[80px] animate-pulse-slow delay-1000 pointer-events-none mix-blend-multiply"></div>
        <div class="absolute top-[20%] left-[20%] w-[300px] h-[300px] bg-gradient-to-r from-teal-200/20 to-emerald-200/20 rounded-full blur-[60px] animate-pulse-slow delay-700 pointer-events-none"></div>

        <!-- Geometric Accents -->
        <svg class="absolute top-10 right-10 w-32 h-32 text-orange-400/20 rotate-12 animate-float" viewBox="0 0 100 100" fill="currentColor">
            <rect x="10" y="10" width="20" height="20" rx="4" />
            <rect x="60" y="20" width="30" height="30" rx="8" />
            <circle cx="80" cy="80" r="8" />
        </svg>

        @php
            $frameItem = $user->equipped_frame_item;
            $titleItem = $user->equipped_title_item;
            $frameGradient = $frameItem && isset($frameItem->data['gradient'])
                ? $frameItem->data['gradient']
                : 'from-blue-400 to-indigo-500';
        @endphp

        <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 md:gap-12">
            <!-- Avatar Section -->
            <div class="relative group cursor-pointer perspective-1000">
                <div class="absolute -inset-2 bg-gradient-to-r {{ $frameGradient }} rounded-full blur-lg opacity-40 group-hover:opacity-70 transition duration-500 will-change-transform"></div>
                <div class="relative transform group-hover:scale-105 transition-transform duration-500">
                    <div class="w-28 h-28 md:w-32 md:h-32 rounded-full bg-gradient-to-br {{ $frameGradient }} p-[3px] shadow-2xl ring-4 ring-white/50 backdrop-blur-sm">
                        <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-full h-full rounded-full object-cover border-[3px] border-white bg-white shadow-inner">
                    </div>
                    
                    @if($frameItem && isset($frameItem->data['icon']))
                        <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg border border-gray-100 transform group-hover:rotate-12 transition-transform duration-300">
                            <span class="text-xl">{{ $frameItem->data['icon'] }}</span>
                        </div>
                    @endif
                    
                    <div class="absolute bottom-1 left-1 bg-white p-1.5 rounded-full shadow-md">
                        <div class="w-4 h-4 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full border-2 border-white animate-pulse"></div>
                    </div>
                </div>

                {{-- Title Badge --}}
                @if($titleItem)
                    <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-max max-w-[150px]">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gradient-to-r {{ $titleItem->rarity_color }} text-white text-xs font-bold shadow-lg ring-2 ring-white transform group-hover:-translate-y-1 transition-transform duration-300">
                            @if(isset($titleItem->data['emoji']))<span>{{ $titleItem->data['emoji'] }}</span>@endif
                            <span class="truncate">{{ $titleItem->name }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Content Section -->
            <div class="flex-1 text-center md:text-left">
                <div class="space-y-2 mb-6">
                    @php
                        $nameColorItem = $user->equipped_name_color_item;
                        $nameColorClass = $nameColorItem ? ($nameColorItem->data['class'] ?? '') : 'text-gray-800';
                    @endphp
                    <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-2 drop-shadow-sm">
                        <span class="text-gray-400 text-2xl md:text-3xl font-medium block md:inline mb-1 md:mb-0">สวัสดี,</span>
                        <span class="{{ $nameColorClass }} bg-clip-text text-transparent bg-gradient-to-r from-gray-900 via-gray-700 to-gray-800">{{ $user->name }}</span>
                    </h1>
                    
                    <p class="text-gray-500 text-lg md:text-xl font-medium leading-relaxed max-w-2xl mx-auto md:mx-0">
                        "ความพยายามในวันนี้ คือความสำเร็จในวันข้างหน้า"
                    </p>
                    <p class="text-sm text-indigo-500/80 font-medium tracking-wide shimmer-text">
                        ✨ มาฝึกพิมพ์วันละนิด เพื่ออนาคตที่สดใสกันเถอะ!
                    </p>
                </div>

                <!-- Bento-style Stats Badges -->
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <!-- Student ID -->
                    <div class="group/badge flex items-center gap-3 pr-5 pl-2 py-2 rounded-full bg-white/60 border border-white/60 shadow-sm hover:shadow-md hover:bg-white transition-all duration-300 backdrop-blur-md">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner group-hover/badge:scale-110 transition-transform">
                            <i class="fas fa-id-card-clip"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">รหัสนักเรียน</p>
                            <p class="text-sm font-bold text-gray-800 font-mono">{{ $user->student_id ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Class -->
                    <div class="group/badge flex items-center gap-3 pr-5 pl-2 py-2 rounded-full bg-white/60 border border-white/60 shadow-sm hover:shadow-md hover:bg-white transition-all duration-300 backdrop-blur-md">
                        <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shadow-inner group-hover/badge:scale-110 transition-transform">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">ชั้นเรียน</p>
                            <p class="text-sm font-bold text-gray-800">{{ $user->class_name ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Points (Premium Action) -->
                    <a href="{{ route('typing.shop.index') }}" class="group/badge relative flex items-center gap-3 pr-5 pl-2 py-2 rounded-full bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 shadow-sm hover:shadow-amber-200/50 hover:-translate-y-0.5 transition-all duration-300 cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-400/0 via-amber-400/10 to-amber-400/0 translate-x-[-100%] group-hover/badge:translate-x-[100%] transition-transform duration-1000"></div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shadow-lg group-hover/badge:scale-110 group-hover/badge:rotate-12 transition-transform">
                            <i class="fas fa-coins text-sm"></i>
                        </div>
                        <div class="text-left z-10">
                            <p class="text-[10px] text-amber-600 uppercase tracking-wider font-bold">คะแนนสะสม</p>
                            <p class="text-sm font-black text-amber-700 font-mono">{{ number_format($user->points ?? 0) }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-[10px] text-amber-400 ml-1 opacity-0 -translate-x-2 group-hover/badge:opacity-100 group-hover/badge:translate-x-0 transition-all"></i>
                    </a>
                </div>
            </div>

            <!-- Action Area -->
            <div class="mt-8 md:mt-0 flex flex-col gap-4 w-full md:w-auto min-w-[200px]">
                <a href="{{ route('typing.profile') }}" class="relative group w-full overflow-hidden rounded-2xl p-[1px]">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-200 to-gray-300 group-hover:from-blue-400 group-hover:to-indigo-500 transition-colors duration-500"></div>
                    <div class="relative flex items-center gap-3 px-5 py-3.5 bg-white rounded-2xl group-hover:bg-white/95 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">โปรไฟล์</p>
                            <p class="text-sm font-bold text-gray-800">แก้ไขข้อมูล</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-300 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </a>

                <a href="{{ route('typing.shop.my-rewards') }}" class="group relative w-full flex items-center gap-4 px-5 py-4 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-2xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150 mix-blend-overlay"></div>
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                    
                    <div class="relative w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/10 group-hover:rotate-12 transition-transform">
                        <i class="fas fa-gift text-lg"></i>
                    </div>
                    <div class="relative flex-1">
                        <p class="text-xs text-indigo-100 font-medium opacity-90">ของสะสม</p>
                        <p class="text-base font-bold">รางวัลของฉัน</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Bento Grid Stats - Premium Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6 mb-10">
        <!-- Card 1: Total Score -->
        <div class="group relative bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 hover:border-blue-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full -mr-10 -mt-10 opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative flex flex-col h-full justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-2xl text-blue-600 group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-star text-xl"></i>
                    </div>
                    @php $scorePercent = $submissions->count() > 0 ? min(100, ($avgScore ?? 0)) : 0; @endphp
                    <div class="flex items-center gap-1 text-xs font-bold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">
                        <span>รวม</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-gray-800 tracking-tight group-hover:text-blue-600 transition-colors">{{ number_format($totalScore, 0) }}</h3>
                    <p class="text-sm text-gray-500 font-medium mt-1">คะแนนรวมทั้งหมด</p>
                </div>
                <div class="mt-4 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-1000" style="width: {{ $scorePercent }}%"></div>
                </div>
            </div>
        </div>

        <!-- Card 2: Rank -->
        <div class="group relative bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 hover:border-amber-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-amber-50 to-transparent rounded-bl-full -mr-10 -mt-10 opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative flex flex-col h-full justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-amber-50 rounded-2xl text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-trophy text-xl"></i>
                    </div>
                    <div class="flex items-center gap-1 text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-100">
                        @if($userRank <= 3) Top 3! @else Top 10 @endif
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-3xl font-black text-gray-800 tracking-tight group-hover:text-amber-600 transition-colors">#{{ $userRank }}</h3>
                        <span class="text-sm font-bold text-gray-400">/ {{ $totalStudents }}</span>
                    </div>
                    <p class="text-sm text-gray-500 font-medium mt-1">อันดับปัจจุบัน</p>
                </div>
                <div class="mt-4 pt-4 border-t border-dashed border-gray-100">
                    <p class="text-xs text-gray-400">
                        <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                        แข่งกับเพื่อนในห้อง
                    </p>
                </div>
            </div>
        </div>

        <!-- Card 3: Tasks -->
        <div class="group relative bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 hover:border-emerald-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-50 to-transparent rounded-bl-full -mr-10 -mt-10 opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative flex flex-col h-full justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    @php $remaining = max(0, $totalAssignments - $submittedCount); @endphp
                    <div class="flex items-center gap-1 text-xs font-bold {{ $remaining == 0 ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-gray-400 bg-gray-50 border-gray-100' }} px-2.5 py-1 rounded-full border">
                        <span>{{ $remaining == 0 ? 'ครบแล้ว' : 'เหลือ ' . $remaining }}</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline gap-1.5">
                        <h3 class="text-3xl font-black text-gray-800 tracking-tight group-hover:text-emerald-600 transition-colors">{{ $submittedCount }}</h3>
                        <span class="text-sm font-bold text-gray-400">/ {{ $totalAssignments }}</span>
                    </div>
                    <p class="text-sm text-gray-500 font-medium mt-1">งานที่ส่งแล้ว</p>
                </div>
                <!-- Circular Progress Mini -->
                <div class="mt-4 pt-4 border-t border-dashed border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-400">ความคืบหน้า</p>
                    <span class="text-xs font-bold text-gray-700">{{ $totalAssignments > 0 ? round(($submittedCount / $totalAssignments) * 100) : 0 }}%</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Avg Score -->
        <div class="group relative bg-white rounded-3xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 hover:border-rose-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-rose-50 to-transparent rounded-bl-full -mr-10 -mt-10 opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative flex flex-col h-full justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-rose-50 rounded-2xl text-rose-600 group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <div class="flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full border border-rose-100">
                        <i class="fas fa-fire"></i> Performance
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-gray-800 tracking-tight group-hover:text-rose-600 transition-colors">{{ number_format($avgScore, 1) }}%</h3>
                    <p class="text-sm text-gray-500 font-medium mt-1">คะแนนเฉลี่ย</p>
                </div>
                <div class="mt-4 pt-4 border-t border-dashed border-gray-100">
                    <p class="text-xs {{ $avgScore >= 80 ? 'text-emerald-500 font-bold' : ($avgScore >= 60 ? 'text-amber-500' : 'text-rose-500') }}">
                         @if($avgScore >= 80) ยอดเยี่ยม! 🌟 @elseif($avgScore >= 60) ทำได้ดี! 👍 @else ต้องพยายามเพิ่มนะ 💪 @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Split -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-10">

        <!-- Column 1 & 2: Pending Tasks -->
        <div class="xl:col-span-2 space-y-8">
            <!-- Header -->
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                        <i class="fas fa-keyboard"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">บทเรียนและแบบฝึกหัดพิมพ์</h2>
                </div>
            </div>

            <!-- Lessons Grid -->
            <div class="space-y-8 pb-4">
                @forelse($lessons->groupBy('chapter') as $chapter => $chapterAssignments)
                    @php $chapterTitle = $chapter ?: 'บทเรียนทั่วไป'; @endphp
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <h3 class="text-sm font-black text-gray-800 bg-gray-100 px-4 py-1.5 rounded-full shadow-sm">{{ $chapterTitle }}</h3>
                            <div class="h-px flex-1 bg-gray-100"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($chapterAssignments as $assignment)
                                @php
                                    $submission = $assignment->submissions->first();
                                    $isCompleted = $submission && $submission->score !== null;
                                    $isPending = !$submission;
                                    $isUrgent = $isPending && $assignment->due_date && $assignment->due_date->isFuture() && $assignment->due_date->diffInDays(now()) <= 2;
                                @endphp
                                <div class="group relative bg-white border {{ $isCompleted ? 'border-emerald-100' : 'border-gray-100' }} rounded-2xl p-5 shadow-sm hover:shadow-lg hover:border-indigo-100 transition-all duration-300 overflow-hidden">
                                    @if($isCompleted)
                                        <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden pointer-events-none">
                                            <div class="absolute top-3 -right-6 bg-emerald-500 text-white text-[9px] font-bold uppercase tracking-wider py-1 px-8 rotate-45 shadow-sm">
                                                สำเร็จ
                                            </div>
                                        </div>
                                    @elseif($isUrgent)
                                        <div class="absolute -top-3 -right-3">
                                            <span class="relative flex h-6 w-6">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-6 w-6 bg-red-500 text-white text-[10px] items-center justify-center shadow-md">
                                                  <i class="fas fa-exclamation"></i>
                                              </span>
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-start gap-4">
                                        <div class="relative flex-shrink-0">
                                            <div class="w-12 h-12 rounded-xl {{ $isCompleted ? 'bg-emerald-50 text-emerald-500' : ($isUrgent ? 'bg-red-50 text-red-500' : 'bg-indigo-50 text-indigo-500') }} flex items-center justify-center text-xl group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300 shadow-inner">
                                                <i class="fas {{ $isCompleted ? 'fa-check' : ($isUrgent ? 'fa-fire' : 'fa-keyboard') }}"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0 pr-2">
                                            <h3 class="font-bold text-gray-800 line-clamp-1 group-hover:text-indigo-600 transition-colors" title="{{ $assignment->title }}">{{ $assignment->title }}</h3>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs font-medium px-2 py-0.5 rounded-md bg-gray-50 text-gray-500 border border-gray-100">
                                                    คะแนนเต็ม: {{ $assignment->max_score }}
                                                </span>
                                                @if($isCompleted)
                                                    <span class="text-xs font-black text-emerald-600">ได้: {{ $submission->score }}</span>
                                                @elseif($isUrgent)
                                                    <span class="text-xs font-bold text-red-500">ด่วน!</span>
                                                @endif
                                            </div>
                                            
                                            <div class="mt-4 flex items-center justify-between">
                                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                                    @if($isCompleted)
                                                        <i class="fas fa-check-double text-emerald-400 mr-1"></i> ผ่านแล้ว
                                                    @elseif($assignment->due_date)
                                                        <i class="far fa-clock mr-1"></i> หมดเขต: {{ $assignment->due_date->format('d/m') }}
                                                    @else
                                                        <span><i class="fas fa-infinity mr-1"></i> ไม่มีกำหนด</span>
                                                    @endif
                                                </div>
                                                
                                                <a href="{{ $assignment->submission_type === 'file' ? route('typing.student.upload', $assignment->id) : route('typing.student.practice', $assignment->id) }}" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $isCompleted ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-200' }} text-xs font-bold rounded-lg transition-colors relative z-10">
                                                    @if($isCompleted)
                                                        <i class="fas fa-redo text-[10px]"></i> ทำอีกครั้ง
                                                    @else
                                                        <i class="fas {{ $assignment->submission_type === 'file' ? 'fa-upload' : 'fa-play' }} text-[10px]"></i> เริ่ม!
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 bg-white/50 border border-dashed border-gray-200 rounded-3xl">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-box-open text-2xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-500 font-medium">ยังไม่มีบทเรียนในขณะนี้</p>
                    </div>
                @endforelse
            </div>

            <!-- Progress Chart Section -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                            <i class="fas fa-chart-area"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">พัฒนาการการพิมพ์</h2>
                    </div>
                    <select class="text-xs font-bold text-gray-500 bg-gray-50 border-none rounded-lg focus:ring-0 cursor-pointer hover:bg-gray-100">
                        <option>10 ครั้งล่าสุด</option>
                    </select>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Column 3: Recent & Leaderboard -->
        <div class="space-y-8">
            
            <!-- Recent Scores Widget -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-history text-gray-400"></i> คะแนนล่าสุด
                    </h2>
                    <a href="{{ route('typing.student.grades') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600">ดูทั้งหมด</a>
                </div>
                
                <div class="relative pl-4 space-y-6 before:absolute before:inset-y-0 before:left-[11px] before:w-[2px] before:bg-gray-100">
                    @forelse($submissions->take(5) as $submission)
                        <div class="relative group">
                            <div class="absolute -left-[21px] mt-1.5 h-3 w-3 rounded-full border-2 border-white {{ $submission->score >= ($submission->assignment->max_score * 0.8) ? 'bg-green-500 shadow-green-200' : 'bg-gray-300' }} shadow-sm z-10"></div>
                            <div class="flex items-start justify-between group-hover:translate-x-1 transition-transform duration-300">
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800 line-clamp-1" title="{{ $submission->assignment->title }}">{{ $submission->assignment->title }}</h4>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $submission->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="text-sm font-black {{ $submission->score >= ($submission->assignment->max_score * 0.8) ? 'text-green-500' : 'text-gray-600' }}">
                                    {{ $submission->score }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic">ยังไม่มีการส่งงาน</p>
                    @endforelse
                </div>
            </div>

            <!-- Mini Leaderboard -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-6 shadow-xl text-white relative overflow-hidden">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
                
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="fas fa-crown text-amber-400"></i> Top 5 ผู้นำ
                    </h2>
                    <a href="{{ route('typing.leaderboard') }}" class="text-xs font-bold text-gray-400 hover:text-white transition-colors">ดูทั้งหมด</a>
                </div>

                <div class="space-y-4 relative z-10">
                    @forelse($leaderboard->take(5) as $index => $leader)
                        @php $rank = $index + 1; @endphp
                        <div class="flex items-center gap-3 p-2 rounded-xl {{ $leader->id === $user->id ? 'bg-white/10 border border-white/20' : 'hover:bg-white/5' }} transition-colors">
                            <div class="flex-shrink-0 w-6 text-center font-bold {{ $rank == 1 ? 'text-amber-400' : ($rank == 2 ? 'text-gray-300' : ($rank == 3 ? 'text-amber-600' : 'text-gray-500')) }}">
                                {{ $rank }}
                            </div>
                            <div class="w-8 h-8 rounded-full bg-gray-700 p-0.5 overflow-hidden">
                                <img src="{{ $leader->avatar_url }}" alt="" class="w-full h-full rounded-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold truncate text-gray-200">{{ $leader->name }}</p>
                            </div>
                            <div class="font-mono text-xs font-bold text-indigo-300">
                                {{ number_format($leader->typing_submissions_sum_score) }}
                            </div>
                            @if($rank == 1) <i class="fas fa-crown text-amber-400 text-xs"></i> @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">ยังไม่มีข้อมูล</p>
                    @endforelse
                </div>
                
                @if($userRank > 5)
                    <div class="mt-4 pt-4 border-t border-white/10 text-center">
                        <p class="text-xs text-gray-400">อันดับของคุณ: <span class="text-white font-bold">#{{ $userRank }}</span></p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Chart Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('progressChart').getContext('2d');
            const chartData = @json($chartData); // Expecting { labels: [], accuracy: [], wpm: [] }

            // Gradient for Line 1
            let gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
            gradient1.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
            gradient1.addColorStop(1, 'rgba(16, 185, 129, 0)');

            // Gradient for Line 2
            let gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
            gradient2.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
            gradient2.addColorStop(1, 'rgba(59, 130, 246, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'ความแม่นยำ (%)',
                            data: chartData.accuracy,
                            borderColor: '#10b981', // Emerald 500
                            backgroundColor: gradient1,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y'
                        },
                        {
                            label: 'ความเร็ว (WPM)',
                            data: chartData.wpm,
                            borderColor: '#6366f1', // Indigo 500
                            backgroundColor: gradient2,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#6366f1',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { 
                            position: 'top', 
                            align: 'end',
                            labels: { usePointStyle: true, boxWidth: 8, font: { family: "'Kanit', sans-serif" } } 
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            titleFont: { family: "'Kanit', sans-serif", size: 13, weight: 'bold' },
                            bodyFont: { family: "'Kanit', sans-serif", size: 12 },
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true,
                            boxPadding: 4
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: "'Kanit', sans-serif" }, color: '#9ca3af' }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: { display: false },
                            min: 0,
                            max: 100,
                            grid: { borderDash: [4, 4], color: '#f3f4f6' },
                            ticks: { font: { family: "'Kanit', sans-serif" }, color: '#9ca3af' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { display: false },
                            ticks: { font: { family: "'Kanit', sans-serif" }, color: '#9ca3af' }
                        },
                    }
                }
            });
        });
    </script>

    <!-- Styles for specific animations -->
    <style>
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px) rotate(12deg); }
            50% { transform: translateY(-10px) rotate(12deg); }
            100% { transform: translateY(0px) rotate(12deg); }
        }
        .shimmer-text {
            background: linear-gradient(to right, #6366f1 0%, #818cf8 50%, #6366f1 100%);
            background-size: 200% auto;
            color: transparent;
            background-clip: text;
            -webkit-background-clip: text;
            animation: shimmer 3s linear infinite;
        }
        @keyframes shimmer {
            to { background-position: 200% center; }
        }
    </style>

</x-typing-app>