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
        <div class="card text-center">
            <div class="mb-6 relative">
                <img 
                    src="{{ $user->avatar_url }}" 
                    alt="Avatar" 
                    class="w-24 h-24 rounded-xl mx-auto shadow-lg object-cover"
                    id="avatar-preview"
                >
                <label for="avatar-input" class="absolute bottom-0 right-1/2 translate-x-1/2 translate-y-2 w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center cursor-pointer hover:bg-primary-600 transition-colors shadow-lg">
                    <i class="fas fa-camera text-white text-xs"></i>
                </label>
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
            
            <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-gray-500">{{ $user->email }}</p>
            <div class="flex justify-center gap-2 mt-4">
                <span class="badge-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                    <i class="fas fa-{{ $user->role === 'admin' ? 'shield-alt' : 'user-graduate' }} mr-1"></i>
                    {{ $user->role === 'admin' ? 'ผู้ดูแลระบบ' : 'นักเรียน' }}
                </span>
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
