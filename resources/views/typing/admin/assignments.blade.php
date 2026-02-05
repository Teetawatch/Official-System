<x-typing-app :role="'admin'" :title="'จัดการงาน - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    <div class="space-y-10 pb-12">

        <!-- Aurora & Glass Header -->
        <div
            class="relative overflow-hidden bg-white border border-white/40 rounded-[2.5rem] p-8 shadow-2xl group transition-all duration-500 hover:shadow-primary-500/10">
            <!-- Aurora Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-white via-indigo-50/50 to-blue-50/30 opacity-80"></div>
            <div
                class="absolute top-[-30%] right-[-10%] w-[600px] h-[600px] bg-gradient-to-br from-orange-300/10 via-pink-300/10 to-rose-200/10 rounded-full blur-[80px] animate-pulse-slow pointer-events-none mix-blend-multiply">
            </div>
            <div
                class="absolute bottom-[-20%] left-[-10%] w-[500px] h-[500px] bg-gradient-to-tr from-blue-200/10 via-indigo-200/10 to-purple-200/10 rounded-full blur-[80px] animate-pulse-slow delay-1000 pointer-events-none mix-blend-multiply">
            </div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-primary-500/30 transform group-hover:rotate-6 transition-transform">
                        <i class="fas fa-tasks text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tight">จัดการงาน</h1>
                        <p class="text-gray-500 mt-1 font-medium flex items-center gap-2 text-lg">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            สร้างและจัดการงานพิมพ์ของนักเรียน
                        </p>
                    </div>
                </div>

                <a href="{{ route('typing.admin.assignments.create') }}"
                    class="group relative flex items-center gap-3 px-8 py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-black hover:shadow-2xl hover:-translate-y-1 transition-all overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-primary-400/0 via-primary-400/20 to-primary-400/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000">
                    </div>
                    <i class="fas fa-plus text-primary-400 group-hover:rotate-90 transition-transform"></i>
                    สร้างงานใหม่
                </a>
            </div>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="relative overflow-hidden bg-emerald-500 text-white rounded-[1.5rem] p-5 shadow-xl shadow-emerald-500/20 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <p class="font-bold tracking-wide">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="w-8 h-8 rounded-full hover:bg-white/20 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Bento Grid Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-primary-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-primary-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60">
                </div>
                <div class="relative flex flex-col gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-primary-500 group-hover:text-white transition-colors">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">{{ $totalAssignments }}</p>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">งานทั้งหมด</p>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-secondary-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60">
                </div>
                <div class="relative flex flex-col gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-secondary-50 text-secondary-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-secondary-500 group-hover:text-white transition-colors">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">{{ $activeAssignments }}</p>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">เปิดรับงาน</p>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-red-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-red-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60">
                </div>
                <div class="relative flex flex-col gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-red-500 group-hover:text-white transition-colors">
                        <i class="fas fa-door-closed"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">
                            {{ $totalAssignments - $activeAssignments }}</p>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">ปิดรับงาน</p>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-amber-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60">
                </div>
                <div class="relative flex flex-col gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-amber-500 group-hover:text-white transition-colors">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">{{ $totalSubmissions }}</p>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">จำนวนส่งงาน</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

            @forelse($assignments as $assignment)
                <div
                    class="group relative bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 hover:shadow-2xl hover:shadow-primary-500/10 transition-all duration-500 flex flex-col overflow-hidden">
                    <!-- Status Decoration -->
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br {{ $assignment->is_active ? 'from-emerald-50' : 'from-gray-50' }} rounded-bl-full -mr-10 -mt-10 opacity-60">
                    </div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex items-start justify-between mb-6">
                            @if($assignment->is_active)
                                <span
                                    class="px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    เปิดรับงาน
                                </span>
                            @else
                                <span
                                    class="px-4 py-1.5 rounded-full bg-gray-100 text-gray-500 text-[10px] font-black uppercase tracking-widest flex items-center gap-2 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    ปิดรับงาน
                                </span>
                            @endif

                            <div class="flex items-center gap-2" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-white hover:text-primary-500 hover:shadow-lg transition-all flex items-center justify-center">
                                    <i class="fas fa-ellipsis-h text-sm"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                    class="absolute right-0 top-12 w-48 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-20">
                                    <a href="{{ route('typing.admin.assignments.edit', $assignment->id) }}"
                                        class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                        <i class="fas fa-edit"></i> แก้ไขงาน
                                    </a>
                                    <a href="{{ route('typing.admin.submissions', ['assignment_id' => $assignment->id]) }}"
                                        class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                        <i class="fas fa-inbox"></i> ตรวจงานที่ส่ง
                                    </a>
                                    <div class="h-px bg-gray-100 my-1 mx-2"></div>
                                    <form action="{{ route('typing.admin.assignments.destroy', $assignment->id) }}"
                                        method="POST" class="w-full"
                                        onsubmit="return confirm('ยืนยันการลบงานนี้? งานที่ลบจะไม่สามารถกู้คืนได้');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors">
                                            <i class="fas fa-trash-alt"></i> ลบงาน
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <span
                                class="text-[10px] font-black text-primary-500 bg-primary-50 px-3 py-1 rounded-lg uppercase tracking-widest border border-primary-100">
                                {{ $assignment->chapter ?: 'บทเรียนทั่วไป' }}
                            </span>
                        </div>

                        <h3 class="text-2xl font-black text-gray-800 mb-3 leading-tight line-clamp-2 min-h-[3.5rem]"
                            title="{{ $assignment->title }}">
                            {{ $assignment->title }}
                        </h3>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">คะแนนเต็ม</span>
                                <div class="px-3 py-1 bg-gray-50 rounded-lg text-sm font-black text-gray-800">
                                    {{ $assignment->max_score }} <span class="text-[10px] text-gray-400">PTS</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">กำหนดส่ง</span>
                                <div
                                    class="flex items-center gap-2 {{ $assignment->due_date && $assignment->due_date->isPast() ? 'text-red-500 bg-red-50' : 'text-gray-800 bg-gray-50' }} px-3 py-1 rounded-lg text-sm font-black">
                                    <i class="far fa-calendar-alt text-xs opacity-50"></i>
                                    {{ $assignment->due_date ? $assignment->due_date->format('d/m/Y') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto space-y-6">
                            <!-- Progress Stats -->
                            <div class="space-y-2">
                                @php
                                    $totalStudents = 45; // Mock/Real total students
                                    $percent = min(100, round(($assignment->submissions->count() / $totalStudents) * 100));
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-xs font-bold text-gray-400 uppercase tracking-widest">ความคืบหน้าการส่ง</span>
                                    <span
                                        class="text-xs font-black text-gray-800">{{ $assignment->submissions->count() }}/{{ $totalStudents }}
                                        คน</span>
                                </div>
                                <div
                                    class="h-3 w-full bg-gray-100 rounded-full overflow-hidden p-0.5 border border-gray-50">
                                    <div class="h-full rounded-full transition-all duration-1000 bg-gradient-to-r {{ $assignment->is_active ? 'from-secondary-500 to-primary-500' : 'from-gray-400 to-gray-500' }} shadow-sm"
                                        style="width: {{ $percent }}%"></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <a href="{{ route('typing.admin.submissions', ['assignment_id' => $assignment->id]) }}"
                                    class="flex-1 flex items-center justify-center gap-2 py-4 bg-primary-600 text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl shadow-primary-500/20 hover:bg-primary-700 hover:-translate-y-1 transition-all">
                                    <i class="fas fa-search"></i> ตรวจงาน
                                </a>
                                <a href="{{ route('typing.admin.assignments.edit', $assignment->id) }}"
                                    class="w-14 h-14 flex items-center justify-center bg-white border border-gray-100 rounded-2xl text-gray-400 hover:text-primary-500 hover:bg-primary-50 transition-all shadow-sm">
                                    <i class="fas fa-cog"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center">
                    <div
                        class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200 text-5xl">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-400">ยังไม่มีบทเรียนในระบบ</h3>
                    <p class="text-gray-300 mt-2">เริ่มต้นโดยการคลิกปุ่มสร้างงานใหม่</p>
                </div>
            @endforelse

            <!-- Create New Premium Card -->
            <a href="{{ route('typing.admin.assignments.create') }}"
                class="relative h-full min-h-[400px] flex flex-col items-center justify-center text-center p-8 rounded-[2.5rem] border-2 border-dashed border-gray-200 bg-gray-50/30 hover:bg-white hover:border-primary-400 hover:shadow-2xl transition-all duration-500 group">
                <div
                    class="w-20 h-20 rounded-3xl bg-white shadow-xl flex items-center justify-center text-gray-300 group-hover:bg-primary-500 group-hover:text-white transition-all duration-500 mb-6 transform group-hover:rotate-12">
                    <i class="fas fa-plus text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-300 group-hover:text-primary-600 transition-colors">
                    สร้างงานใหม่</h3>
                <p class="text-sm font-bold text-gray-300 mt-2 max-w-[200px]">
                    เพิ่มบทเรียนหรือชิ้นงานใหม่ให้นักเรียนของคุณ</p>

                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                    <div class="absolute top-10 left-10 w-2 h-2 rounded-full bg-primary-200"></div>
                    <div class="absolute bottom-10 right-10 w-3 h-3 rounded-full bg-indigo-200"></div>
                    <div class="absolute top-20 right-20 w-1.5 h-1.5 rounded-full bg-amber-200"></div>
                </div>
            </a>
        </div>

        <!-- Pagination (Modern) -->
        <div class="mt-12 pt-10 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-black text-gray-400 uppercase tracking-widest">
                Showing <span
                    class="text-gray-800">{{ $assignments->firstItem() ?? 0 }}-{{ $assignments->lastItem() ?? 0 }}</span>
                of <span class="text-gray-800">{{ $assignments->total() }}</span> entries
            </p>
            <div class="premium-pagination">
                {{ $assignments->links() }}
            </div>
        </div>
    </div>

    <style>
        .animate-pulse-slow {
            animation: pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.2;
                transform: scale(1);
            }

            50% {
                opacity: 0.4;
                transform: scale(1.1);
            }
        }

        .premium-pagination nav span[aria-current="page"]>span {
            @apply bg-primary-500 text-white rounded-xl px-5 py-3 font-black border-none shadow-xl shadow-primary-500/30;
        }

        .premium-pagination nav a {
            @apply bg-white text-gray-400 rounded-xl px-5 py-3 font-black border border-gray-100 hover:bg-primary-50 hover:text-primary-500 transition-all mx-1 shadow-sm;
        }
    </style>

</x-typing-app>