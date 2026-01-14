<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>สมัครสมาชิก - ระบบวิชาพิมพ์หนังสือราชการ 1</title>
    
    <!-- Google Font: Kanit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .student-card {
            transition: all 0.3s ease;
        }
        .student-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .student-card.selected {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }
        .student-card.selected .check-icon {
            display: flex;
        }
        .check-icon {
            display: none;
        }
        .step-indicator .step {
            transition: all 0.3s ease;
        }
        .step-indicator .step.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        .step-indicator .step.completed {
            background: #10b981;
            color: white;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .students-grid {
            max-height: 400px;
            overflow-y: auto;
        }
        .students-grid::-webkit-scrollbar {
            width: 6px;
        }
        .students-grid::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        .students-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .students-grid::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700">
    
    <!-- Decorative Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-cyan-500/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="min-h-screen flex items-center justify-center p-4 relative z-10" x-data="registrationForm()">
        <div class="w-full max-w-2xl">
            
            <!-- Logo Card -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-xl mb-4">
                    <i class="fas fa-user-plus text-emerald-600 text-4xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">สมัครสมาชิกนักเรียน</h1>
                <p class="text-white/70 mt-2">ลงทะเบียนเพื่อเข้าใช้งานระบบ</p>
            </div>
            
            <!-- Step Indicator -->
            <div class="step-indicator flex justify-center gap-4 mb-6">
                <div class="step flex items-center justify-center w-10 h-10 rounded-full font-bold text-lg"
                     :class="currentStep >= 1 ? (currentStep > 1 ? 'completed' : 'active') : 'bg-white/30 text-white'">
                    <span x-show="currentStep <= 1">1</span>
                    <i x-show="currentStep > 1" class="fas fa-check text-sm"></i>
                </div>
                <div class="flex items-center">
                    <div class="w-16 h-1 rounded" :class="currentStep > 1 ? 'bg-emerald-400' : 'bg-white/30'"></div>
                </div>
                <div class="step flex items-center justify-center w-10 h-10 rounded-full font-bold text-lg"
                     :class="currentStep >= 2 ? 'active' : 'bg-white/30 text-white'">
                    2
                </div>
            </div>
            
            <!-- Registration Form Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-center gap-2 text-red-600 mb-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span class="font-semibold">พบข้อผิดพลาด</span>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-500">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('typing.student-register.submit') }}" method="POST">
                    @csrf
                    
                    <!-- Step 1: Select Student -->
                    <div class="form-step" :class="currentStep === 1 ? 'active' : ''">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">
                            <i class="fas fa-search text-emerald-500 mr-2"></i>
                            เลือกชื่อของคุณ
                        </h2>
                        
                        <!-- Class Filter -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-filter text-gray-400 mr-1"></i>
                                กรองตามห้องเรียน
                            </label>
                            <select 
                                x-model="selectedClass"
                                @change="filterStudents()"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all"
                            >
                                <option value="">-- ทุกห้องเรียน --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class }}">{{ $class }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="mb-4">
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input 
                                    type="text"
                                    x-model="searchQuery"
                                    @input="filterStudents()"
                                    placeholder="ค้นหาชื่อหรือรหัสนักเรียน..."
                                    class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all"
                                >
                            </div>
                        </div>
                        
                        <!-- Students Grid -->
                        <div class="students-grid">
                            @if($students->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($students as $student)
                                        <div 
                                            class="student-card relative p-4 rounded-xl border-2 border-gray-200 cursor-pointer"
                                            :class="{ 'selected': selectedStudent == {{ $student->id }} }"
                                            @click="selectStudent({{ $student->id }}, '{{ $student->name }}', '{{ $student->student_id }}')"
                                            x-show="isStudentVisible({{ $student->id }}, '{{ $student->name }}', '{{ $student->student_id }}', '{{ $student->class_name }}')"
                                            data-class="{{ $student->class_name }}"
                                        >
                                            <div class="check-icon absolute top-2 right-2 w-6 h-6 bg-blue-500 rounded-full items-center justify-center">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-lg">
                                                    {{ mb_substr($student->name, 0, 1, 'UTF-8') }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ $student->name }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        <span class="inline-flex items-center gap-1">
                                                            <i class="fas fa-id-card text-xs"></i>
                                                            {{ $student->student_id }}
                                                        </span>
                                                        @if($student->class_name)
                                                            <span class="ml-2 inline-flex items-center gap-1">
                                                                <i class="fas fa-door-open text-xs"></i>
                                                                {{ $student->class_name }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 text-gray-500">
                                    <i class="fas fa-users-slash text-5xl mb-4 text-gray-300"></i>
                                    <p class="text-lg font-medium">ไม่พบนักเรียนที่รอลงทะเบียน</p>
                                    <p class="text-sm mt-2">กรุณาติดต่อครูผู้ดูแลระบบ</p>
                                </div>
                            @endif
                        </div>
                        
                        <input type="hidden" name="student_id" x-model="selectedStudent">
                        
                        <!-- Next Button -->
                        <div class="mt-6 flex justify-end">
                            <button 
                                type="button"
                                @click="nextStep()"
                                :disabled="!selectedStudent"
                                class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-lg"
                            >
                                ถัดไป
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 2: Set Username & Password -->
                    <div class="form-step" :class="currentStep === 2 ? 'active' : ''">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                            <i class="fas fa-key text-emerald-500 mr-2"></i>
                            ตั้งค่าบัญชีผู้ใช้
                        </h2>
                        
                        <!-- Selected Student Info -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-xl">
                                    <span x-text="selectedStudentName ? selectedStudentName.charAt(0) : ''"></span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800" x-text="selectedStudentName"></p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-id-card mr-1"></i>
                                        <span x-text="selectedStudentCode"></span>
                                    </p>
                                </div>
                                <button 
                                    type="button"
                                    @click="prevStep()"
                                    class="ml-auto text-emerald-600 hover:text-emerald-700 text-sm font-medium"
                                >
                                    <i class="fas fa-edit mr-1"></i>
                                    เปลี่ยน
                                </button>
                            </div>
                        </div>
                        
                        <!-- Username Input -->
                        <div class="mb-5">
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user text-gray-400 mr-1"></i>
                                Username
                            </label>
                            <input 
                                type="text"
                                id="username"
                                name="username"
                                x-model="username"
                                placeholder="กรอก username (ตัวอักษรภาษาอังกฤษ, ตัวเลข, _)"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all @error('username') border-red-500 @enderror"
                                value="{{ old('username') }}"
                                required
                            >
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                ใช้ตัวอักษรภาษาอังกฤษ ตัวเลข หรือ _ อย่างน้อย 4 ตัวอักษร
                            </p>
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password Input -->
                        <div class="mb-5" x-data="{ show: false }">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock text-gray-400 mr-1"></i>
                                รหัสผ่าน
                            </label>
                            <div class="relative">
                                <input 
                                    :type="show ? 'text' : 'password'"
                                    id="password"
                                    name="password"
                                    x-model="password"
                                    placeholder="กรอกรหัสผ่าน (อย่างน้อย 6 ตัวอักษร)"
                                    class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all @error('password') border-red-500 @enderror"
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
                        
                        <!-- Confirm Password Input -->
                        <div class="mb-6" x-data="{ show: false }">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock text-gray-400 mr-1"></i>
                                ยืนยันรหัสผ่าน
                            </label>
                            <div class="relative">
                                <input 
                                    :type="show ? 'text' : 'password'"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    x-model="passwordConfirmation"
                                    placeholder="กรอกรหัสผ่านอีกครั้ง"
                                    class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all"
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
                            <template x-if="password && passwordConfirmation && password !== passwordConfirmation">
                                <p class="text-red-500 text-xs mt-1">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    รหัสผ่านไม่ตรงกัน
                                </p>
                            </template>
                            <template x-if="password && passwordConfirmation && password === passwordConfirmation">
                                <p class="text-green-500 text-xs mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    รหัสผ่านตรงกัน
                                </p>
                            </template>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-3">
                            <button 
                                type="button"
                                @click="prevStep()"
                                class="flex-1 py-3 px-4 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-all duration-300"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
                                ย้อนกลับ
                            </button>
                            <button 
                                type="submit"
                                :disabled="!isFormValid()"
                                class="flex-1 py-3 px-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-teal-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i class="fas fa-user-plus mr-2"></i>
                                ลงทะเบียน
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Login Link -->
                <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                    <p class="text-gray-600 text-sm">
                        มีบัญชีอยู่แล้ว?
                        <a href="{{ route('typing.login') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold">
                            เข้าสู่ระบบ
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
    
    <script>
        function registrationForm() {
            return {
                currentStep: 1,
                selectedStudent: null,
                selectedStudentName: '',
                selectedStudentCode: '',
                selectedClass: '',
                searchQuery: '',
                username: '',
                password: '',
                passwordConfirmation: '',
                
                selectStudent(id, name, code) {
                    this.selectedStudent = id;
                    this.selectedStudentName = name;
                    this.selectedStudentCode = code;
                },
                
                isStudentVisible(id, name, code, className) {
                    // Filter by class
                    if (this.selectedClass && className !== this.selectedClass) {
                        return false;
                    }
                    
                    // Filter by search
                    if (this.searchQuery) {
                        const search = this.searchQuery.toLowerCase();
                        return name.toLowerCase().includes(search) || code.toLowerCase().includes(search);
                    }
                    
                    return true;
                },
                
                filterStudents() {
                    // This triggers Alpine to re-evaluate x-show
                },
                
                nextStep() {
                    if (this.selectedStudent) {
                        this.currentStep = 2;
                    }
                },
                
                prevStep() {
                    this.currentStep = 1;
                },
                
                isFormValid() {
                    return this.selectedStudent && 
                           this.username.length >= 4 && 
                           this.password.length >= 6 && 
                           this.password === this.passwordConfirmation;
                }
            }
        }
    </script>
</body>
</html>
