<x-typing-app :role="'student'" :title="'คลังเอกสารตัวอย่าง - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Hero Section -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-[#0f172a] shadow-2xl mb-12 group">
        <!-- Animated Background Elements -->
        <div class="absolute top-[-10%] right-[-5%] w-[400px] h-[400px] rounded-full bg-gradient-to-br from-violet-600/20 to-purple-600/20 blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[5%] w-[300px] h-[300px] rounded-full bg-gradient-to-tr from-indigo-600/20 to-cyan-600/20 blur-[80px] animate-pulse" style="animation-delay: 2s"></div>
        
        <div class="relative z-10 px-10 py-16 md:py-20 flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-300 text-sm font-medium mb-6 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-violet-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-violet-500"></span>
                    </span>
                    Template Library
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-6 leading-tight">
                    คลังเอกสาร <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-400 font-black">ตัวอย่าง</span>
                </h1>
                <p class="text-slate-400 text-lg md:text-xl leading-relaxed">
                    ยกระดับทักษะการพิมพ์หนังสือราชการของคุณด้วยเอกสารต้นแบบที่ถูกต้อง 
                    คัดสรรมาเพื่อการเรียนรู้ที่มีประสิทธิภาพสูงสุด
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <div class="flex -space-x-3 overflow-hidden">
                        <div class="inline-block h-10 w-10 rounded-full ring-2 ring-slate-900 bg-violet-500 flex items-center justify-center text-xs font-bold text-white">100+</div>
                        <div class="inline-block h-10 w-10 rounded-full ring-2 ring-slate-900 bg-indigo-500 flex items-center justify-center text-xs font-bold text-white">PDF</div>
                        <div class="inline-block h-10 w-10 rounded-full ring-2 ring-slate-900 bg-blue-500 flex items-center justify-center text-xs font-bold text-white">DOCX</div>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400 text-sm">
                        <span class="font-bold text-white">แหล่งรวม</span> เอกสารที่สมบูรณ์ที่สุด
                    </div>
                </div>
            </div>
            
            <div class="hidden lg:block relative">
                <div class="relative w-64 h-64 bg-gradient-to-br from-violet-500 to-purple-700 rounded-3xl rotate-12 shadow-2xl flex items-center justify-center overflow-hidden group-hover:rotate-6 transition-transform duration-500">
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                    <i class="fas fa-file-invoice text-7xl text-white opacity-40"></i>
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                </div>
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-violet-600/30 rounded-full blur-3xl -z-10"></div>
            </div>
        </div>
    </div>

    @if($featuredTemplates->count() > 0)
        <!-- Featured Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">เอกสารแนะนำ</h2>
                        <p class="text-sm text-gray-500">เอกสารตัวอย่างที่ผู้สอนแนะนำให้ศึกษา</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredTemplates as $template)
                    <a href="{{ route('typing.student.templates.show', $template) }}" 
                       class="group relative bg-[#1e293b] rounded-3xl shadow-2xl overflow-hidden hover:-translate-y-2 transition-all duration-500 border border-slate-800">
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-br from-violet-600/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                        <!-- Thumbnail -->
                        <div class="relative h-56 flex items-center justify-center overflow-hidden">
                            @if($template->thumbnail)
                                <img src="{{ asset('uploads/' . $template->thumbnail) }}" alt="{{ $template->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="text-center group-hover:scale-110 transition-transform duration-500">
                                    <i class="fas fa-file-invoice text-white/10 text-8xl mb-2"></i>
                                    <p class="text-[10px] text-white/20 uppercase font-black tracking-widest">{{ $template->file_type }}</p>
                                </div>
                            @endif
                            <!-- Glass Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#1e293b] via-[#1e293b]/20 to-transparent"></div>
                            
                            <!-- Badges -->
                            <div class="absolute top-4 left-4 z-10">
                                <span class="px-3 py-1.5 bg-gradient-to-r from-amber-400 to-orange-500 text-amber-950 text-[10px] font-black uppercase rounded-lg shadow-xl flex items-center gap-1.5 border border-white/20">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="relative p-6 -mt-12 backdrop-blur-md bg-white/5 border-t border-white/10 mx-4 mb-4 rounded-2xl">
                            <span class="inline-block px-2.5 py-1 bg-violet-500/20 text-violet-300 text-[10px] font-black uppercase rounded-md mb-3 border border-violet-500/30">
                                {{ $template->category }}
                            </span>
                            <h3 class="font-bold text-white mb-2 line-clamp-2 min-h-[48px] leading-tight">{{ $template->title }}</h3>
                            
                            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-4 pt-4 border-t border-white/5">
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-download text-violet-400"></i> {{ number_format($template->download_count) }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-hdd text-blue-400"></i> {{ $template->formatted_file_size }}
                                    </span>
                                </div>
                                <span class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center text-white group-hover:bg-violet-500 transition-colors">
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="bg-white/70 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-slate-200/50 border border-white p-4 mb-12 sticky top-4 z-40">
        <form method="GET" class="flex flex-col lg:flex-row gap-3">
            <div class="flex-1 relative group">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-violet-500 transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="ค้นหาเอกสารตัวอย่าง..." 
                       class="w-full pl-14 pr-4 py-4 rounded-2xl border-0 bg-slate-100/50 focus:bg-white focus:ring-4 focus:ring-violet-500/10 transition-all text-slate-700 placeholder:text-slate-400">
            </div>
            <div class="w-full lg:w-72 relative">
                <i class="fas fa-th-large absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <select name="category" class="w-full pl-14 pr-10 py-4 rounded-2xl border-0 bg-slate-100/50 focus:bg-white focus:ring-4 focus:ring-violet-500/10 transition-all text-slate-700 appearance-none">
                    <option value="">ทุกหมวดหมู่</option>
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
            </div>
            <button type="submit" class="px-8 py-4 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-2xl shadow-lg shadow-violet-200 hover:shadow-violet-300 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-3 min-w-[140px]">
                <i class="fas fa-search text-sm"></i>
                <span>ค้นหา</span>
            </button>
            @if(request('search') || request('category'))
                <a href="{{ route('typing.student.templates.index') }}" class="px-6 py-4 bg-slate-200 hover:bg-slate-300 text-slate-600 font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- All Templates -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-1 flex items-center gap-2">
            <span class="w-1.5 h-6 bg-gradient-to-b from-violet-500 to-purple-600 rounded-full"></span>
            เอกสารตัวอย่างทั้งหมด
        </h2>
        <p class="text-sm text-gray-500 ml-4">รวมเอกสารตัวอย่าง {{ $templates->total() }} รายการ</p>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @forelse($templates as $template)
            <a href="{{ route('typing.student.templates.show', $template) }}" 
               class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:border-violet-200 hover:-translate-y-1 transition-all duration-300">
                <!-- Thumbnail -->
                <div class="relative h-48 bg-slate-50 flex items-center justify-center overflow-hidden">
                            @if($template->thumbnail)
                                <img src="{{ asset('uploads/' . $template->thumbnail) }}" alt="{{ $template->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                        <div class="text-center group-hover:scale-110 transition-transform duration-500">
                            @php
                                $iconClass = match(strtolower($template->file_type)) {
                                    'pdf' => 'fa-file-pdf text-red-500/20',
                                    'doc', 'docx' => 'fa-file-word text-blue-500/20',
                                    default => 'fa-file text-slate-300'
                                };
                            @endphp
                            <i class="fas {{ $iconClass }} text-7xl mb-2"></i>
                            <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest">{{ $template->file_type }}</p>
                        </div>
                    @endif

                    <!-- Glass Overlays -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <!-- Featured Badge -->
                    @if($template->is_featured)
                        <div class="absolute top-4 left-4">
                            <span class="px-2.5 py-1.5 bg-amber-400 text-amber-950 text-[10px] font-black uppercase rounded-lg shadow-lg flex items-center gap-1 backdrop-blur-md border border-amber-300/50">
                                <i class="fas fa-star"></i> Featured
                            </span>
                        </div>
                    @endif

                    <!-- Category Badge -->
                    <div class="absolute top-4 right-4 translate-y-[-10px] opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                        <span class="px-2.5 py-1.5 bg-white/20 backdrop-blur-md text-white text-[10px] font-bold uppercase rounded-lg border border-white/30">
                            {{ $template->category }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-5">
                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-violet-600 transition-colors min-h-[48px]">
                        {{ $template->title }}
                    </h3>
                    
                    @if($template->description)
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $template->description }}</p>
                    @endif

                    <!-- Stats -->
                    <div class="flex items-center justify-between text-xs text-gray-400 pt-3 border-t border-gray-100">
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
                </div>
            </a>
        @empty
            <div class="col-span-full">
                <div class="text-center py-20 bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 shadow-inner">
                        <i class="fas fa-folder-open text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">ไม่พบเอกสารที่ค้นหา</h3>
                    <p class="text-slate-400 max-w-sm mx-auto">{{ request('search') || request('category') ? 'ลองเปลี่ยนชื่อเอกสารหรือหมวดหมู่ในการค้นหาอีกครั้ง' : 'ขณะนี้ยังไม่มีเอกสารตัวอย่างให้ดาวน์โหลด' }}</p>
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

    <!-- Tips Section -->
    <div class="mt-12 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-violet-500 opacity-10 rounded-full -mr-20 -mt-20 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-cyan-500 opacity-10 rounded-full -ml-10 -mb-10 blur-xl"></div>
        
        <div class="relative z-10">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-violet-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-lightbulb text-amber-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg mb-2">เคล็ดลับการศึกษาเอกสารตัวอย่าง</h3>
                    <ul class="text-slate-300 space-y-2 text-sm">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-green-400 mt-0.5"></i>
                            สังเกตรูปแบบการจัดหน้า ระยะขอบ และการจัดวางองค์ประกอบต่างๆ
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-green-400 mt-0.5"></i>
                            ศึกษาการใช้ภาษา รูปแบบการเขียนที่เป็นทางการ
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-green-400 mt-0.5"></i>
                            สังเกตลำดับขั้นตอนและโครงสร้างของเอกสารแต่ละประเภท
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</x-typing-app>
