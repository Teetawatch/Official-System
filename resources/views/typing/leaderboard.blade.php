<x-typing-app :role="auth()->user()->role" :title="'บอร์ดผู้นำ - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white border border-gray-100 rounded-3xl p-8 mb-12 text-center shadow-xl group hover:shadow-2xl transition-all duration-500">
        <!-- Decorative Background Elements -->
        <div class="absolute top-[-20%] left-[-5%] w-[500px] h-[500px] bg-gradient-to-br from-yellow-200/40 via-orange-100/40 to-red-100/40 rounded-full blur-3xl animate-pulse-slow pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[400px] h-[400px] bg-gradient-to-tr from-purple-200/40 via-blue-100/40 to-indigo-100/40 rounded-full blur-3xl animate-pulse-slow delay-1000 pointer-events-none"></div>
        
        <div class="relative z-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-600 rounded-2xl shadow-lg shadow-orange-500/20 mb-6 transform rotate-3 hover:rotate-6 transition-transform duration-300">
                <i class="fas fa-trophy text-white text-4xl drop-shadow-md"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 via-gray-700 to-gray-800 drop-shadow-sm mb-3">
                บอร์ดผู้นำ
            </h1>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">
                ทำเนียบสุดยอดนักพิมพ์หนังสือราชการ รวมพลคนเก่งที่มีความเร็วและความแม่นยำสูงสุด
            </p>
        </div>
    </div>
    
    @if($students->isNotEmpty())
    <!-- Top 3 Podium (Premium Style) -->
    <div class="relative mb-16 px-4">
        <div class="flex items-end justify-center perspective-1000">
            <!-- 2nd Place -->
            @if(isset($top3[1]))
            <div class="relative z-10 -mr-4 md:-mr-8 group cursor-pointer order-1">
                <div class="flex flex-col items-center transition-transform transform group-hover:-translate-y-2 duration-300">
                    <div class="relative mb-3">
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full p-1 bg-gradient-to-b from-gray-300 to-gray-500 shadow-xl">
                            <img src="{{ $top3[1]->avatar_url }}" class="w-full h-full rounded-full object-cover border-4 border-white">
                        </div>
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-gray-600 text-white text-xs font-bold px-3 py-1 rounded-full border-2 border-white shadow-md">
                            #2
                        </div>
                    </div>
                    <div class="w-28 md:w-36 bg-gradient-to-b from-gray-300 to-gray-400 rounded-t-xl p-4 text-center text-white shadow-lg h-32 md:h-40 flex flex-col justify-start pt-8 relative overflow-hidden backdrop-blur-sm">
                        <div class="absolute inset-0 bg-white/20 skew-y-12 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <p class="font-bold text-sm md:text-base truncate relative z-10">{{ $top3[1]->name }}</p>
                        <p class="text-xs opacity-90 relative z-10 mt-1">{{ number_format($top3[1]->typing_submissions_sum_score ?? 0) }} คะแนน</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- 1st Place -->
            @if(isset($top3[0]))
            <div class="relative z-20 group cursor-pointer order-2 -mt-12">
                <div class="flex flex-col items-center transition-transform transform group-hover:-translate-y-2 duration-300">
                    <div class="relative mb-4">
                        <i class="fas fa-crown text-4xl text-yellow-400 absolute -top-10 left-1/2 -translate-x-1/2 drop-shadow-lg animate-bounce-slow"></i>
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full p-1 bg-gradient-to-b from-yellow-300 to-amber-500 shadow-2xl shadow-amber-500/30">
                            <img src="{{ $top3[0]->avatar_url }}" class="w-full h-full rounded-full object-cover border-4 border-white">
                        </div>
                        <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-yellow-500 to-amber-600 text-white text-sm font-bold px-4 py-1 rounded-full border-2 border-white shadow-lg">
                            #1
                        </div>
                    </div>
                    <div class="w-32 md:w-44 bg-gradient-to-b from-yellow-400 to-amber-500 rounded-t-2xl p-4 text-center text-white shadow-xl h-44 md:h-52 flex flex-col justify-start pt-10 relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 skew-y-12 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative z-10">
                            <p class="font-bold text-base md:text-lg truncate">{{ $top3[0]->name }}</p>
                            <div class="inline-block bg-white/20 rounded-lg px-2 py-1 mt-2">
                                <p class="text-sm font-bold flex items-center gap-1">
                                    <i class="fas fa-bolt text-yellow-200"></i>
                                    {{ number_format($top3[0]->typing_submissions_sum_score ?? 0) }}
                                </p>
                            </div>
                            <p class="text-[10px] opacity-80 mt-1">คะแนนรวมสูงสุด</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 3rd Place -->
            @if(isset($top3[2]))
            <div class="relative z-10 -ml-4 md:-ml-8 group cursor-pointer order-3">
                <div class="flex flex-col items-center transition-transform transform group-hover:-translate-y-2 duration-300">
                    <div class="relative mb-3">
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full p-1 bg-gradient-to-b from-amber-600 to-orange-700 shadow-xl">
                            <img src="{{ $top3[2]->avatar_url }}" class="w-full h-full rounded-full object-cover border-4 border-white">
                        </div>
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-amber-700 text-white text-xs font-bold px-3 py-1 rounded-full border-2 border-white shadow-md">
                            #3
                        </div>
                    </div>
                    <div class="w-28 md:w-36 bg-gradient-to-b from-amber-600 to-orange-700 rounded-t-xl p-4 text-center text-white shadow-lg h-28 md:h-36 flex flex-col justify-start pt-8 relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 skew-y-12 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <p class="font-bold text-sm md:text-base truncate relative z-10">{{ $top3[2]->name }}</p>
                        <p class="text-xs opacity-90 relative z-10 mt-1">{{ number_format($top3[2]->typing_submissions_sum_score ?? 0) }} คะแนน</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- Floor Shadow -->
        <div class="w-3/4 h-4 bg-black/5 blur-xl rounded-[100%] mx-auto -mt-4"></div>
    </div>
    @endif
    
    <!-- Full Leaderboard Card -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Card Header -->
        <div class="p-6 md:p-8 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gray-50/50">
            <div>
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">
                        <i class="fas fa-list-ol"></i>
                    </span>
                    อันดับทั้งหมด
                </h2>
                <p class="text-sm text-gray-500 mt-1 ml-10">รายชื่อนักเรียนเรียงตามคะแนนรวมจากทุกบทเรียน</p>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <form method="GET" action="{{ route('typing.leaderboard') }}" class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <input 
                            type="text" 
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="ค้นหานักเรียน..." 
                            class="w-full md:w-64 pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                        >
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select name="class" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none bg-white cursor-pointer transition-all hover:border-blue-300">
                        <option value="all" {{ ($classFilter ?? '') === 'all' || !($classFilter ?? '') ? 'selected' : '' }}>ทุกห้องเรียน</option>
                        @foreach($classes ?? [] as $class)
                            <option value="{{ $class }}" {{ ($classFilter ?? '') === $class ? 'selected' : '' }}>{{ $class }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-4 pl-8 pr-4 w-24"># อันดับ</th>
                        <th class="py-4 px-4">ข้อมูลนักเรียน</th>
                        <th class="py-4 px-4 hidden md:table-cell">ห้องเรียน</th>
                        <th class="py-4 px-4 hidden md:table-cell w-1/4">ความคืบหน้า</th>
                        <th class="py-4 px-4 text-right">คะแนนรวม</th>
                        <th class="py-4 pl-4 pr-8 hidden sm:table-cell text-center w-24">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($students as $index => $student)
                    @php
                        $rank = ($students->currentPage() - 1) * $students->perPage() + $index + 1;
                        $isCurrentUser = $student->id === $user->id;
                        $score = $student->typing_submissions_sum_score ?? 0;
                        $submittedCount = $student->typing_submissions_count ?? 0;
                        $submittedPercent = $totalAssignments > 0 ? min(100, ($submittedCount / $totalAssignments) * 100) : 0;
                    @endphp
                    <tr class="group hover:bg-blue-50/50 transition-colors {{ $isCurrentUser ? 'bg-blue-50/30' : '' }}">
                        <td class="py-4 pl-8 pr-4">
                            @if($rank === 1)
                                <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center shadow-sm font-bold border border-yellow-200">
                                    <i class="fas fa-crown text-xs"></i>
                                </div>
                            @elseif($rank === 2)
                                <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center shadow-sm font-bold border border-gray-200">
                                    2
                                </div>
                            @elseif($rank === 3)
                                <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center shadow-sm font-bold border border-orange-200">
                                    3
                                </div>
                            @else
                                <span class="font-medium text-gray-500 ml-2 group-hover:text-blue-600 transition-colors">{{ $rank }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <img 
                                        src="{{ $student->avatar_url }}" 
                                        alt="" 
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm group-hover:scale-110 transition-transform duration-300"
                                    >
                                    @if($rank <= 3)
                                        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full border border-white flex items-center justify-center text-[8px] text-white {{ $rank === 1 ? 'bg-yellow-400' : ($rank === 2 ? 'bg-gray-400' : 'bg-orange-500') }}">
                                            <i class="fas fa-star"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 flex items-center gap-2 group-hover:text-blue-600 transition-colors">
                                        {{ $student->name }}
                                        @if($isCurrentUser)
                                            <span class="bg-blue-100 text-blue-600 text-[10px] px-2 py-0.5 rounded-full border border-blue-200">คุณ</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400">ID: {{ $student->student_id ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 hidden md:table-cell">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 group-hover:bg-white border border-transparent group-hover:border-gray-200 transition-all">
                                {{ $student->class_name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 hidden md:table-cell">
                           <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                     <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full" style="width: {{ $submittedPercent }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-500 w-12 text-right">{{ $submittedCount }}/{{ $totalAssignments }}</span>
                           </div>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <span class="text-lg font-bold {{ $rank <= 3 ? 'text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600' : 'text-gray-700' }}">
                                {{ number_format($score) }}
                            </span>
                            <span class="text-xs text-gray-400 block -mt-1">คะแนน</span>
                        </td>
                        <td class="py-4 pl-4 pr-8 hidden sm:table-cell text-center">
                            @if($rank <= 3)
                                <i class="fas fa-fire text-orange-500 animate-pulse"></i>
                            @else
                                <i class="fas fa-minus text-gray-200"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-users-slash text-4xl text-gray-300 mb-3"></i>
                                <p>ยังไม่มีข้อมูลในระบบ</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $students->links() }}
        </div>
    </div>
    
</x-typing-app>
