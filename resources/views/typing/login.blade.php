<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>เข้าสู่ระบบ - ระบบวิชาพิมพ์หนังสือราชการ 1</title>
    
    <!-- Google Font: Kanit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-600 via-primary-700 to-accent-700">
    
    <!-- Decorative Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-accent-500/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-secondary-500/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        <div class="w-full max-w-md">
            
            <!-- Logo Card -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-xl mb-4">
                    <i class="fas fa-file-alt text-primary-600 text-4xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">ระบบวิชาพิมพ์หนังสือราชการ 1</h1>
                <p class="text-white/70 mt-2">เข้าสู่ระบบเพื่อจัดการงานและคะแนน</p>
            </div>
            
            <!-- Login Form Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                    <i class="fas fa-sign-in-alt text-primary-500 mr-2"></i>
                    เข้าสู่ระบบ
                </h2>
                
                <form action="{{ route('typing.login') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <!-- Email/Username Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-gray-400 mr-1"></i>
                            อีเมล หรือ Username
                        </label>
                        <input 
                            type="text" 
                            id="email"
                            name="email"
                            placeholder="กรอกอีเมลหรือ Username"
                            class="input @error('email') border-red-500 @enderror"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password Input -->
                    <div x-data="{ show: false }">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            รหัสผ่าน
                        </label>
                        <div class="relative">
                            <input 
                                :type="show ? 'text' : 'password'" 
                                id="password"
                                name="password"
                                placeholder="กรอกรหัสผ่าน"
                                class="input pr-12 @error('password') border-red-500 @enderror"
                                required
                            >
                            <button 
                                type="button"
                                @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-gray-600">จดจำฉัน</span>
                        </label>
                        <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">ลืมรหัสผ่าน?</a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-300 transform hover:-translate-y-0.5"
                    >
                        <i class="fas fa-arrow-right mr-2"></i>
                        เข้าสู่ระบบ
                    </button>
                </form>
                
                <!-- Register Link -->
                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    <p class="text-gray-600 text-sm">
                        ยังไม่ได้ลงทะเบียน?
                        <a href="{{ route('typing.student-register') }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                            สมัครสมาชิก
                        </a>
                    </p>
                </div>
                
            </div>
            
            <!-- Footer -->
            <p class="text-center text-white/60 text-sm mt-8">
                © {{ date('Y') }} ระบบวิชาพิมพ์หนังสือราชการ 1
            </p>
        </div>
    </div>
    
</body>
</html>
