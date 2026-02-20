<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>สมัครสมาชิก - ระบบวิชาพิมพ์หนังสือราชการ 1</title>
    
    <!-- Google Font: Kanit (Premium Typography) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: #f0f4f8;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-animated {
            background: linear-gradient(-45deg, #0f172a, #1e293b, #334155, #0f172a);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        /* Student Selection Card */
        .student-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }
        
        .student-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.1);
            background-color: #f8fafc;
        }
        
        .student-card.selected {
            border-color: #3b82f6; /* Primary Blue */
            background-color: #eff6ff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .student-card.selected .check-mark {
            transform: scale(1);
            opacity: 1;
        }

        .check-mark {
            transform: scale(0);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Scrollbar Styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }

        /* Form Inputs */
        .form-input {
            transition: all 0.2s ease;
        }
        .form-input:focus {
            transform: translateY(-1px);
        }

        /* Floating Shapes */
        .shape {
            position: absolute;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.6;
            animation: float 20s infinite ease-in-out;
        }
        .shape-1 {
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #3b82f6 0%, rgba(59, 130, 246, 0) 70%);
            animation-delay: 0s;
        }
        .shape-2 {
            bottom: -10%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #8b5cf6 0%, rgba(139, 92, 246, 0) 70%);
            animation-delay: -5s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, 30px); }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-900 overflow-hidden font-sans text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

    <!-- Background Effects -->
    <div class="fixed inset-0 pointer-events-none">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150 mix-blend-overlay"></div>
    </div>
    
    <div class="relative min-h-screen flex items-center justify-center p-4 py-8" x-data="registrationForm()">
        <div class="w-full max-w-5xl h-[85vh] flex rounded-3xl overflow-hidden glass-card shadow-2xl animate-fade-in-up">
            
            <!-- Left Side: Hero Section -->
            <div class="hidden lg:flex w-2/5 flex-col justify-between p-12 bg-animated text-white relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class="fas fa-keyboard text-xl"></i>
                        </div>
                        <span class="font-bold tracking-wide text-white/90">WPM PRO</span>
                    </div>
                    
                    <h1 class="text-4xl font-bold leading-tight mb-4">
                        เริ่มต้นการเรียนรู้<br>
                        <span class="text-blue-400">การพิมพ์สัมผัส</span>
                        <br>อย่างมืออาชีพ
                    </h1>
                    <p class="text-slate-300 text-lg font-light leading-relaxed">
                        ระบบฝึกพิมพ์หนังสือราชการที่ทันสมัยที่สุด ช่วยให้คุณพัฒนาทักษะการพิมพ์ได้อย่างรวดเร็วและแม่นยำ
                    </p>
                </div>

                <div class="relative z-10 space-y-6">
                    <!-- Feature Items -->
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-300 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">ติดตามผลแบบ Real-time</h3>
                            <p class="text-slate-400 text-sm">ดูสถิติความเร็วและความแม่นยำทันที</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-purple-500/20 flex items-center justify-center">
                            <i class="fas fa-medal text-purple-300 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">จัดอันดับ Leaderboard</h3>
                            <p class="text-slate-400 text-sm">แข่งขันกับเพื่อนในชั้นเรียน</p>
                        </div>
                    </div>
                </div>

                <!-- Abstract Circles -->
                <div class="absolute top-1/2 right-0 transform translate-x-1/2 -translate-y-1/2 w-64 h-64 border border-white/10 rounded-full"></div>
                <div class="absolute top-1/2 right-0 transform translate-x-1/2 -translate-y-1/2 w-96 h-96 border border-white/5 rounded-full"></div>
            </div>

            <!-- Right Side: Form Section -->
            <div class="flex-1 flex flex-col relative bg-white/50 backdrop-blur-sm">
                <!-- Header -->
                <div class="px-8 py-6 border-b border-gray-100/50 flex justify-between items-center bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">สมัครสมาชิกใหม่</h2>
                        <p class="text-slate-500 text-sm mt-1">กรุณากรอกข้อมูลให้ครบถ้วนเพื่อเริ่มต้นใช้งาน</p>
                    </div>
                    <div class="flex items-center gap-2 text-sm font-medium text-slate-400 bg-slate-50 px-3 py-1.5 rounded-full border border-slate-200">
                        <span>ขั้นตอนที่ <span x-text="currentStep"></span>/2</span>
                    </div>
                </div>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto px-8 py-6 custom-scrollbar relative">
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-xl animate-shake">
                            <div class="flex items-center gap-2 text-red-600 mb-2">
                                <i class="fas fa-exclamation-circle text-lg"></i>
                                <span class="font-semibold">โปรดตรวจสอบข้อมูล</span>
                            </div>
                            <ul class="list-disc list-inside text-sm text-red-500 ml-1 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('typing.student-register.submit') }}" method="POST" id="registerForm" class="h-full">
                        @csrf
                        
                        <!-- Step 1: Select Student -->
                        <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="h-full flex flex-col">
                            
                            <!-- Search & Filter Toolkit -->
                            <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md pb-4 pt-2 -mt-2 space-y-4 border-b border-slate-100 mb-6">
                                <div class="flex gap-4">
                                    <div class="flex-1 relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input 
                                            type="text" 
                                            x-model="searchQuery"
                                            @input="filterStudents()" 
                                            class="block w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium text-slate-600 placeholder:text-slate-400" 
                                            placeholder="ค้นหาชื่อ หรือ รหัสนักเรียน..."
                                        >
                                    </div>
                                    <div class="w-1/3 relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <i class="fas fa-filter text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <select 
                                            x-model="selectedClass"
                                            @change="filterStudents()"
                                            class="block w-full pl-10 pr-8 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all font-medium text-slate-600 appearance-none cursor-pointer"
                                        >
                                            <option value="">ทุกห้องเรียน</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class }}">{{ $class }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Grid -->
                            <div class="flex-1 relative min-h-[300px]">
                                @if($students->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-4">
                                        @foreach($students as $student)
                                            <div 
                                                class="student-card bg-white p-4 rounded-xl cursor-pointer group"
                                                :class="{ 'selected': selectedStudent == {{ $student->id }} }"
                                                @click="selectStudent({{ $student->id }}, '{{ $student->name }}', '{{ $student->student_id }}')"
                                                x-show="isStudentVisible({{ $student->id }}, '{{ $student->name }}', '{{ $student->student_id }}', '{{ $student->class_name }}')"
                                                data-class="{{ $student->class_name }}"
                                            >
                                                <!-- Check Mark -->
                                                <div class="check-mark absolute top-3 right-3 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center shadow-lg transform translate-x-2 -translate-y-2 group-hover:translate-x-0 group-hover:translate-y-0">
                                                    <i class="fas fa-check text-white text-[10px]"></i>
                                                </div>

                                                <div class="flex items-center gap-4">
                                                    <!-- Avatar -->
                                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-lg group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors ring-4 ring-slate-50 group-hover:ring-blue-50/50">
                                                        {{ mb_substr($student->name, 0, 1, 'UTF-8') }}
                                                    </div>
                                                    
                                                    <!-- Info -->
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-semibold text-slate-800 text-base truncate group-hover:text-blue-700 transition-colors">
                                                            {{ $student->name }}
                                                        </h4>
                                                        <div class="flex flex-wrap gap-2 mt-1">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                                <i class="fas fa-id-badge mr-1.5 opacity-60"></i>{{ $student->student_id }}
                                                            </span>
                                                            @if($student->class_name)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-600 border border-indigo-100">
                                                                    <i class="fas fa-graduation-cap mr-1.5 opacity-60"></i>{{ $student->class_name }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-8">
                                        <div class="w-20 h-20 bg-slate-50 flex items-center justify-center rounded-full mb-4 animate-pulse">
                                            <i class="fas fa-users-slash text-slate-300 text-3xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-slate-700">ไม่มีข้อมูลนักเรียน</h3>
                                        <p class="text-slate-500 mt-1 max-w-xs mx-auto">ไม่พบรายชื่อนักเรียนที่รอลงทะเบียนในขณะนี้ กรุณาติดต่อผู้ดูแลระบบ</p>
                                    </div>
                                @endif
                            </div>
                            
                            <input type="hidden" name="student_id" x-model="selectedStudent">
                        </div>

                        <!-- Step 2: Set Credentials -->
                        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" style="display: none;">
                            
                            <!-- Review Selection -->
                            <div class="mb-8 p-6 bg-blue-50/50 rounded-2xl border border-blue-100/50 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-2xl font-bold text-blue-600 border border-blue-100">
                                        <span x-text="selectedStudentName ? selectedStudentName.charAt(0) : ''"></span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-blue-600 uppercase tracking-wider mb-0.5">กำลังสมัครสมาชิกให้</p>
                                        <h3 class="text-xl font-bold text-slate-800" x-text="selectedStudentName"></h3>
                                        <p class="text-slate-500 font-medium font-mono mt-1" x-text="selectedStudentCode"></p>
                                    </div>
                                </div>
                                <button type="button" @click="prevStep()" class="text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors underline decoration-2 decoration-transparent hover:decoration-blue-600 underline-offset-4">
                                    เปลี่ยนคน?
                                </button>
                            </div>

                            <div class="space-y-6 max-w-lg mx-auto">
                                <!-- Username -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อผู้ใช้งาน (Username)</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input 
                                            type="text" 
                                            name="username" 
                                            x-model="username"
                                            class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium text-slate-800"
                                            placeholder="ตั้งชื่อผู้ใช้งาน..."
                                            required
                                        >
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500 flex items-center">
                                        <i class="fas fa-info-circle mr-1.5 text-blue-400"></i>
                                        ภาษาอังกฤษ ตัวเลข, จุด (.) หรือ _ อย่างน้อย 4 ตัวอักษร
                                    </p>
                                </div>

                                <!-- Password -->
                                <div x-data="{ show: false }">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">รหัสผ่าน (Password)</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input 
                                            :type="show ? 'text' : 'password'" 
                                            name="password" 
                                            x-model="password"
                                            class="block w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium text-slate-800"
                                            placeholder="ตั้งรหัสผ่าน..."
                                            required
                                        >
                                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div x-data="{ show: false }">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">ยืนยันรหัสผ่าน</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-shield-alt text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                        </div>
                                        <input 
                                            :type="show ? 'text' : 'password'" 
                                            name="password_confirmation" 
                                            x-model="passwordConfirmation"
                                            class="block w-full pl-11 pr-12 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium text-slate-800 focus:border-slate-800 selection:bg-slate-200"
                                            :class="{
                                                'border-green-500 focus:border-green-500 focus:ring-green-50': password && passwordConfirmation && password === passwordConfirmation,
                                                'border-red-300 focus:border-red-500 focus:ring-red-50': password && passwordConfirmation && password !== passwordConfirmation
                                            }"
                                            placeholder="กรอกรหัสผ่านอีกครั้ง..."
                                            required
                                        >
                                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                        </button>
                                    </div>
                                    <!-- Match Indicator -->
                                    <div class="h-6 mt-1.5 flex items-center text-xs font-medium transition-all duration-300">
                                        <template x-if="password && passwordConfirmation">
                                            <span :class="password === passwordConfirmation ? 'text-green-600' : 'text-red-500'" class="flex items-center">
                                                <i :class="password === passwordConfirmation ? 'fas fa-check-circle' : 'fas fa-times-circle'" class="mr-1.5"></i>
                                                <span x-text="password === passwordConfirmation ? 'รหัสผ่านตรงกัน' : 'รหัสผ่านไม่ตรงกัน'"></span>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- Footer / Action Area -->
                <div class="px-8 py-6 border-t border-gray-100 bg-white/80 backdrop-blur z-20 flex items-center justify-between gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2 text-xs"></i> กลับหน้าหลัก
                    </a>

                    <div class="flex items-center gap-3">
                        <button 
                            x-show="currentStep === 2"
                            @click="prevStep()"
                            type="button"
                            class="px-6 py-3 rounded-xl text-slate-600 font-semibold hover:bg-slate-100 transition-all"
                        >
                            ย้อนกลับ
                        </button>

                        <button 
                            x-show="currentStep === 1"
                            @click="nextStep()"
                            :disabled="!selectedStudent"
                            type="button"
                            class="group relative px-8 py-3 bg-slate-900 hover:bg-blue-600 text-white font-semibold rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-blue-600/30 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none transition-all duration-300 overflow-hidden"
                        >
                            <span class="relative z-10 flex items-center gap-2">
                                ถัดไป <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </button>

                        <button 
                            x-show="currentStep === 2"
                            type="submit"
                            form="registerForm"
                            :disabled="!isFormValid()"
                            class="group px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform active:scale-95"
                        >
                            <span class="flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> ยืนยันการสมัคร
                            </span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Background Attribution / Small Footer -->
        <div class="fixed bottom-4 left-1/2 -translate-x-1/2 text-slate-400 text-xs font-light tracking-wide pointer-events-none">
            &copy; {{ date('Y') }} Typing System. Designed for Excellence.
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
