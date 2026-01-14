<header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-100">
    <div class="flex items-center justify-between px-4 py-3 md:px-6">
        <!-- Left: Menu Toggle & Breadcrumb -->
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Toggle -->
            <button 
                @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors"
            >
                <i class="fas fa-bars text-lg"></i>
            </button>
            
            <!-- Breadcrumb -->
            <nav class="hidden md:flex items-center gap-2 text-sm">
                <a href="{{ url('/typing') }}" class="text-gray-400 hover:text-primary-600 transition-colors">
                    <i class="fas fa-home"></i>
                </a>
                <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                <span class="text-gray-600">{{ $pageTitle ?? 'แดชบอร์ด' }}</span>
            </nav>
        </div>
        
        <!-- Right: Actions -->
        <div class="flex items-center gap-2 md:gap-4">
            <!-- Search (Desktop) -->
            <div class="hidden md:block relative">
                <input 
                    type="text" 
                    placeholder="ค้นหา..." 
                    class="w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-200 bg-gray-50 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:bg-white transition-all outline-none"
                >
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            
            <!-- Mobile Search -->
            <button class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <i class="fas fa-search"></i>
            </button>
            
            <!-- Notifications -->
            <div x-data="{ open: false }" class="relative">
                <button 
                    @click="open = !open"
                    class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors"
                >
                    <i class="fas fa-bell"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    @endif
                </button>
                
                <!-- Dropdown -->
                <div 
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 py-2 overflow-hidden z-50"
                    x-cloak
                >
                    <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">การแจ้งเตือน</h3>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <a href="{{ route('typing.notifications.readAll') }}" class="text-xs text-primary-600 hover:text-primary-700">อ่านทั้งหมด</a>
                        @endif
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <a href="{{ route('typing.notifications.read', $notification->id) }}" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 relative group">
                                <div class="w-8 h-8 rounded-full {{ $notification->data['color'] ?? 'bg-gray-100 text-gray-500' }} flex items-center justify-center flex-shrink-0">
                                    <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="w-2 h-2 bg-primary-500 rounded-full mt-1.5 flex-shrink-0"></div>
                            </a>
                        @empty
                            <div class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-bell-slash text-2xl mb-2 text-gray-300"></i>
                                <p class="text-sm">ไม่มีการแจ้งเตือนใหม่</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- User Menu -->
            <div x-data="{ open: false }" class="relative">
                <button 
                    @click="open = !open"
                    class="flex items-center gap-2 p-1 pr-3 rounded-full hover:bg-gray-100 transition-colors"
                >
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                    <i class="fas fa-chevron-down text-gray-400 text-xs hidden md:block"></i>
                </button>
                
                <!-- Dropdown -->
                <div 
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2"
                    x-cloak
                >
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="font-medium text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ url('/typing/profile') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user w-5 text-center text-gray-400"></i>
                        <span>โปรไฟล์</span>
                    </a>
                    <a href="{{ url('/typing/settings') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-cog w-5 text-center text-gray-400"></i>
                        <span>ตั้งค่า</span>
                    </a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <form method="POST" action="{{ route('typing.logout') }}">
                        @csrf
                        <a href="{{ route('typing.logout') }}" 
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt w-5 text-center"></i>
                            <span>ออกจากระบบ</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
