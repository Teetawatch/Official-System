<x-typing-app :role="'student'" :title="$template->title . ' - คลังเอกสารตัวอย่าง'">

    <!-- Breadcrumb -->
    <div class="mb-8">
        <a href="{{ route('typing.student.templates.index') }}"
            class="inline-flex items-center gap-2 text-gray-500 hover:text-violet-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>กลับไปคลังเอกสาร</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Document Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <!-- Header with Thumbnail -->
                <div class="relative h-72 md:h-[400px] bg-slate-50 flex items-center justify-center overflow-hidden">
                    <!-- Dynamic Background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-purple-500/5"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-violet-500/5 rounded-full blur-3xl -mr-32 -mt-32">
                    </div>

                    @if($template->thumbnail)
                        <img src="{{ asset('uploads/' . $template->thumbnail) }}" alt="{{ $template->title }}"
                            class="relative z-10 w-auto h-full max-h-[80%] object-contain drop-shadow-2xl transition-transform duration-700 hover:scale-105">
                    @else
                        <div class="relative z-10 text-center group">
                            @php
                                $iconClass = match (strtolower($template->file_type)) {
                                    'pdf' => 'fa-file-pdf text-red-500/20',
                                    'doc', 'docx' => 'fa-file-word text-blue-500/20',
                                    default => 'fa-file text-slate-300'
                                };
                                $accentColor = match (strtolower($template->file_type)) {
                                    'pdf' => 'text-red-500',
                                    'doc', 'docx' => 'text-blue-500',
                                    default => 'text-slate-500'
                                };
                            @endphp
                            <div class="relative flex items-center justify-center mb-6">
                                <i
                                    class="fas {{ $iconClass }} text-9xl transition-transform duration-500 group-hover:scale-110"></i>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span
                                        class="text-2xl font-black {{ $accentColor }} uppercase tracking-widest opacity-60">{{ $template->file_type }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Badges Overlay -->
                    <div class="absolute top-6 left-6 flex flex-wrap gap-3 z-20">
                        @if($template->is_featured)
                            <span
                                class="px-3.5 py-2 bg-amber-400 text-amber-950 text-xs font-black uppercase rounded-xl shadow-lg border border-amber-300/50 flex items-center gap-2 backdrop-blur-md">
                                <i class="fas fa-star text-[10px]"></i> Featured Template
                            </span>
                        @endif
                        <span
                            class="px-3.5 py-2 bg-white/20 backdrop-blur-md text-slate-700 text-xs font-bold uppercase rounded-xl shadow-sm border border-white/40">
                            {{ $template->category }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">{{ $template->title }}</h1>

                    @if($template->description)
                        <div class="prose max-w-none text-gray-600 mb-6">
                            <p>{{ $template->description }}</p>
                        </div>
                    @endif

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                        <div
                            class="bg-slate-50 rounded-[1.5rem] p-5 text-center border border-slate-100/50 group hover:border-violet-200 hover:bg-violet-50/30 transition-all duration-300">
                            <div
                                class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-download text-violet-500 text-sm"></i>
                            </div>
                            <p class="text-2xl font-black text-slate-800">{{ number_format($template->download_count) }}
                            </p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Downloads</p>
                        </div>
                        <div
                            class="bg-slate-50 rounded-[1.5rem] p-5 text-center border border-slate-100/50 group hover:border-pink-200 hover:bg-pink-50/30 transition-all duration-300">
                            <div
                                class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-eye text-pink-500 text-sm"></i>
                            </div>
                            <p class="text-2xl font-black text-slate-800">{{ number_format($template->view_count) }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Views</p>
                        </div>
                        <div
                            class="bg-slate-50 rounded-[1.5rem] p-5 text-center border border-slate-100/50 group hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-300">
                            <div
                                class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-code text-blue-500 text-sm"></i>
                            </div>
                            <p class="text-2xl font-black text-slate-800">{{ strtoupper($template->file_type) }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Format</p>
                        </div>
                        <div
                            class="bg-slate-50 rounded-[1.5rem] p-5 text-center border border-slate-100/50 group hover:border-indigo-200 hover:bg-indigo-50/30 transition-all duration-300">
                            <div
                                class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mx-auto mb-3 shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fas fa-weight-hanging text-indigo-500 text-sm"></i>
                            </div>
                            <p class="text-2xl font-black text-slate-800">{{ $template->formatted_file_size }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">File Size</p>
                        </div>
                    </div>

                    <!-- Download Button -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('typing.student.templates.download', $template) }}"
                            class="flex-1 py-4 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-3 text-lg">
                            <i class="fas fa-download"></i>
                            <span>ดาวน์โหลดเอกสาร</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- How to Use Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-book-reader text-violet-500"></i>
                    วิธีใช้เอกสารตัวอย่าง
                </h2>

                <div class="space-y-4">
                    <div class="flex gap-4 p-4 bg-violet-50 rounded-xl">
                        <div
                            class="w-10 h-10 rounded-full bg-violet-500 text-white flex items-center justify-center font-bold flex-shrink-0">
                            1</div>
                        <div>
                            <h3 class="font-semibold text-gray-800">ดาวน์โหลดเอกสาร</h3>
                            <p class="text-sm text-gray-600 mt-1">คลิกปุ่มดาวน์โหลดเพื่อบันทึกไฟล์ลงในคอมพิวเตอร์ของคุณ
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4 p-4 bg-purple-50 rounded-xl">
                        <div
                            class="w-10 h-10 rounded-full bg-purple-500 text-white flex items-center justify-center font-bold flex-shrink-0">
                            2</div>
                        <div>
                            <h3 class="font-semibold text-gray-800">ศึกษารูปแบบ</h3>
                            <p class="text-sm text-gray-600 mt-1">เปิดไฟล์และศึกษารูปแบบการจัดหน้า ระยะขอบ
                                ตำแหน่งขององค์ประกอบต่างๆ</p>
                        </div>
                    </div>

                    <div class="flex gap-4 p-4 bg-indigo-50 rounded-xl">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold flex-shrink-0">
                            3</div>
                        <div>
                            <h3 class="font-semibold text-gray-800">ฝึกปฏิบัติ</h3>
                            <p class="text-sm text-gray-600 mt-1">ลองพิมพ์ตามรูปแบบของเอกสารตัวอย่าง
                                เพื่อฝึกทักษะการพิมพ์หนังสือราชการ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- File Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-alt text-violet-500"></i>
                    ข้อมูลไฟล์
                </h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500 text-sm">ชื่อไฟล์</span>
                        <span
                            class="text-gray-800 font-medium text-sm text-right max-w-[180px] truncate">{{ $template->file_name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500 text-sm">ประเภท</span>
                        <span class="text-gray-800 font-medium">{{ strtoupper($template->file_type) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500 text-sm">ขนาด</span>
                        <span class="text-gray-800 font-medium">{{ $template->formatted_file_size }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500 text-sm">หมวดหมู่</span>
                        <span
                            class="px-2 py-1 bg-violet-100 text-violet-700 text-xs font-semibold rounded">{{ $template->category }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-gray-500 text-sm">อัปโหลดเมื่อ</span>
                        <span
                            class="text-gray-800 font-medium text-sm">{{ $template->created_at->locale('th')->isoFormat('D MMM YYYY') }}</span>
                    </div>
                </div>
            </div>

            <!-- Related Templates -->
            @if($relatedTemplates->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-folder text-violet-500"></i>
                        เอกสารที่เกี่ยวข้อง
                    </h3>

                    <div class="space-y-3">
                        @foreach($relatedTemplates as $related)
                            <a href="{{ route('typing.student.templates.show', $related) }}"
                                class="flex items-center gap-3 p-3 rounded-xl hover:bg-violet-50 transition-colors group">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    @php
                                        $relatedIcon = match (strtolower($related->file_type)) {
                                            'pdf' => 'fa-file-pdf text-red-400',
                                            'doc', 'docx' => 'fa-file-word text-blue-400',
                                            default => 'fa-file text-gray-400'
                                        };
                                    @endphp
                                    <i class="fas {{ $relatedIcon }}"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-medium text-gray-700 group-hover:text-violet-600 transition-colors truncate">
                                        {{ $related->title }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $related->formatted_file_size }}</p>
                                </div>
                                <i
                                    class="fas fa-chevron-right text-xs text-gray-300 group-hover:text-violet-400 transition-colors"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Download -->
            <div class="bg-gradient-to-br from-violet-600 to-purple-700 rounded-2xl p-6 text-white">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-download text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg">ดาวน์โหลดเอกสาร</h3>
                    <p class="text-violet-200 text-sm mt-1">{{ $template->file_name }}</p>
                </div>
                <a href="{{ route('typing.student.templates.download', $template) }}"
                    class="block w-full py-3 bg-white text-violet-700 font-bold rounded-xl hover:bg-violet-50 transition-colors text-center">
                    <i class="fas fa-download mr-2"></i> ดาวน์โหลด
                </a>
            </div>
        </div>
    </div>

</x-typing-app>