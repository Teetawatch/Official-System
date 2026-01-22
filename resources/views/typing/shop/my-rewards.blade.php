<x-typing-app :role="auth()->user()->role" :title="'รางวัลของฉัน - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <i class="fas fa-box-open text-primary-500 mr-2"></i>
                    รางวัลของฉัน
                </h1>
                <p class="text-gray-500 mt-1">จัดการและติดตั้งรางวัลที่คุณได้รับ</p>
            </div>
            
            <a href="{{ route('typing.shop.index') }}" class="btn-primary">
                <i class="fas fa-store mr-2"></i>
                ไปร้านค้า
            </a>
        </div>
    </div>
    
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-secondary-50 border border-secondary-200 text-secondary-700 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-secondary-100 flex items-center justify-center">
                <i class="fas fa-check-circle text-secondary-500 text-xl"></i>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    <!-- Currently Equipped Preview -->
    <div class="card mb-8 bg-gradient-to-br from-gray-50 to-white">
        <h2 class="text-lg font-bold text-gray-800 mb-6">
            <i class="fas fa-user-gear text-primary-500 mr-2"></i>
            ไอเทมที่กำลังใช้งาน
        </h2>
        
        <div class="flex flex-col md:flex-row items-center gap-8">
            <!-- Avatar Preview with Frame -->
            <div class="relative flex-shrink-0">
                @php
                    $frameItem = $user->equipped_frame_item;
                    $frameGradient = $frameItem && isset($frameItem->data['gradient']) 
                        ? $frameItem->data['gradient'] 
                        : 'from-gray-300 to-gray-400';
                @endphp
                
                <div class="w-36 h-36 rounded-full bg-gradient-to-br {{ $frameGradient }} p-1.5 shadow-xl">
                    <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-full h-full rounded-full object-cover border-4 border-white">
                </div>
                
                @if($frameItem && isset($frameItem->data['icon']))
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg border-2 border-gray-100">
                        <span class="text-xl">{{ $frameItem->data['icon'] }}</span>
                    </div>
                @endif
            </div>
            
            <!-- Info -->
            <div class="text-center md:text-left flex-1">
                <!-- Title -->
                @php
                    $titleItem = $user->equipped_title_item;
                @endphp
                @if($titleItem)
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r {{ $titleItem->rarity_color }} text-white font-bold text-sm shadow-lg mb-3">
                        <span>{{ $titleItem->data['emoji'] ?? '🏆' }}</span>
                        <span>{{ $titleItem->name }}</span>
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-200 text-gray-500 text-sm mb-3">
                        <i class="fas fa-crown"></i>
                        <span>ยังไม่ได้ตั้งตำแหน่ง</span>
                    </div>
                @endif
                
                <h3 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h3>
                <p class="text-gray-500">{{ $user->email }}</p>
                
                <!-- Equipped Items List -->
                <div class="mt-4 flex flex-wrap gap-3 justify-center md:justify-start">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg {{ $frameItem ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-circle-user"></i>
                        <span class="text-sm font-medium">{{ $frameItem ? $frameItem->name : 'ไม่มีกรอบ' }}</span>
                    </div>
                    
                    @php $themeItem = $user->equipped_theme_item; @endphp
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg {{ $themeItem ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-palette"></i>
                        <span class="text-sm font-medium">{{ $themeItem ? $themeItem->name : 'ไม่มีธีม' }}</span>
                    </div>
                    
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg {{ $titleItem ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-crown"></i>
                        <span class="text-sm font-medium">{{ $titleItem ? $titleItem->name : 'ไม่มีตำแหน่ง' }}</span>
                    </div>

                    @php $ncItem = $user->equipped_name_color_item; @endphp
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg {{ $ncItem ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-signature"></i>
                        <span class="text-sm font-medium">{{ $ncItem ? $ncItem->name : 'ไม่มีสีชื่อ' }}</span>
                    </div>

                    @php $pbgItem = $user->equipped_profile_bg_item; @endphp
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg {{ $pbgItem ? 'bg-cyan-100 text-cyan-700' : 'bg-gray-100 text-gray-500' }}">
                        <i class="fas fa-id-card"></i>
                        <span class="text-sm font-medium">{{ $pbgItem ? $pbgItem->name : 'ไม่มีการ์ด' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Rewards by Type -->
    @php
        $types = [
            'avatar_frame' => ['name' => 'กรอบอวาตาร์', 'icon' => 'fa-circle-user', 'color' => 'purple'],
            'theme' => ['name' => 'ธีมโปรไฟล์', 'icon' => 'fa-palette', 'color' => 'pink'],
            'title' => ['name' => 'ตำแหน่งพิเศษ', 'icon' => 'fa-crown', 'color' => 'amber'],
            'name_color' => ['name' => 'สีชื่อ', 'icon' => 'fa-signature', 'color' => 'indigo'],
            'profile_bg' => ['name' => 'พื้นหลังการ์ด', 'icon' => 'fa-id-card', 'color' => 'cyan'],
        ];
    @endphp
    
    @foreach($types as $typeKey => $typeInfo)
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-{{ $typeInfo['color'] }}-100 flex items-center justify-center">
                    <i class="fas {{ $typeInfo['icon'] }} text-{{ $typeInfo['color'] }}-600"></i>
                </div>
                {{ $typeInfo['name'] }}
                <span class="text-sm font-normal text-gray-400 ml-2">({{ $grouped[$typeKey]->count() }} ชิ้น)</span>
            </h2>
            
            @if($grouped[$typeKey]->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($grouped[$typeKey] as $userReward)
                        @php
                            $item = $userReward->rewardItem;
                            $isEquipped = $userReward->is_equipped;
                        @endphp
                        
                        <div class="group relative bg-white rounded-2xl border {{ $isEquipped ? 'border-secondary-400 ring-2 ring-secondary-200' : 'border-gray-100' }} shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                            <!-- Equipped Badge -->
                            @if($isEquipped)
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="px-3 py-1 rounded-full bg-secondary-500 text-white text-xs font-bold shadow">
                                        <i class="fas fa-check mr-1"></i> กำลังใช้
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Item Preview -->
                            <div class="relative p-6 bg-gradient-to-br {{ $item->rarity_color }} bg-opacity-10">
                                <!-- Rarity Badge -->
                                <div class="absolute top-3 left-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->rarity_badge }}">
                                        {{ $item->rarity_name }}
                                    </span>
                                </div>
                                
                                <!-- Item Visual -->
                                <div class="h-24 flex items-center justify-center mt-4">
                                    @if($item->type === 'avatar_frame')
                                        <div class="w-20 h-20 rounded-full bg-gradient-to-br {{ $item->rarity_color }} p-1 shadow-lg">
                                            <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                                <i class="fas fa-user text-3xl text-gray-300"></i>
                                            </div>
                                        </div>
                                    @elseif($item->type === 'theme')
                                        <div class="w-full h-20 rounded-xl bg-gradient-to-br {{ $item->data['gradient'] ?? 'from-gray-100 to-gray-200' }} shadow-inner flex items-center justify-center">
                                            <i class="fas fa-palette text-2xl text-white/80"></i>
                                        </div>
                                    @elseif($item->type === 'title')
                                        <div class="text-center">
                                            <p class="text-2xl mb-1">{{ $item->data['emoji'] ?? '🏆' }}</p>
                                            <p class="px-3 py-1.5 rounded-full bg-gradient-to-r {{ $item->rarity_color }} text-white font-bold text-xs shadow-lg">
                                                {{ $item->name }}
                                            </p>
                                        </div>
                                    @elseif($item->type === 'name_color')
                                        <div class="text-center w-full">
                                            <p class="text-lg font-bold {{ $item->data['class'] ?? 'text-gray-800' }}">ตัวอย่างชื่อ</p>
                                            <p class="text-xs text-gray-400 mt-1">แสดงผลใน Leaderboard</p>
                                        </div>
                                    @elseif($item->type === 'profile_bg')
                                        <div class="w-full h-20 rounded-xl {{ $item->data['class'] ?? 'bg-white' }} shadow-sm flex items-center justify-center border border-gray-100 p-2 relative overflow-hidden">
                                            <div class="absolute inset-0 bg-white/10 skew-y-12"></div>
                                            <div class="text-center relative z-10 p-2 bg-white/30 backdrop-blur-[1px] rounded-lg">
                                                <i class="fas fa-user-circle text-2xl text-gray-800/50"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Item Info -->
                            <div class="p-4">
                                <h3 class="font-bold text-gray-800 mb-1">{{ $item->name }}</h3>
                                <p class="text-xs text-gray-400 mb-3">
                                    <i class="fas fa-calendar mr-1"></i>
                                    ซื้อเมื่อ {{ $userReward->purchased_at->format('d/m/Y') }}
                                </p>
                                
                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    @if($isEquipped)
                                        <form action="{{ route('typing.shop.unequip', $item->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-2 px-4 rounded-xl bg-gray-200 text-gray-700 font-medium hover:bg-gray-300 transition-colors">
                                                <i class="fas fa-times mr-1"></i>
                                                ถอด
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('typing.shop.equip', $item->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-2 px-4 rounded-xl bg-primary-500 text-white font-medium hover:bg-primary-600 transition-colors">
                                                <i class="fas fa-check mr-1"></i>
                                                ใช้งาน
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card text-center py-8 bg-gray-50">
                    <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                        <i class="fas {{ $typeInfo['icon'] }} text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500">คุณยังไม่มี{{ $typeInfo['name'] }}</p>
                    <a href="{{ route('typing.shop.index', ['type' => $typeKey]) }}" class="btn-outline text-sm mt-3">
                        <i class="fas fa-store mr-1"></i>
                        ไปซื้อที่ร้านค้า
                    </a>
                </div>
            @endif
        </div>
    @endforeach
    
    <!-- Empty State -->
    @if($rewards->count() === 0)
        <div class="card text-center py-16">
            <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-6">
                <i class="fas fa-gift text-5xl text-gray-300"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">ยังไม่มีรางวัล</h3>
            <p class="text-gray-500 mb-6">ไปร้านค้าเพื่อซื้อไอเทมพิเศษสำหรับโปรไฟล์ของคุณ!</p>
            <a href="{{ route('typing.shop.index') }}" class="btn-primary">
                <i class="fas fa-store mr-2"></i>
                ไปร้านค้าเลย
            </a>
        </div>
    @endif

</x-typing-app>
