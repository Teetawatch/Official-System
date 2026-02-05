<x-typing-app :role="'admin'" :title="'ตารางคะแนน - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    <div class="space-y-10 pb-12">

        <!-- Aurora & Glass Header -->
        <div class="relative overflow-hidden bg-white border border-white/40 rounded-[2.5rem] p-8 shadow-2xl group transition-all duration-500 hover:shadow-primary-500/10">
            <!-- Aurora Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-white via-blue-50/50 to-indigo-50/30 opacity-80"></div>
            <div class="absolute top-[-30%] right-[-10%] w-[600px] h-[600px] bg-gradient-to-br from-blue-300/10 via-primary-300/10 to-indigo-200/10 rounded-full blur-[80px] animate-pulse-slow pointer-events-none mix-blend-multiply"></div>
            <div class="absolute bottom-[-20%] left-[-10%] w-[500px] h-[500px] bg-gradient-to-tr from-indigo-200/10 via-blue-200/10 to-purple-200/10 rounded-full blur-[80px] animate-pulse-slow delay-1000 pointer-events-none mix-blend-multiply"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-primary-500/30 transform group-hover:rotate-6 transition-transform">
                        <i class="fas fa-chart-bar text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tight">ตารางคะแนน</h1>
                        <p class="text-gray-500 mt-1 font-medium flex items-center gap-2 text-lg">
                            <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                            ดูและจัดการคะแนนนักเรียนทั้งหมดในระบบ
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('typing.admin.grades.export.csv') }}" 
                        class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-white/60 backdrop-blur-md border border-white text-emerald-600 font-black hover:bg-emerald-50 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        <i class="fas fa-file-excel"></i>
                        ส่งออก Excel
                    </a>
                    <a href="{{ route('typing.admin.grades.export.pdf') }}" target="_blank"
                        class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-gray-900 text-white font-black hover:bg-black hover:shadow-xl hover:-translate-y-0.5 transition-all shadow-lg shadow-gray-200">
                        <i class="fas fa-print text-amber-400"></i>
                        พิมพ์รายงาน
                    </a>
                </div>
            </div>
        </div>

        <!-- Bento Grid Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-primary-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-primary-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-primary-500 group-hover:text-white transition-colors">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">{{ $totalStudents }}</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">นักเรียน</p>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-indigo-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-indigo-600 tracking-tight">{{ number_format($averageScore, 1) }}</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">เฉลี่ยรวม</p>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-emerald-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-emerald-600 tracking-tight">{{ number_format($maxScore, 1) }}</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">สูงสุด</p>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-red-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-red-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-red-500 group-hover:text-white transition-colors">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-red-600 tracking-tight">{{ number_format($minScore, 1) }}</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">ต่ำสุด</p>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-amber-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-amber-500 group-hover:text-white transition-colors">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-amber-600 tracking-tight">{{ $passingRate }}%</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">อัตราการผ่าน</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters (Modern Card) -->
        <form action="{{ route('typing.admin.grades') }}" method="GET" class="bg-white rounded-[2rem] p-4 shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-6 relative group">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ค้นหาชื่อ หรือ รหัสนักเรียน..." 
                        class="w-full pl-12 pr-4 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="md:col-span-3">
                    <select name="class" class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all appearance-none cursor-pointer" onchange="this.form.submit()">
                        <option value="">ทุกห้องเรียน</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <select name="sort" class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all appearance-none cursor-pointer" onchange="this.form.submit()">
                        <option value="total_desc" {{ request('sort') == 'total_desc' ? 'selected' : '' }}>คะแนนรวม (มาก-น้อย)</option>
                        <option value="total_asc" {{ request('sort') == 'total_asc' ? 'selected' : '' }}>คะแนนรวม (น้อย-มาก)</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>ชื่อ ก-ฮ</option>
                        <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>รหัสนักเรียน</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <button type="submit" class="w-full h-full rounded-2xl bg-primary-500 text-white hover:bg-primary-600 transition-colors shadow-lg shadow-primary-500/20">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Grade Table Card -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100 overflow-hidden relative">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center">
                        <i class="fas fa-list-ol"></i>
                    </div>
                    <h2 class="text-xl font-black text-gray-800">Ranking & Score Board</h2>
                </div>
            </div>

            <div class="overflow-x-auto -mx-8">
                <table class="w-full text-left border-separate border-spacing-y-4 px-8">
                    <thead>
                        <tr class="text-gray-400 text-[10px] font-black uppercase tracking-widest">
                            <th class="pb-2 text-center w-16">Rank</th>
                            <th class="pb-2 px-6">นักเรียน</th>
                            @foreach($assignments as $assignment)
                                <th class="pb-2 text-center font-bold">{{ Str::limit($assignment->title, 10) }}</th>
                            @endforeach
                            <th class="pb-2 text-center bg-gray-50/50 rounded-t-xl">Total</th>
                            <th class="pb-2 text-center bg-gray-50/50 rounded-t-xl">Average</th>
                            <th class="pb-2 text-center bg-gray-50/50 rounded-t-xl">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                            @php
                                $rank = ($students->currentPage() - 1) * $students->perPage() + $index + 1;
                                $totalScore = $student->typingSubmissions->sum('score');
                                $avgScore = $student->typingSubmissions->count() > 0 ? $student->typingSubmissions->avg('score') : 0;
                                
                                // Grade Logic (simplified for display)
                                $grade = '0';
                                if ($totalScore >= 80) $grade = '4';
                                elseif ($totalScore >= 75) $grade = '3.5';
                                elseif ($totalScore >= 70) $grade = '3';
                                elseif ($totalScore >= 65) $grade = '2.5';
                                elseif ($totalScore >= 60) $grade = '2';
                                elseif ($totalScore >= 55) $grade = '1.5';
                                elseif ($totalScore >= 50) $grade = '1';
                            @endphp
                            <tr class="group transition-all duration-300">
                                <td class="bg-gray-50/50 group-hover:bg-primary-50 py-4 rounded-l-2xl border-y border-l border-transparent group-hover:border-primary-100 text-center">
                                    @if($rank == 1)
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 text-white flex items-center justify-center mx-auto shadow-lg shadow-amber-500/30">
                                            <i class="fas fa-crown text-sm"></i>
                                        </div>
                                    @elseif($rank == 2)
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 text-white flex items-center justify-center mx-auto shadow-lg shadow-gray-400/30">
                                            <span class="font-black">2</span>
                                        </div>
                                    @elseif($rank == 3)
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center mx-auto shadow-lg shadow-orange-600/30">
                                            <span class="font-black">3</span>
                                        </div>
                                    @else
                                        <span class="text-sm font-black text-gray-400">{{ $rank }}</span>
                                    @endif
                                </td>
                                <td class="bg-gray-50/50 group-hover:bg-primary-50 px-6 py-4 border-y border-transparent group-hover:border-primary-100">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $student->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm font-black">
                                        <div>
                                            <p class="font-bold text-gray-800 leading-none">{{ $student->name }}</p>
                                            <p class="text-[10px] font-black text-gray-400 mt-1 uppercase tracking-tighter">{{ $student->student_id }} • {{ $student->class_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                @foreach($assignments as $assignment)
                                    @php
                                        $submission = $student->typingSubmissions->where('assignment_id', $assignment->id)->first();
                                    @endphp
                                    <td class="bg-gray-50/50 group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100 text-center">
                                        @if($submission && $submission->score !== null)
                                            <span class="text-sm font-black {{ $submission->score >= 80 ? 'text-emerald-600' : ($submission->score >= 50 ? 'text-primary-600' : 'text-red-500') }}">
                                                {{ $submission->score }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 font-black">--</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="bg-white group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100 text-center font-black text-gray-800">
                                    {{ number_format($totalScore, 0) }}
                                </td>
                                <td class="bg-white group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100 text-center">
                                    <span class="text-sm font-black {{ $avgScore >= 80 ? 'text-emerald-600' : ($avgScore >= 50 ? 'text-primary-600' : 'text-red-500') }}">
                                        {{ number_format($avgScore, 1) }}%
                                    </span>
                                </td>
                                <td class="bg-white group-hover:bg-primary-50 py-4 rounded-r-2xl border-y border-r border-transparent group-hover:border-primary-100 text-center">
                                    @php
                                        $gradeColors = [
                                            '4' => 'bg-emerald-500 text-white shadow-emerald-500/20',
                                            '3.5' => 'bg-emerald-400 text-white shadow-emerald-400/20',
                                            '3' => 'bg-blue-500 text-white shadow-blue-500/20',
                                            '2.5' => 'bg-blue-400 text-white shadow-blue-400/20',
                                            '2' => 'bg-amber-500 text-white shadow-amber-500/20',
                                            '1.5' => 'bg-orange-500 text-white shadow-orange-500/20',
                                            '1' => 'bg-red-500 text-white shadow-red-500/20',
                                            '0' => 'bg-gray-800 text-white',
                                        ];
                                    @endphp
                                    <span class="inline-flex w-10 h-10 items-center justify-center rounded-xl text-xs font-black shadow-lg {{ $gradeColors[$grade] ?? 'bg-gray-400' }}">
                                        {{ $grade }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 6 + $assignments->count() }}" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center text-gray-200 text-4xl">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <p class="text-gray-400 font-bold">ไม่พบข้อมูลคะแนนนักเรียน</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Modern) -->
            <div class="mt-10 pt-8 border-t border-gray-50 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-sm font-black text-gray-400 uppercase tracking-widest">
                    Showing <span class="text-gray-800">{{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }}</span> 
                    of <span class="text-gray-800">{{ $students->total() }}</span> entries
                </p>
                <div class="premium-pagination">
                    {{ $students->links() }}
                </div>
            </div>
        </div>

        <!-- Grade Legend (Premium) -->
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
            <h3 class="flex items-center gap-3 text-lg font-black text-gray-800 mb-6">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                    <i class="fas fa-info-circle"></i>
                </div>
                เกณฑ์การคำนวณเกรด
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                @foreach([
                    ['4', '80-100', 'bg-emerald-500'],
                    ['3.5', '75-79', 'bg-emerald-400'],
                    ['3', '70-74', 'bg-blue-500'],
                    ['2.5', '65-69', 'bg-blue-400'],
                    ['2', '60-64', 'bg-amber-500'],
                    ['1.5', '55-59', 'bg-orange-500'],
                    ['1', '50-54', 'bg-red-500'],
                    ['0', '0-49', 'bg-gray-800']
                ] as $item)
                    <div class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-gray-50/50 border border-gray-100 transition-all hover:bg-white hover:shadow-md">
                        <span class="w-10 h-10 flex items-center justify-center rounded-xl text-white font-black text-xs shadow-sm {{ $item[2] }}">{{ $item[0] }}</span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tight">{{ $item[1] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .animate-pulse-slow { animation: pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 0.2; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.1); } }
        
        .premium-pagination nav span[aria-current="page"] > span {
            @apply bg-primary-500 text-white rounded-xl px-5 py-3 font-black border-none shadow-xl shadow-primary-500/30;
        }
        .premium-pagination nav a {
            @apply bg-white text-gray-400 rounded-xl px-5 py-3 font-black border border-gray-100 hover:bg-primary-50 hover:text-primary-500 transition-all mx-1 shadow-sm;
        }
    </style>
</x-typing-app>