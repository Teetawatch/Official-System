<x-typing-app :role="'admin'" :title="'จัดการนักเรียน - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                <i class="fas fa-user-graduate text-primary-500 mr-2"></i>
                จัดการนักเรียน
            </h1>
            <p class="text-gray-500 mt-1">ดูและจัดการข้อมูลนักเรียนทั้งหมด</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('typing.admin.students.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                เพิ่มนักเรียน
            </a>
            <button class="btn-outline" x-data @click="$dispatch('open-import-modal')">
                <i class="fas fa-file-import mr-2"></i>
                นำเข้า Excel
            </button>
        </div>
    </div>
    
    <!-- Search & Filters -->
    <div class="card mb-6">
        <form action="{{ route('typing.admin.students.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อ, รหัสนักเรียน..." class="input pl-10">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <select name="class" class="input w-full md:w-48" onchange="this.form.submit()">
                <option value="">ทุกห้อง</option>
                @foreach($classes as $class)
                    <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>
                        {{ $class }}
                    </option>
                @endforeach
            </select>
            <div class="flex items-center gap-2">
                 <button type="submit" class="btn-secondary">ค้นหา</button>
                 @if(request()->hasAny(['search', 'class']))
                    <a href="{{ route('typing.admin.students.index') }}" class="btn-ghost">ล้างค่า</a>
                 @endif
            </div>
        </form>
    </div>
    
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="card p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                <i class="fas fa-users text-primary-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $students->total() }}</p>
                <p class="text-xs text-gray-500">นักเรียนทั้งหมด</p>
            </div>
        </div>
        <!-- Other stats can remain static or be calculated in controller if needed -->
    </div>
    
    <!-- Students Table -->
    <div class="card">
        <div class="overflow-x-auto w-full">
            <table class="table">
                <thead>
                    <tr>
                        <th>นักเรียน</th>
                        <th>ห้อง</th>
                        <th>ส่งงาน</th>
                        <th>คะแนนเฉลี่ย</th>
                        <th>สถานะ</th>
                        <th>การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $student->avatar_url }}" alt="" class="avatar-sm object-cover">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->student_id ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->class_name ?? '-' }}</td>
                        <td>
                            <span class="text-secondary-600 font-medium">{{ $student->typing_submissions_count }}</span>
                        </td>
                        <td>
                            @php
                                $avg = $student->typingSubmissions->count() > 0 ? $student->typingSubmissions->avg('score') : null;
                                $color = $avg >= 80 ? 'text-primary-600' : ($avg >= 50 ? 'text-amber-600' : 'text-red-600');
                            @endphp
                            <span class="text-lg font-bold {{ $color }}">{{ $avg !== null ? number_format($avg, 1) . '%' : '-' }}</span>
                        </td>
                        <td><span class="badge-secondary">กำลังศึกษา</span></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('typing.admin.students.edit', $student->id) }}" class="btn-ghost p-2" title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('typing.admin.students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันลบข้อมูลนักเรียนรายนี้?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-ghost p-2 text-red-500 hover:bg-red-50" title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">ไม่พบข้อมูลนักเรียน</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6 pt-6 border-t border-gray-100">
            {{ $students->links() }}
        </div>
    </div>
    
    <!-- Import Modal -->
    <div x-data="{ showImportModal: false }" @open-import-modal.window="showImportModal = true">
        <div x-show="showImportModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showImportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showImportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('typing.admin.students.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-file-import text-blue-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        นำเข้าข้อมูลนักเรียน
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">
                                            กรุณาดาวน์โหลดไฟล์ต้นแบบ (Template) เพื่อกรอกข้อมูลนักเรียนให้ถูกต้อง แล้วนำไฟล์มาอัปโหลดที่นี่
                                        </p>
                                        
                                        <div class="mb-4">
                                            <a href="{{ route('typing.admin.students.template') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                <i class="fas fa-download mr-2"></i> ดาวน์โหลด Template
                                            </a>
                                        </div>

                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700">เลือกไฟล์ Excel (.xlsx)</label>
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                                <div class="space-y-1 text-center">
                                                    <i class="fas fa-file-excel text-gray-400 text-3xl mb-2"></i>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                                            <span>อัปโหลดไฟล์</span>
                                                            <input id="file-upload" name="file" type="file" class="sr-only" accept=".xlsx, .xls">
                                                        </label>
                                                        <p class="pl-1">หรือลากไฟล์มาวางที่นี่</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">XLSX, XLS up to 10MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                                นำเข้าข้อมูล
                            </button>
                            <button type="button" @click="showImportModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                ยกเลิก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-typing-app>
