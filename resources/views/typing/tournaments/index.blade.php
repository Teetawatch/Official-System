<x-typing-app :role="auth()->user()->role" :title="'การแข่งขัน - ระบบวิชาพิมพ์หนังสือราชการ 1'">

    <!-- Page Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                <i class="fas fa-trophy text-amber-500 mr-2"></i>
                🏆 การแข่งขัน
            </h1>
            <p class="text-gray-500 mt-1">ร่วมแข่งขันพิมพ์ดีดกับผู้เล่นคนอื่นๆ</p>
        </div>

        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'teacher')
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'teacher')
            <div class="flex gap-2">
                <a href="{{ route('typing.tournaments.create', ['type' => 'bracket']) }}" class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-sitemap"></i>
                    สร้าง Bracket
                </a>
                <a href="{{ route('typing.tournaments.create', ['type' => 'class_battle']) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl shadow-lg transition-all inline-flex items-center gap-2">
                    <i class="fas fa-users-class"></i>
                    สร้างห้อง Classroom
                </a>
            </div>
        @endif
        @endif
    </div>

    <!-- Tournament Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tournaments as $tournament)
            <div class="card group hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-800 group-hover:text-primary-600 transition-colors">
                                {{ $tournament->name }}
                            </h2>
                            <p class="text-gray-500 text-sm mt-1 line-clamp-2">
                                {{ $tournament->description }}
                            </p>
                            @if($tournament->type === 'class_battle')
                                <span class="inline-block mt-2 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide bg-indigo-100 text-indigo-700 rounded-md">Classroom Battle</span>
                            @else
                                <span class="inline-block mt-2 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide bg-amber-100 text-amber-700 rounded-md">Bracket Tournament</span>
                            @endif
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                @if($tournament->status === 'open')
                                    bg-green-100 text-green-800
                                @elseif($tournament->status === 'ongoing')
                                    bg-blue-100 text-blue-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif
                            ">
                            @if($tournament->status === 'open')
                                <i class="fas fa-door-open mr-1"></i> เปิดรับสมัคร
                            @elseif($tournament->status === 'ongoing')
                                <i class="fas fa-play mr-1"></i> กำลังแข่งขัน
                            @else
                                <i class="fas fa-flag-checkered mr-1"></i> จบแล้ว
                            @endif
                        </span>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4 pb-4 border-b border-gray-100">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-users text-primary-500"></i>
                            <span class="font-medium text-gray-700">{{ $tournament->participants_count }}</span> /
                            {{ $tournament->max_participants }} คน
                        </span>
                        <span class="flex items-center gap-2">
                            <i class="fas fa-calendar text-secondary-500"></i>
                            {{ $tournament->created_at->format('d M Y') }}
                        </span>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $progress = $tournament->max_participants > 0 ? ($tournament->participants_count / $tournament->max_participants) * 100 : 0;
                    @endphp
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>ผู้เข้าร่วม</span>
                            <span>{{ number_format($progress, 0) }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-to-r from-primary-500 to-secondary-500"
                                style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('typing.tournaments.show', $tournament->id) }}"
                        class="btn-secondary w-full text-center flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i>
                        ดูข้อมูล
                    </a>
                    
                    @if((Auth::user()->role === 'admin' || Auth::user()->role === 'teacher') && $tournament->status !== 'completed')
                         <div class="mt-2 flex gap-2">
                              @if($tournament->status === 'open')
                                <form action="{{ route('typing.tournaments.start', $tournament->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-50 text-green-600 hover:bg-green-100 font-bold py-2 px-4 rounded-lg text-sm transition-colors border border-green-200">
                                        <i class="fas fa-play mr-1"></i> เริ่มทันที
                                    </button>
                                </form>
                              @endif
                            <form action="{{ route('typing.tournaments.destroy', $tournament->id) }}" method="POST" onsubmit="return confirm('ยืนยันลบการแข่งขันนี้? ข้อมูลทั้งหมดจะหายไป');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 font-bold py-2 px-4 rounded-lg text-sm transition-colors border border-red-200" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                         </div>
                    @elseif((Auth::user()->role === 'admin' || Auth::user()->role === 'teacher'))
                        <form action="{{ route('typing.tournaments.destroy', $tournament->id) }}" method="POST" onsubmit="return confirm('ยืนยันลบการแข่งขันนี้? ข้อมูลทั้งหมดจะหายไป');" class="mt-2 text-center">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-500 text-sm underline">
                                ลบประวัติ
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="card text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trophy text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">ยังไม่มีการแข่งขัน</h3>
                    <p class="text-gray-500">รอติดตามการแข่งขันครั้งถัดไปได้เลย!</p>
                </div>
            </div>
        @endforelse
    </div>

</x-typing-app>