<x-typing-app :role="'admin'" :title="'จัดการงาน - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                <i class="fas fa-tasks text-primary-500 mr-2"></i>
                จัดการงาน
            </h1>
            <p class="text-gray-500 mt-1">สร้างและจัดการงานทั้งหมด</p>
        </div>
        <a href="{{ route('typing.admin.assignments.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            สร้างงานใหม่
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-xl mr-3"></i>
            <p>{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif
    
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                <i class="fas fa-file-alt text-primary-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalAssignments }}</p>
                <p class="text-xs text-gray-500">งานทั้งหมด</p>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-secondary-100 flex items-center justify-center">
                <i class="fas fa-door-open text-secondary-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $activeAssignments }}</p>
                <p class="text-xs text-gray-500">เปิดรับงาน</p>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                <i class="fas fa-door-closed text-red-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalAssignments - $activeAssignments }}</p>
                <p class="text-xs text-gray-500">ปิดรับงาน</p>
            </div>
        </div>
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                <i class="fas fa-star text-amber-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalSubmissions }}</p>
                <p class="text-xs text-gray-500">จำนวนส่งงาน</p>
            </div>
        </div>
    </div>
    
    <!-- Assignments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Loop Assignments -->
        @forelse($assignments as $assignment)
        <div class="card p-0 overflow-hidden border-t-4 {{ $assignment->is_active ? 'border-secondary-500' : 'border-gray-400' }} hover:shadow-lg transition-shadow">
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    @if($assignment->is_active)
                        <span class="badge-secondary">เปิดรับงาน</span>
                    @else
                        <span class="badge-danger">ปิดรับงาน</span>
                    @endif
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="btn-ghost p-2">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10" x-cloak>
                            <a href="{{ route('typing.admin.assignments.edit', $assignment->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-edit mr-2"></i>แก้ไข
                            </a>
                            <a href="{{ route('typing.admin.submissions', ['assignment_id' => $assignment->id]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-search mr-2"></i>ตรวจงานที่ส่ง
                            </a>
                            <form action="{{ route('typing.admin.assignments.destroy', $assignment->id) }}" method="POST" class="block w-full text-left" onsubmit="return confirm('ยืนยันการลบ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-trash mr-2"></i>ลบ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate" title="{{ $assignment->title }}">{{ $assignment->title }}</h3>
                <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ Str::limit($assignment->content, 60) }}</p>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">คะแนนเต็ม</span>
                        <span class="font-medium text-gray-800">{{ $assignment->max_score }} คะแนน</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">กำหนดส่ง</span>
                        <span class="font-medium {{ $assignment->due_date && $assignment->due_date->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $assignment->due_date ? $assignment->due_date->format('d/m/Y') : '-' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">จำนวนที่ส่ง</span>
                        <span class="font-medium text-gray-800">{{ $assignment->submissions->count() }} Submissions</span>
                    </div>
                </div>
                
                <!-- Progress Mockup -->
                <div class="mt-4">
                    @php
                        // Mock progress calculation (random for demo if no users count)
                        $totalStudents = 45; // Mock total students
                        $percent = min(100, round(($assignment->submissions->count() / $totalStudents) * 100));
                    @endphp
                    <div class="progress">
                        <div class="progress-bar bg-gradient-to-r {{ $assignment->is_active ? 'from-secondary-500 to-secondary-400' : 'from-primary-500 to-primary-400' }}" style="width: {{ $percent }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 text-right">{{ $percent }}%</p>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('typing.admin.submissions', ['assignment_id' => $assignment->id]) }}" class="w-full btn-outline justify-center text-sm py-2">
                        <i class="fas fa-search mr-2"></i>ตรวจงาน ({{ $assignment->submissions->count() }})
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center text-gray-400">
            <i class="fas fa-folder-open text-6xl mb-4 opacity-50"></i>
            <p>ยังไม่มีบทเรียนในระบบ</p>
        </div>
        @endforelse

        <!-- Create New Card -->
        <a href="{{ route('typing.admin.assignments.create') }}" class="card p-0 overflow-hidden border-2 border-dashed border-gray-300 hover:border-primary-400 transition-colors cursor-pointer group">
            <div class="p-5 h-full flex flex-col items-center justify-center text-center min-h-[280px]">
                <div class="w-16 h-16 rounded-full bg-gray-100 group-hover:bg-primary-100 flex items-center justify-center mb-4 transition-colors">
                    <i class="fas fa-plus text-2xl text-gray-400 group-hover:text-primary-500 transition-colors"></i>
                </div>
                <h3 class="font-semibold text-gray-600 group-hover:text-primary-600 transition-colors">สร้างงานใหม่</h3>
                <p class="text-sm text-gray-400 mt-1">คลิกเพื่อเพิ่มงานให้นักเรียน</p>
            </div>
        </a>
    </div>
    
    <!-- Pagination -->
    <div class="mt-8">
        {{ $assignments->links() }}
    </div>
    
</x-typing-app>
