<x-typing-app :role="auth()->user()->role" :title="'ร้านค้าแลกของรางวัล - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <i class="fas fa-store text-primary-500 mr-2"></i>
                    ร้านค้าแลกของรางวัล
                </h1>
                <p class="text-gray-500 mt-1">ใช้คะแนนแลกไอเทมพิเศษสำหรับโปรไฟล์ของคุณ</p>
            </div>
            
            <!-- User Points Display -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 px-5 py-3 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-2xl shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow-lg">
                        <i class="fas fa-coins text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-amber-600 font-medium uppercase tracking-wider">คะแนนของฉัน</p>
                        <p class="text-2xl font-bold text-amber-700">{{ number_format($user->points) }}</p>
                    </div>
                </div>
                <a href="{{ route('typing.shop.my-rewards') }}" class="btn-outline">
                    <i class="fas fa-box-open mr-2"></i>
                    รางวัลของฉัน
                </a>
            </div>
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
    
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
            </div>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <a href="{{ route('typing.shop.index') }}" class="card text-center group hover:shadow-lg transition-all {{ $type === 'all' ? 'ring-2 ring-primary-500 bg-primary-50' : '' }}">
            <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <i class="fas fa-th-large text-white text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_items'] }}</p>
            <p class="text-sm text-gray-500">ทั้งหมด</p>
        </a>
        
        <a href="{{ route('typing.shop.index', ['type' => 'avatar_frame']) }}" class="card text-center group hover:shadow-lg transition-all {{ $type === 'avatar_frame' ? 'ring-2 ring-purple-500 bg-purple-50' : '' }}">
            <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <i class="fas fa-circle-user text-white text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['avatar_frames'] }}</p>
            <p class="text-sm text-gray-500">กรอบอวาตาร์</p>
        </a>
        
        <a href="{{ route('typing.shop.index', ['type' => 'theme']) }}" class="card text-center group hover:shadow-lg transition-all {{ $type === 'theme' ? 'ring-2 ring-pink-500 bg-pink-50' : '' }}">
            <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <i class="fas fa-palette text-white text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['themes'] }}</p>
            <p class="text-sm text-gray-500">ธีมโปรไฟล์</p>
        </a>
        
        <a href="{{ route('typing.shop.index', ['type' => 'title']) }}" class="card text-center group hover:shadow-lg transition-all {{ $type === 'title' ? 'ring-2 ring-amber-500 bg-amber-50' : '' }}">
            <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <i class="fas fa-crown text-white text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['titles'] }}</p>
            <p class="text-sm text-gray-500">ตำแหน่งพิเศษ</p>
        </a>
        
        <a href="{{ route('typing.shop.my-rewards') }}" class="card text-center group hover:shadow-lg transition-all">
            <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-secondary-400 to-secondary-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <i class="fas fa-box-open text-white text-lg"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['owned'] }}</p>
            <p class="text-sm text-gray-500">ที่ฉันมี</p>
        </a>
    </div>
    
    <!-- Items Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($items as $item)
            @php
                $isOwned = in_array($item->id, $ownedItemIds);
                $canAfford = $user->points >= $item->price;
            @endphp
            
            <div class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden {{ $isOwned ? 'ring-2 ring-secondary-400' : '' }}">
                <!-- Rarity Glow Effect -->
                @if($item->rarity === 'legendary')
                    <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/20 via-transparent to-orange-400/20 pointer-events-none"></div>
                @elseif($item->rarity === 'epic')
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-400/20 via-transparent to-violet-400/20 pointer-events-none"></div>
                @elseif($item->rarity === 'rare')
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400/20 via-transparent to-cyan-400/20 pointer-events-none"></div>
                @endif
                
                <!-- Item Preview -->
                <div class="relative p-6 bg-gradient-to-br {{ $item->rarity_color }} bg-opacity-10">
                    <!-- Rarity Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->rarity_badge }}">
                            {{ $item->rarity_name }}
                        </span>
                    </div>
                    
                    <!-- Type Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-white/80 text-gray-600 backdrop-blur-sm">
                            <i class="fas {{ $item->type_icon }} mr-1"></i>
                            {{ $item->type_name }}
                        </span>
                    </div>
                    
                    <!-- Item Visual -->
                    <div class="h-32 flex items-center justify-center mt-4">
                        @if($item->type === 'avatar_frame')
                            <!-- Avatar Frame Preview -->
                            <div class="relative">
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br {{ $item->rarity_color }} p-1 shadow-lg animate-pulse-slow">
                                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                                        <i class="fas fa-user text-4xl text-gray-300"></i>
                                    </div>
                                </div>
                                @if($item->data && isset($item->data['icon']))
                                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg">
                                        <span class="text-lg">{{ $item->data['icon'] }}</span>
                                    </div>
                                @endif
                            </div>
                        @elseif($item->type === 'theme')
                            <!-- Theme Preview -->
                            <div class="w-full h-28 rounded-xl bg-gradient-to-br {{ $item->data['gradient'] ?? 'from-gray-100 to-gray-200' }} shadow-inner flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-palette text-3xl text-white/80"></i>
                                    <p class="text-xs text-white/70 mt-1">ธีมโปรไฟล์</p>
                                </div>
                            </div>
                        @elseif($item->type === 'title')
                            <!-- Title Preview -->
                            <div class="text-center">
                                <p class="text-3xl mb-2">{{ $item->data['emoji'] ?? '🏆' }}</p>
                                <p class="px-4 py-2 rounded-full bg-gradient-to-r {{ $item->rarity_color }} text-white font-bold text-sm shadow-lg">
                                    {{ $item->name }}
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Owned Badge -->
                    @if($isOwned)
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2">
                            <span class="px-4 py-1.5 rounded-full bg-secondary-500 text-white text-xs font-bold shadow-lg">
                                <i class="fas fa-check mr-1"></i> มีแล้ว
                            </span>
                        </div>
                    @endif
                </div>
                
                <!-- Item Info -->
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-lg mb-1 group-hover:text-primary-600 transition-colors">
                        {{ $item->name }}
                    </h3>
                    @if($item->description)
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $item->description }}</p>
                    @endif
                    
                    <!-- Price & Action -->
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                                <i class="fas fa-coins text-amber-500"></i>
                            </div>
                            <span class="font-bold text-lg {{ $canAfford ? 'text-gray-800' : 'text-red-500' }}">
                                {{ number_format($item->price) }}
                            </span>
                        </div>
                        
                        @if($isOwned)
                            <a href="{{ route('typing.shop.my-rewards') }}" class="btn-outline text-sm py-2">
                                <i class="fas fa-box-open mr-1"></i>
                                ดูในกล่อง
                            </a>
                        @elseif(!$item->isInStock())
                            <button disabled class="px-4 py-2 rounded-xl bg-gray-200 text-gray-500 text-sm font-medium cursor-not-allowed">
                                หมดสต็อก
                            </button>
                        @elseif(!$canAfford)
                            <button disabled class="px-4 py-2 rounded-xl bg-gray-200 text-gray-500 text-sm font-medium cursor-not-allowed">
                                <i class="fas fa-lock mr-1"></i>
                                คะแนนไม่พอ
                            </button>
                        @else
                            <form action="{{ route('typing.shop.purchase', $item->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('ยืนยันซื้อ {{ $item->name }} ในราคา {{ number_format($item->price) }} คะแนน?')">
                                @csrf
                                <button type="submit" class="btn-primary text-sm py-2">
                                    <i class="fas fa-shopping-cart mr-1"></i>
                                    ซื้อเลย
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    <!-- Stock Info -->
                    @if($item->stock !== null && !$isOwned)
                        <div class="mt-3 text-center">
                            <span class="text-xs {{ $item->stock <= 5 ? 'text-red-500' : 'text-gray-400' }}">
                                <i class="fas fa-box mr-1"></i>
                                เหลือ {{ $item->stock }} ชิ้น
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="card text-center py-16">
                    <div class="w-24 h-24 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <i class="fas fa-store-slash text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">ไม่พบไอเทม</h3>
                    <p class="text-gray-500">ยังไม่มีไอเทมในหมวดหมู่นี้</p>
                    <a href="{{ route('typing.shop.index') }}" class="btn-primary mt-4">
                        <i class="fas fa-arrow-left mr-2"></i>
                        ดูไอเทมทั้งหมด
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- How to earn points -->
    <div class="mt-12 card bg-gradient-to-br from-primary-50 to-blue-50 border-primary-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-lightbulb text-amber-500 mr-2"></i>
            วิธีหาคะแนน
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center gap-3 p-4 bg-white rounded-xl">
                <div class="w-12 h-12 rounded-xl bg-secondary-100 flex items-center justify-center">
                    <i class="fas fa-keyboard text-secondary-600 text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">ทำงานพิมพ์</p>
                    <p class="text-sm text-gray-500">ส่งงานและได้คะแนนสะสม</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-4 bg-white rounded-xl">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-gamepad text-amber-600 text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">แข่ง 1v1</p>
                    <p class="text-sm text-gray-500">ชนะการแข่งขันรับคะแนนเพิ่ม</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-4 bg-white rounded-xl">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-fire text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">ทำ Streak</p>
                    <p class="text-sm text-gray-500">พิมพ์ทุกวันต่อเนื่อง</p>
                </div>
            </div>
        </div>
    </div>

</x-typing-app>
