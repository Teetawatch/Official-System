<x-typing-app :role="auth()->user()->role" :title="'โปรไฟล์ - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
            <i class="fas fa-user-circle text-primary-500 mr-2"></i>
            โปรไฟล์ของฉัน
        </h1>
        <p class="text-gray-500 mt-1">จัดการข้อมูลส่วนตัวและรหัสผ่าน</p>
    </div>
    
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-secondary-50 border border-secondary-200 text-secondary-700">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Profile Card -->
        @php
            $frameItem = $user->equipped_frame_item;
            $titleItem = $user->equipped_title_item;
            $themeItem = $user->equipped_theme_item;
            $frameGradient = $frameItem && isset($frameItem->data['gradient']) 
                ? $frameItem->data['gradient'] 
                : 'from-gray-300 to-gray-400';
            
            $nameColorItem = $user->equipped_name_color_item;
            $profileBgItem = $user->equipped_profile_bg_item;
            
            $nameColorClass = $nameColorItem ? ($nameColorItem->data['class'] ?? '') : 'text-gray-800';
            
            // Card background
            $profileBgClass = $profileBgItem ? ($profileBgItem->data['class'] ?? '') : 'bg-white';
        @endphp
        
        <div class="card text-center {{ $profileBgClass }}">
            <div class="mb-6 relative flex flex-col items-center">
                {{-- Avatar with Frame --}}
                <div class="relative">
                    <div class="w-28 h-28 rounded-full bg-gradient-to-br {{ $frameGradient }} p-1 shadow-xl">
                        <img 
                            src="{{ $user->avatar_url }}" 
                            alt="Avatar" 
                            class="w-full h-full rounded-full object-cover border-4 border-white"
                            id="avatar-preview"
                        >
                    </div>
                    @if($frameItem && isset($frameItem->data['icon']))
                        <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg border border-gray-100">
                            <span class="text-lg">{{ $frameItem->data['icon'] }}</span>
                        </div>
                    @endif
                    <label for="avatar-input" class="absolute bottom-0 left-0 w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center cursor-pointer hover:bg-primary-600 transition-colors shadow-lg">
                        <i class="fas fa-camera text-white text-xs"></i>
                    </label>
                </div>
                
                {{-- Title Badge --}}
                @if($titleItem)
                    <div class="mt-3 inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gradient-to-r {{ $titleItem->rarity_color }} text-white font-bold text-sm shadow-lg">
                        @if(isset($titleItem->data['emoji']))
                            <span>{{ $titleItem->data['emoji'] }}</span>
                        @endif
                        <span>{{ $titleItem->name }}</span>
                    </div>
                @endif
            </div>
            
            <!-- Avatar Upload Form -->
            <form action="{{ route('typing.profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
                @csrf
                <input 
                    type="file" 
                    id="avatar-input" 
                    name="avatar" 
                    accept="image/*" 
                    class="hidden"
                >
            </form>
            <p class="text-xs text-gray-400 mb-4">คลิกไอคอนกล้องเพื่อเปลี่ยนรูป</p>
            
            <h2 class="text-xl font-bold {{ $nameColorClass }}">{{ $user->name }}</h2>
            <p class="text-gray-500">{{ $user->email }}</p>
            <div class="flex justify-center gap-2 mt-4">
                <span class="badge-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                    <i class="fas fa-{{ $user->role === 'admin' ? 'shield-alt' : 'user-graduate' }} mr-1"></i>
                    {{ $user->role === 'admin' ? 'ผู้ดูแลระบบ' : 'นักเรียน' }}
                </span>
            </div>
            
            {{-- Points Display --}}
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center justify-center gap-3 px-4 py-3 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border border-amber-200">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow">
                        <i class="fas fa-coins text-white"></i>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] text-amber-600 font-medium uppercase tracking-wider">คะแนนสะสม</p>
                        <p class="text-xl font-bold text-amber-700">{{ number_format($user->points ?? 0) }}</p>
                    </div>
                </div>
            </div>
            
            {{-- Equipped Items --}}
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-3">ไอเทมที่ใช้งาน</p>
                <div class="flex flex-wrap gap-2 justify-center">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $frameItem ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-400' }} text-xs">
                        <i class="fas fa-circle-user"></i>
                        <span>{{ $frameItem ? Str::limit($frameItem->name, 10) : 'ไม่มีกรอบ' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $themeItem ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-400' }} text-xs">
                        <i class="fas fa-palette"></i>
                        <span>{{ $themeItem ? Str::limit($themeItem->name, 10) : 'ไม่มีธีม' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $titleItem ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400' }} text-xs">
                        <i class="fas fa-crown"></i>
                        <span>{{ $titleItem ? Str::limit($titleItem->name, 10) : 'ไม่มีตำแหน่ง' }}</span>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 justify-center mt-2">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $nameColorItem ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-400' }} text-xs">
                        <i class="fas fa-signature"></i>
                        <span>{{ $nameColorItem ? Str::limit($nameColorItem->name, 10) : 'ไม่มีสีชื่อ' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $profileBgItem ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-400' }} text-xs">
                        <i class="fas fa-id-card"></i>
                        <span>{{ $profileBgItem ? Str::limit($profileBgItem->name, 10) : 'ไม่มีการ์ด' }}</span>
                    </div>
                </div>
                </div>
                <div class="mt-3 flex gap-2 justify-center">
                    <a href="{{ route('typing.shop.my-rewards') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-box-open mr-1"></i> รางวัลของฉัน
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('typing.shop.index') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                        <i class="fas fa-store mr-1"></i> ร้านค้า
                    </a>
                </div>
            </div>
            
            @if($user->role === 'student')
            <div class="mt-6 pt-6 border-t border-gray-100 text-left">
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">รหัสนักเรียน</span>
                    <span class="font-medium text-gray-800">{{ $user->student_id ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">ห้อง</span>
                    <span class="font-medium text-gray-800">{{ $user->class_name ?? '-' }}</span>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Edit Forms -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Update Profile Form -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">
                    <i class="fas fa-edit text-primary-500 mr-2"></i>
                    แก้ไขข้อมูลส่วนตัว
                </h3>
                
                <form action="{{ route('typing.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                class="input w-full"
                                required
                            >
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                class="input w-full"
                                required
                            >
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                บันทึกข้อมูล
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Change Password Form -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">
                    <i class="fas fa-lock text-amber-500 mr-2"></i>
                    เปลี่ยนรหัสผ่าน
                </h3>
                
                <form action="{{ route('typing.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านปัจจุบัน</label>
                            <input 
                                type="password" 
                                id="current_password" 
                                name="current_password" 
                                class="input w-full"
                                required
                            >
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านใหม่</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="input w-full"
                                required
                            >
                            <p class="text-xs text-gray-500 mt-1">รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร</p>
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่านใหม่</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="input w-full"
                                required
                            >
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" class="btn-outline">
                                <i class="fas fa-key mr-2"></i>
                                เปลี่ยนรหัสผ่าน
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
    
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        document.getElementById('avatar-input').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                // Show preview
                previewAvatar(this);
                
                // Show loading indicator
                const btn = document.querySelector('label[for="avatar-input"]');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin text-white text-xs"></i>';
                
                // Submit form after short delay to show preview
                setTimeout(() => {
                    document.getElementById('avatar-form').submit();
                }, 500);
            }
        });
    </script>
    
</x-typing-app>
