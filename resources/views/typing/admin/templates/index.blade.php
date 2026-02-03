<x-typing-app :role="'admin'" :title="'คลังเอกสารตัวอย่าง - ระบบวิชาพิมพ์หนังสือราชการ 1'">

    <!-- Hero Section -->
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-violet-600 to-purple-700 shadow-xl mb-10 text-white">
        <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full bg-white opacity-10 blur-3xl"></div>
        <div class="absolute top-1/2 -left-24 w-48 h-48 rounded-full bg-pink-400 opacity-20 blur-2xl"></div>

        <div class="relative z-10 px-8 py-10 md:py-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span
                        class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-medium border border-white/10">
                        <i class="fas fa-folder-open mr-1"></i> Template Library
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">คลังเอกสารตัวอย่าง</h1>
                <p class="text-violet-100 text-lg max-w-xl">
                    จัดการเอกสารราชการตัวอย่างสำหรับให้นักเรียนศึกษารูปแบบการพิมพ์ที่ถูกต้อง</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('typing.admin.templates.create') }}"
                    class="px-5 py-3 bg-white text-violet-700 font-semibold rounded-xl shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all flex items-center gap-2 transform hover:-translate-y-1">
                    <i class="fas fa-cloud-upload-alt text-lg"></i>
                    <span>อัปโหลดเอกสาร</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Templates -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-violet-50 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="relative z-10">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white shadow-lg mb-4">
                    <i class="fas fa-file-alt text-lg"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">เอกสารทั้งหมด</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
            </div>
        </div>

        <!-- Active Templates -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="relative z-10">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white shadow-lg mb-4">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">เปิดใช้งาน</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['active'] }}</h3>
            </div>
        </div>

        <!-- Featured Templates -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="relative z-10">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white shadow-lg mb-4">
                    <i class="fas fa-star text-lg"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">แนะนำ</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $stats['featured'] }}</h3>
            </div>
        </div>

        <!-- Total Downloads -->
        <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="relative z-10">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white shadow-lg mb-4">
                    <i class="fas fa-download text-lg"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">ดาวน์โหลดทั้งหมด</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_downloads']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาเอกสาร..."
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all">
                </div>
            </div>
            <div class="w-full md:w-64">
                <select name="category"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all">
                    <option value="">-- ทุกหมวดหมู่ --</option>
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-filter"></i>
                <span>กรอง</span>
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('typing.admin.templates.index') }}"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i>
                    <span>ล้าง</span>
                </a>
            @endif
        </form>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3 text-green-700">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @forelse($templates as $template)
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:border-violet-200 transition-all group">
                <!-- Thumbnail/Preview -->
                <div
                    class="relative h-40 bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center overflow-hidden">
                    @if($template->thumbnail)
                        <img src="{{ asset('storage/' . $template->thumbnail) }}" alt="{{ $template->title }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="text-center">
                            @php
                                $iconClass = match (strtolower($template->file_type)) {
                                    'pdf' => 'fa-file-pdf text-red-500',
                                    'doc', 'docx' => 'fa-file-word text-blue-500',
                                    'odt' => 'fa-file-alt text-green-500',
                                    default => 'fa-file text-gray-400'
                                };
                            @endphp
                            <i class="fas {{ $iconClass }} text-5xl mb-2 group-hover:scale-110 transition-transform"></i>
                            <p class="text-xs text-gray-400 uppercase font-semibold">{{ $template->file_type }}</p>
                        </div>
                    @endif

                    <!-- Badges -->
                    <div class="absolute top-3 left-3 flex flex-col gap-2">
                        @if($template->is_featured)
                            <span class="px-2 py-1 bg-amber-500 text-white text-xs font-semibold rounded-lg shadow-sm">
                                <i class="fas fa-star mr-1"></i> แนะนำ
                            </span>
                        @endif
                        @if(!$template->is_active)
                            <span class="px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded-lg shadow-sm">
                                <i class="fas fa-eye-slash mr-1"></i> ปิดใช้งาน
                            </span>
                        @endif
                    </div>

                    <!-- Category Badge -->
                    <div class="absolute top-3 right-3">
                        <span
                            class="px-2 py-1 bg-white/90 backdrop-blur-sm text-gray-700 text-xs font-medium rounded-lg shadow-sm">
                            {{ $template->category }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-5">
                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-violet-600 transition-colors">
                        {{ $template->title }}
                    </h3>

                    @if($template->description)
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $template->description }}</p>
                    @endif

                    <!-- Stats -->
                    <div class="flex items-center gap-4 text-xs text-gray-400 mb-4">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-download"></i> {{ number_format($template->download_count) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-eye"></i> {{ number_format($template->view_count) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-hdd"></i> {{ $template->formatted_file_size }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('typing.admin.templates.download', $template) }}"
                            class="flex-1 py-2 bg-violet-50 hover:bg-violet-100 text-violet-600 text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-1">
                            <i class="fas fa-download text-xs"></i> ดาวน์โหลด
                        </a>
                        <a href="{{ route('typing.admin.templates.edit', $template) }}"
                            class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('typing.admin.templates.destroy', $template) }}" method="POST"
                            onsubmit="return confirm('ต้องการลบเอกสารนี้หรือไม่?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-10 h-10 flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-500 rounded-lg transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                    <div
                        class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <i class="fas fa-folder-open text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">ยังไม่มีเอกสารตัวอย่าง</h3>
                    <p class="text-gray-400 mb-6">เริ่มต้นด้วยการอัปโหลดเอกสารตัวอย่างสำหรับนักเรียน</p>
                    <a href="{{ route('typing.admin.templates.create') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl transition-colors">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>อัปโหลดเอกสาร</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($templates->hasPages())
        <div class="flex justify-center">
            {{ $templates->links() }}
        </div>
    @endif

</x-typing-app>