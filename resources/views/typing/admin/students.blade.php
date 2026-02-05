<x-typing-app :role="'admin'" :title="'จัดการนักเรียน - ระบบวิชาพิมพ์หนังสือราชการ 1'">
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
                        <i class="fas fa-user-graduate text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tight">จัดการนักเรียน</h1>
                        <p class="text-gray-500 mt-1 font-medium flex items-center gap-2 text-lg">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            ดูและจัดการข้อมูลนักเรียนในระบบ
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button
                        class="flex items-center gap-2 px-6 py-4 rounded-2xl bg-white/60 backdrop-blur-md border border-white text-gray-700 font-black hover:bg-white hover:shadow-xl hover:-translate-y-0.5 transition-all shadow-sm"
                        x-data @click="$dispatch('open-import-modal')">
                        <i class="fas fa-file-import text-primary-500"></i>
                        นำเข้า Excel
                    </button>
                    <a href="{{ route('typing.admin.students.create') }}"
                        class="group relative flex items-center gap-3 px-8 py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-black hover:shadow-2xl hover:-translate-y-1 transition-all overflow-hidden shadow-lg shadow-gray-200">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-primary-400/0 via-primary-400/20 to-primary-400/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000">
                        </div>
                        <i class="fas fa-plus text-primary-400 group-hover:rotate-90 transition-transform"></i>
                        เพิ่มนักเรียนใหม่
                    </a>
                </div>
            </div>
        </div>

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
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">{{ $students->total() }}</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">นักเรียนทั้งหมด</p>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-emerald-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60">
                </div>
                <div class="relative flex flex-col gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">{{ count($classes) }}</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">ห้องเรียน</p>
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
                        <p class="text-3xl font-black text-gray-800 tracking-tight">Active</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">สถานะปัจจุบัน</p>
                    </div>
                </div>
            </div>

            <div
                class="group relative bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 hover:border-indigo-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-50 to-transparent rounded-bl-full -mr-8 -mt-8 opacity-60">
                </div>
                <div class="relative flex flex-col gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-inner group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-800 tracking-tight">Grad</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">การติดตาม</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters (Modern Card) -->
        <form action="{{ route('typing.admin.students.index') }}" method="GET"
            class="bg-white rounded-[2rem] p-4 shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-7 relative group">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ค้นหาชื่อ หรือ รหัสนักเรียน..."
                        class="w-full pl-12 pr-4 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium">
                    <div
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                        <i class="fas fa-search text-xl"></i>
                    </div>
                </div>
                <div class="md:col-span-3">
                    <select name="class"
                        class="w-full px-4 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all appearance-none cursor-pointer font-bold"
                        onchange="this.form.submit()">
                        <option value="">ทุกห้องเรียน</option>
                        @foreach($classes as $class)
                            <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>{{ $class }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit"
                        class="flex-1 rounded-2xl bg-primary-500 text-white font-black hover:bg-primary-600 transition-colors shadow-lg shadow-primary-500/20">
                        <i class="fas fa-filter mr-2"></i> กรอง
                    </button>
                    @if(request()->hasAny(['search', 'class']))
                        <a href="{{ route('typing.admin.students.index') }}"
                            class="w-12 flex items-center justify-center rounded-2xl bg-gray-100 text-gray-400 hover:bg-gray-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Students Table Card -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100 overflow-hidden relative">
            <div class="overflow-x-auto -mx-8">
                <table class="w-full text-left border-separate border-spacing-y-4 px-8">
                    <thead>
                        <tr class="text-gray-400 text-[10px] font-black uppercase tracking-widest">
                            <th class="pb-2 px-6">นักเรียน</th>
                            <th class="pb-2 text-center">ห้อง</th>
                            <th class="pb-2 text-center">ส่งงาน</th>
                            <th class="pb-2 text-center">คะแนนเฉลี่ย</th>
                            <th class="pb-2 text-center">สถานะ</th>
                            <th class="pb-2 text-right px-6">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr class="group transition-all duration-300">
                                <td
                                    class="bg-gray-50/50 group-hover:bg-primary-50 px-6 py-4 rounded-l-2xl border-y border-l border-transparent group-hover:border-primary-100">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <img src="{{ $student->avatar_url }}" alt=""
                                                class="w-12 h-12 rounded-full object-cover ring-4 ring-white shadow-sm">
                                            <div
                                                class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full">
                                            </div>
                                        </div>
                                        <div>
                                            <p
                                                class="font-bold text-gray-800 leading-none group-hover:text-primary-700 transition-colors">
                                                {{ $student->name }}</p>
                                            <p class="text-[10px] font-black text-gray-400 mt-1 uppercase tracking-widest">
                                                {{ $student->student_id ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="bg-gray-50/50 group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100 text-center text-sm font-bold text-gray-600">
                                    {{ $student->class_name ?? '-' }}
                                </td>
                                <td
                                    class="bg-gray-50/50 group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100 text-center">
                                    <span class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-sm font-black">
                                        {{ $student->typing_submissions_count }} งาน
                                    </span>
                                </td>
                                <td
                                    class="bg-gray-50/50 group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100 text-center">
                                    @php
                                        $avg = $student->typingSubmissions->count() > 0 ? $student->typingSubmissions->avg('score') : null;
                                        $colorClass = $avg >= 80 ? 'text-emerald-600' : ($avg >= 50 ? 'text-amber-500' : 'text-red-500');
                                    @endphp
                                    @if($avg !== null)
                                        <span class="text-xl font-black {{ $colorClass }}">{{ number_format($avg, 1) }}<span
                                                class="text-xs ml-0.5">%</span></span>
                                    @else
                                        <span class="text-gray-300 font-bold">-</span>
                                    @endif
                                </td>
                                <td
                                    class="bg-gray-50/50 group-hover:bg-primary-50 py-4 border-y border-transparent group-hover:border-primary-100 text-center">
                                    <span
                                        class="px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-200">
                                        กำลังศึกษา
                                    </span>
                                </td>
                                <td
                                    class="bg-gray-50/50 group-hover:bg-primary-50 px-6 py-4 rounded-r-2xl border-y border-r border-transparent group-hover:border-primary-100 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('typing.admin.students.edit', $student->id) }}"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-400 hover:text-primary-500 hover:shadow-lg transition-all border border-gray-100">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('typing.admin.students.destroy', $student->id) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('ยืนยันลบข้อมูลนักเรียนรายนี้?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-400 hover:text-red-500 hover:shadow-lg transition-all border border-gray-100">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-24 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div
                                            class="w-24 h-24 rounded-full bg-gray-50 flex items-center justify-center text-gray-200 text-5xl">
                                            <i class="fas fa-user-slash"></i>
                                        </div>
                                        <h3 class="text-xl font-black text-gray-400">ไม่พบข้อมูลนักเรียน</h3>
                                        <p class="text-gray-300 mt-2">ไม่มีข้อมูลในระบบ หรือลองเปลี่ยนเงื่อนไขการค้นหา</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Modern) -->
            <div
                class="mt-10 pt-8 border-t border-gray-50 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-sm font-black text-gray-400 uppercase tracking-widest">
                    Showing <span
                        class="text-gray-800">{{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }}</span>
                    of <span class="text-gray-800">{{ $students->total() }}</span> entries
                </p>
                <div class="premium-pagination">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Import Modal (Premium Redesign) -->
    <div x-data="{ showImportModal: false }" @open-import-modal.window="showImportModal = true" x-cloak>
        <div x-show="showImportModal"
            class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4">
            <!-- Overlay -->
            <div x-show="showImportModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                @click="showImportModal = false"></div>

            <!-- Content -->
            <div x-show="showImportModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-white/40">

                <form action="{{ route('typing.admin.students.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-500 flex items-center justify-center text-2xl shadow-inner">
                                <i class="fas fa-file-import"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-gray-800 tracking-tight">นำเข้าข้อมูล</h3>
                                <p class="text-sm font-medium text-gray-400 mt-1">อัปโหลดไฟล์ Excel
                                    เพื่อเพิ่มนักเรียนจำนวนมาก</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="p-6 rounded-3xl bg-indigo-50 border border-indigo-100 relative group">
                                <div class="flex items-start gap-4">
                                    <div class="text-indigo-500 mt-1">
                                        <i class="fas fa-lightbulb text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-indigo-700 leading-tight">โปรดใช้ไฟล์ต้นแบบ
                                        </p>
                                        <p class="text-xs font-medium text-indigo-600/70 mt-1 mb-4">
                                            เพื่อให้ระบบทำงานได้อย่างถูกต้อง กรุณาใช้ไฟล์ Excel ตามรูปแบบที่กำหนดไว้</p>
                                        <a href="{{ route('typing.admin.students.template') }}"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-indigo-600 text-xs font-black shadow-sm group-hover:shadow-md hover:-translate-y-0.5 transition-all">
                                            <i class="fas fa-download"></i> ดาวน์โหลด Template
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 px-2">เลือกไฟล์ข้อมูล
                                    (.xlsx)</label>
                                <label for="file-upload"
                                    class="group relative flex flex-col items-center justify-center px-6 py-10 border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50 hover:bg-white hover:border-primary-400 transition-all cursor-pointer">
                                    <div
                                        class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-gray-300 group-hover:bg-primary-50 group-hover:text-primary-500 transition-all transform group-hover:rotate-6 mb-4">
                                        <i class="fas fa-cloud-upload-alt text-3xl"></i>
                                    </div>
                                    <p
                                        class="text-sm font-black text-gray-400 group-hover:text-primary-600 transition-colors">
                                        คลิกหรือลากไฟล์มาวางที่นี่</p>
                                    <p class="text-[10px] font-bold text-gray-300 mt-1">XLSX, XLS (สูงสุด 10MB)</p>
                                    <input id="file-upload" name="file" type="file" class="sr-only"
                                        accept=".xlsx, .xls">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 flex gap-3">
                        <button type="button" @click="showImportModal = false"
                            class="flex-1 py-4 text-sm font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">
                            ยกเลิก
                        </button>
                        <button type="submit"
                            class="flex-[2] py-4 rounded-2xl bg-gray-900 text-white text-sm font-black uppercase tracking-widest hover:bg-black shadow-xl shadow-gray-200 transition-all">
                            นำเข้าข้อมูลทันที
                        </button>
                    </div>
                </form>
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