@php
    $currentRole = $role ?? 'student';
    $isAdmin = $currentRole === 'admin';
    
    $sections = $isAdmin ? [
        'เมนูหลัก' => [
            ['route' => 'typing.admin.dashboard', 'icon' => 'fa-home', 'label' => 'แดชบอร์ด'],
            ['url' => '/typing/admin/students', 'icon' => 'fa-user-graduate', 'label' => 'จัดการนักเรียน'],
            ['url' => '/typing/admin/assignments', 'icon' => 'fa-tasks', 'label' => 'จัดการงาน'],
            ['url' => '/typing/admin/submissions', 'icon' => 'fa-file-upload', 'label' => 'ตรวจงานที่ส่ง'],
            ['url' => '/typing/tournaments', 'icon' => 'fa-sitemap', 'label' => 'การแข่งขัน'],
        ],
        'แหล่งเรียนรู้' => [
            ['url' => '/typing/admin/templates', 'icon' => 'fa-folder-open', 'label' => 'คลังเอกสารตัวอย่าง'],
        ],
        'รายงาน & สถิติ' => [
            ['url' => '/typing/admin/grades', 'icon' => 'fa-chart-bar', 'label' => 'ตารางคะแนน'],
            ['url' => '/typing/leaderboard', 'icon' => 'fa-trophy', 'label' => 'บอร์ดผู้นำ'],
        ]
    ] : [
        'เมนูหลัก' => [
            ['url' => '/typing/student/dashboard', 'icon' => 'fa-home', 'label' => 'แดชบอร์ด'],
            ['url' => '/typing/student/assignments', 'icon' => 'fa-clipboard-list', 'label' => 'งานที่ได้รับ'],
            ['url' => '/typing/student/submissions', 'icon' => 'fa-paper-plane', 'label' => 'งานที่ส่งแล้ว'],
            ['url' => '/typing/student/matches', 'icon' => 'fa-gamepad', 'label' => '1v1 แข่งพิมพ์งาน'],
            ['url' => '/typing/tournaments', 'icon' => 'fa-sitemap', 'label' => 'การแข่งขัน'],
        ],
        'แหล่งเรียนรู้' => [
            ['url' => '/typing/student/templates', 'icon' => 'fa-folder-open', 'label' => 'คลังเอกสารตัวอย่าง'],
        ],
        'ผลการเรียน' => [
            ['url' => '/typing/student/grades', 'icon' => 'fa-star', 'label' => 'คะแนนของฉัน'],
            ['url' => '/typing/leaderboard', 'icon' => 'fa-trophy', 'label' => 'บอร์ดผู้นำ'],
        ],
        'กิจกรรม & ร้านค้า' => [
            ['url' => '/typing/shop', 'icon' => 'fa-store', 'label' => 'ร้านค้าแลกรางวัล', 'badge' => number_format(auth()->user()->points ?? 0), 'badgeColor' => 'bg-amber-100 text-amber-600'],
            ['url' => '/typing/shop/my-rewards', 'icon' => 'fa-box-open', 'label' => 'รางวัลของฉัน'],
        ]
    ];
@endphp

<div class="h-full flex flex-col bg-white border-r border-gray-100 shadow-2xl relative overflow-hidden">
    <!-- Aurora Background Accents -->
    <div class="absolute top-[-10%] left-[-20%] w-[200px] h-[200px] bg-primary-500/5 rounded-full blur-[60px] pointer-events-none"></div>
    <div class="absolute bottom-[-5%] right-[-10%] w-[150px] h-[150px] bg-indigo-500/5 rounded-full blur-[60px] pointer-events-none"></div>

    <!-- Logo Section (Premium) -->
    <div class="relative z-10 p-8 border-b border-gray-100/50">
        <a href="{{ url('/typing') }}" class="group flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-indigo-600 flex items-center justify-center shadow-xl shadow-primary-500/20 transform group-hover:rotate-6 transition-all duration-500">
                <i class="fas fa-file-invoice text-white text-xl"></i>
            </div>
            <div>
                <h1 class="font-black text-gray-800 text-lg leading-tight tracking-tight">GOV TYPING</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">ระบบหลัก</p>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="relative z-10 flex-1 px-4 py-8 space-y-8 overflow-y-auto scrollbar-hide">
        @foreach($sections as $title => $items)
            <div class="space-y-3">
                <p class="px-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ $title }}</p>
                <div class="space-y-1">
                    @foreach($items as $item)
                        @php
                            $isActive = isset($item['route']) 
                                ? request()->routeIs($item['route']) 
                                : (isset($item['url']) && request()->is(ltrim($item['url'], '/') . '*'));
                            
                            $href = isset($item['route']) ? route($item['route']) : url($item['url']);
                        @endphp
                        <a href="{{ $href }}"
                            class="group flex items-center gap-4 px-5 py-3.5 rounded-2xl transition-all duration-300 relative overflow-hidden
                            {{ $isActive 
                                ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/20 translate-x-1' 
                                : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 hover:translate-x-1' }}">
                            
                            @if($isActive)
                                <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 translate-x-[-100%] animate-[shimmer_2s_infinite]"></div>
                            @endif

                            <i class="fas {{ $item['icon'] }} w-5 text-center transition-transform group-hover:scale-110 {{ $isActive ? 'text-white' : 'text-gray-400 group-hover:text-primary-500' }}"></i>
                            <span class="font-bold text-sm tracking-tight">{{ $item['label'] }}</span>

                            @if(isset($item['badge']))
                                <span class="ml-auto text-[10px] px-2 py-0.5 rounded-lg font-black {{ $item['badgeColor'] ?? 'bg-white/20 text-white' }}">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <!-- User Profile Section (Premium Bento) -->
    <div class="relative z-10 p-6">
        <div class="p-4 rounded-[2rem] bg-gray-50/50 backdrop-blur-md border border-gray-100 shadow-sm group hover:bg-white hover:shadow-xl transition-all duration-500">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-2xl object-cover ring-4 ring-white shadow-md group-hover:scale-105 transition-transform">
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white {{ auth()->user()->role === 'admin' ? 'bg-amber-500' : 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]' }}"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-gray-800 truncate leading-tight group-hover:text-primary-600 transition-colors">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter truncate mt-0.5">
                        {{ auth()->user()->role === 'admin' ? 'ผู้ดูแลระบบ' : 'รหัส: ' . (auth()->user()->student_id ?? '-') }}
                    </p>
                </div>
                <a href="{{ url('/typing/profile') }}" class="w-8 h-8 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-primary-500 hover:shadow-lg transition-all">
                    <i class="fas fa-cog text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>