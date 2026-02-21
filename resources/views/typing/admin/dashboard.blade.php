<x-typing-app :role="'admin'" :title="'แดชบอร์ดผู้ดูแล - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Hero / Welcome Section -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-600 to-primary-800 shadow-xl mb-10 text-white">
        <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full bg-white opacity-10 blur-3xl"></div>
        <div class="absolute top-1/2 -left-24 w-48 h-48 rounded-full bg-secondary-400 opacity-20 blur-2xl"></div>
        
        <div class="relative z-10 px-8 py-10 md:py-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-medium border border-white/10">
                        แดชบอร์ดผู้ดูแล
                    </span>
                    <span class="text-primary-100 text-sm">{{ now()->locale('th')->isoFormat('RDD MMMM YYYY') }}</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">ยินดีต้อนรับ, ผู้ดูแลระบบ</h1>
                <p class="text-primary-100 text-lg max-w-xl">จัดการข้อมูลนักเรียน ตรวจงาน และติดตามความคืบหน้าของรายวิชาพิมพ์หนังสือราชการ 1 ได้ที่นี่</p>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('typing.admin.assignments.create') }}" class="px-5 py-3 bg-white text-primary-700 font-semibold rounded-xl shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all flex items-center gap-2 transform hover:-translate-y-1">
                    <i class="fas fa-plus-circle text-lg"></i>
                    <span>สร้างงานใหม่</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <!-- Total Students -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-primary-100 transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-primary-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-user-graduate text-lg"></i>
                </div>
                
                <p class="text-gray-500 text-sm font-medium mb-1">นักเรียนทั้งหมด</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalStudents }}</h3>
                    <span class="text-xs text-gray-400 mb-1">คน</span>
                </div>
                <div class="mt-3 text-xs font-medium text-secondary-600 bg-secondary-50 inline-block px-2 py-1 rounded-md">
                    <i class="fas fa-check mr-1"></i> ในระบบ
                </div>
            </div>
        </div>
        
        <!-- Total Assignments -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-secondary-100 transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-secondary-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-secondary-500 to-secondary-600 flex items-center justify-center text-white shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-tasks text-lg"></i>
                </div>
                
                <p class="text-gray-500 text-sm font-medium mb-1">งานทั้งหมด</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalAssignments }}</h3>
                    <span class="text-xs text-gray-400 mb-1">งาน</span>
                </div>
                <div class="mt-3 text-xs font-medium text-gray-500 bg-gray-100 inline-block px-2 py-1 rounded-md">
                    <span>{{ $closedAssignments }} งานปิดรับแล้ว</span>
                </div>
            </div>
        </div>
        
        <!-- Pending Submissions -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-amber-100 transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-clock text-lg"></i>
                </div>
                
                <p class="text-gray-500 text-sm font-medium mb-1">รอตรวจ</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $pendingSubmissions }}</h3>
                    <span class="text-xs text-gray-400 mb-1">รายการ</span>
                </div>
                @if($pendingSubmissions > 0)
                    <div class="mt-3 text-xs font-medium text-amber-600 bg-amber-50 inline-block px-2 py-1 rounded-md animate-pulse">
                        <i class="fas fa-exclamation-circle mr-1"></i> ต้องดำเนินการ
                    </div>
                @else
                    <div class="mt-3 text-xs font-medium text-green-600 bg-green-50 inline-block px-2 py-1 rounded-md">
                        <i class="fas fa-check-circle mr-1"></i> เรียบร้อย
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Average Score -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-accent-100 transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-accent-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
                
                <p class="text-gray-500 text-sm font-medium mb-1">คะแนนเฉลี่ย</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-bold text-gray-800">{{ number_format($averageScore, 1) }}</h3>
                    <span class="text-xs text-gray-400 mb-1">/ 100</span>
                </div>
                <div class="mt-3 text-xs font-medium text-accent-600 bg-accent-50 inline-block px-2 py-1 rounded-md">
                    <i class="fas fa-poll mr-1"></i> ภาพรวม
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Left Column: Recent Submissions -->
        <div class="xl:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-gradient-to-b from-amber-400 to-orange-500 rounded-full"></span>
                            งานที่ส่งล่าสุด
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">รายการส่งงาน 10 รายการล่าสุดจากนักเรียน</p>
                    </div>
                    <a href="{{ route('typing.admin.submissions') }}" class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                        ดูทั้งหมด <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                                <th class="px-6 py-4">นักเรียน</th>
                                <th class="px-6 py-4">งานที่ส่ง</th>
                                <th class="px-6 py-4">สถานะ</th>
                                <th class="px-6 py-4 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentSubmissions as $submission)
                                <tr class="hover:bg-gray-50/80 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gray-100 overflow-hidden border border-gray-200">
                                                @if($submission->user->avatar_url)
                                                    <img src="{{ $submission->user->avatar_url }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800 text-sm group-hover:text-primary-600 transition-colors">{{ $submission->user->name ?? 'ไม่ทราบชื่อ' }}</p>
                                                <p class="text-xs text-gray-400">{{ $submission->user->student_id ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-gray-700">{{ $submission->assignment->title ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                            <i class="fas fa-clock text-[10px]"></i>
                                            {{ $submission->created_at->diffForHumans() }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($submission->score === null)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                                รอตรวจ
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600 border border-green-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                                ตรวจแล้ว
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('typing.admin.submissions') }}" class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-primary-600 hover:border-primary-200 hover:bg-primary-50 transition-all shadow-sm">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-300">
                                                <i class="fas fa-inbox text-2xl"></i>
                                            </div>
                                            <p class="text-sm font-medium">ยังไม่มีการส่งงาน</p>
                                            <p class="text-xs text-gray-400 mt-1">รายการส่งงานจะปรากฏที่นี่เมื่อนักเรียนเริ่มส่งงาน</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Assignments progress -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-gradient-to-b from-secondary-400 to-emerald-500 rounded-full"></span>
                            สถานะการส่งงาน
                        </h2>
                    </div>
                    <a href="{{ route('typing.admin.assignments.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium hover:underline">
                        จัดการงานทั้งหมด
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($assignments as $assignment)
                        @php
                            $percent = $totalStudents > 0 ? min(100, round(($assignment->submissions_count / $totalStudents) * 100)) : 0;
                            $isActive = $assignment->is_active;
                            $colorClass = $isActive ? 'from-secondary-500 to-emerald-400' : 'from-gray-400 to-gray-500';
                            $bgClass = $isActive ? 'bg-secondary-50 text-secondary-700 border-secondary-100' : 'bg-gray-50 text-gray-600 border-gray-100';
                        @endphp
                        <div class="p-4 rounded-xl border border-gray-100 hover:border-primary-200 hover:shadow-md transition-all bg-white group">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-800 line-clamp-1 group-hover:text-primary-600 transition-colors" title="{{ $assignment->title }}">{{ $assignment->title }}</h3>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                        <i class="fas fa-calendar-alt text-[10px]"></i>
                                        {{ $assignment->due_date ? $assignment->due_date->format('d/m/Y') : 'ไม่มีกำหนด' }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 rounded text-[10px] font-semibold border {{ $isActive ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                                    {{ $isActive ? 'กำลังดำเนิน' : 'ปิดรับแล้ว' }}
                                </span>
                            </div>

                            <div class="flex justify-between items-end mb-1 text-xs">
                                <span class="text-gray-500">ความคืบหน้า</span>
                                <span class="font-bold text-gray-700">{{ $percent }}%</span>
                            </div>
                            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r {{ $colorClass }} rounded-full transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                            </div>
                            <div class="mt-2 text-xs text-gray-400 text-right">
                                ส่งแล้ว {{ $assignment->submissions_count }} จาก {{ $totalStudents }}
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-8 text-center text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <p>ไม่มีข้อมูลงาน</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Right Column: Quick Actions & Leaderboard Preview -->
        <div class="space-y-8">
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-amber-500"></i>
                    เมนูด่วน
                </h2>
                <div class="space-y-3">
                    <a href="{{ route('typing.admin.students.create') }}" class="flex items-center p-3 rounded-xl hover:bg-primary-50 border border-transparent hover:border-primary-100 transition-all group">
                        <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center mr-4 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-700 group-hover:text-primary-700">เพิ่มนักเรียน</h4>
                            <p class="text-xs text-gray-500">สร้างบัญชีผู้ใช้ใหม่</p>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-gray-300 group-hover:text-primary-400"></i>
                    </a>
                    
                    <a href="{{ route('typing.admin.assignments.create') }}" class="flex items-center p-3 rounded-xl hover:bg-secondary-50 border border-transparent hover:border-secondary-100 transition-all group">
                        <div class="w-10 h-10 rounded-lg bg-secondary-100 text-secondary-600 flex items-center justify-center mr-4 group-hover:bg-secondary-600 group-hover:text-white transition-colors">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-700 group-hover:text-secondary-700">สร้างงานใหม่</h4>
                            <p class="text-xs text-gray-500">มอบหมายงานพิมพ์</p>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-gray-300 group-hover:text-secondary-400"></i>
                    </a>
                    
                    <a href="{{ route('typing.admin.submissions') }}" class="flex items-center p-3 rounded-xl hover:bg-accent-50 border border-transparent hover:border-accent-100 transition-all group">
                        <div class="w-10 h-10 rounded-lg bg-accent-100 text-accent-600 flex items-center justify-center mr-4 group-hover:bg-accent-600 group-hover:text-white transition-colors">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-700 group-hover:text-accent-700">ตรวจให้คะแนน</h4>
                            <p class="text-xs text-gray-500">ดูงานที่รอตรวจ</p>
                        </div>
                        <div class="w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] flex items-center justify-center font-bold">
                            {{ $pendingSubmissions > 9 ? '9+' : $pendingSubmissions }}
                        </div>
                    </a>
                </div>
            </div>

            <!-- Mini Tips / Info -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16 blur-xl"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-primary-500 opacity-20 rounded-full -ml-10 -mb-10 blur-xl"></div>
                
                <h3 class="font-bold text-lg mb-2 relative z-10">ระบบวิชาพิมพ์</h3>
                <p class="text-slate-300 text-sm mb-4 relative z-10">
                    ติดตามและจัดการการเรียนการสอนวิชาพิมพ์หนังสือราชการได้ง่ายขึ้น
                </p>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 border border-white/10 mb-2">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-info-circle text-sky-400 text-xs"></i>
                        <span class="text-xs font-semibold text-sky-100">เคล็ดลับ</span>
                    </div>
                    <p class="text-xs text-slate-300">
                        คุณสามารถดูรายงานคะแนนรวมของนักเรียนได้ที่เมนู "บอร์ดผู้นำ"
                    </p>
                </div>
                
                <a href="{{ route('typing.leaderboard') }}" class="w-full mt-2 py-2 bg-primary-600 hover:bg-primary-500 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-trophy"></i>
                    ไปที่บอร์ดผู้นำ
                </a>
            </div>
            
        </div>
    </div>
    
</x-typing-app>
