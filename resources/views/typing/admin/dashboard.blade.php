<x-typing-app :role="'admin'" :title="'แดชบอร์ดผู้ดูแล - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
            <i class="fas fa-home text-primary-500 mr-2"></i>
            แดชบอร์ด
        </h1>
        <p class="text-gray-500 mt-1">ยินดีต้อนรับ, ผู้ดูแลระบบ</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <!-- Total Students -->
        <div class="card group hover:shadow-lg cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">นักเรียนทั้งหมด</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalStudents }}</p>
                    <p class="text-xs text-secondary-600 mt-2">
                        <i class="fas fa-users mr-1"></i>
                        นักเรียนในระบบ
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-graduate text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Assignments -->
        <div class="card group hover:shadow-lg cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">งานทั้งหมด</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalAssignments }}</p>
                    <p class="text-xs text-secondary-600 mt-2">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ $closedAssignments }} งานปิดรับแล้ว
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-secondary-500 to-secondary-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-tasks text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Pending Submissions -->
        <div class="card group hover:shadow-lg cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">รอตรวจ</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pendingSubmissions }}</p>
                    <p class="text-xs text-amber-600 mt-2">
                        <i class="fas fa-clock mr-1"></i>
                        รอให้คะแนน
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-file-upload text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Average Score -->
        <div class="card group hover:shadow-lg cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">คะแนนเฉลี่ย</p>
                    <p class="text-3xl font-bold text-gray-800">{{ number_format($averageScore, 1) }}</p>
                    <p class="text-xs text-secondary-600 mt-2">
                        <i class="fas fa-chart-line mr-1"></i>
                        จากงานที่ตรวจแล้ว
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-bar text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Submissions (2 cols) -->
        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-clock text-amber-500 mr-2"></i>
                    งานที่ส่งล่าสุด
                </h2>
                <a href="{{ route('typing.admin.submissions') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    ดูทั้งหมด <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>นักเรียน</th>
                            <th>งาน</th>
                            <th>เวลาส่ง</th>
                            <th>สถานะ</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSubmissions as $submission)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $submission->user->avatar_url }}" alt="" class="avatar-sm object-cover">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $submission->user->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $submission->user->student_id ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $submission->assignment->title ?? '-' }}</td>
                            <td class="text-gray-500">{{ $submission->created_at->diffForHumans() }}</td>
                            <td>
                                @if($submission->score === null)
                                    <span class="badge-warning">รอตรวจ</span>
                                @else
                                    <span class="badge-secondary">ให้คะแนนแล้ว</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('typing.admin.submissions') }}" class="btn-ghost text-xs">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">ยังไม่มีการส่งงาน</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">
                <i class="fas fa-bolt text-amber-500 mr-2"></i>
                การดำเนินการด่วน
            </h2>
            
            <div class="space-y-3">
                <a href="{{ route('typing.admin.students.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-primary-50 hover:bg-primary-100 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 group-hover:text-primary-700">เพิ่มนักเรียน</p>
                        <p class="text-xs text-gray-500">เพิ่มนักเรียนใหม่เข้าระบบ</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
                
                <a href="{{ route('typing.admin.assignments.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-secondary-50 hover:bg-secondary-100 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-secondary-500 flex items-center justify-center">
                        <i class="fas fa-file-plus text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 group-hover:text-secondary-700">สร้างงานใหม่</p>
                        <p class="text-xs text-gray-500">มอบหมายงานให้นักเรียน</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
                
                <a href="{{ route('typing.admin.submissions') }}" class="flex items-center gap-3 p-3 rounded-xl bg-accent-50 hover:bg-accent-100 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-accent-500 flex items-center justify-center">
                        <i class="fas fa-star text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 group-hover:text-accent-700">ให้คะแนน</p>
                        <p class="text-xs text-gray-500">ตรวจงานและให้คะแนน</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
                
                <a href="{{ route('typing.leaderboard') }}" class="flex items-center gap-3 p-3 rounded-xl bg-amber-50 hover:bg-amber-100 transition-colors group">
                    <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center">
                        <i class="fas fa-trophy text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800 group-hover:text-amber-700">บอร์ดผู้นำ</p>
                        <p class="text-xs text-gray-500">ดูอันดับนักเรียน</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Assignment Status -->
    <div class="mt-6 card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-tasks text-secondary-500 mr-2"></i>
                สถานะงานทั้งหมด
            </h2>
            <a href="{{ route('typing.admin.assignments.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                จัดการงาน <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($assignments as $assignment)
            @php
                $percent = $totalStudents > 0 ? min(100, round(($assignment->submissions_count / $totalStudents) * 100)) : 0;
            @endphp
            <div class="p-4 rounded-xl border border-gray-200 hover:border-primary-300 hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $assignment->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($assignment->due_date)
                                กำหนดส่ง: {{ $assignment->due_date->format('d/m/Y') }}
                            @else
                                ไม่มีกำหนดส่ง
                            @endif
                        </p>
                    </div>
                    @if($assignment->is_active)
                        <span class="badge-secondary">เปิดรับงาน</span>
                    @else
                        <span class="badge-danger">ปิดรับงาน</span>
                    @endif
                </div>
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-gray-500">ส่งแล้ว</span>
                    <span class="font-medium text-gray-800">{{ $assignment->submissions_count }}/{{ $totalStudents }}</span>
                </div>
                <div class="progress">
                    <div class="progress-bar bg-gradient-to-r {{ $assignment->is_active ? 'from-secondary-500 to-secondary-400' : 'from-primary-500 to-primary-400' }}" style="width: {{ $percent }}%"></div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-8 text-center text-gray-400">
                <i class="fas fa-folder-open text-4xl mb-2"></i>
                <p>ยังไม่มีงานในระบบ</p>
            </div>
            @endforelse
        </div>
    </div>
    
</x-typing-app>
