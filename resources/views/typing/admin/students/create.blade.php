<x-typing-app :role="'admin'" :title="'เพิ่มนักเรียน - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('typing.admin.students.index') }}" class="btn-ghost p-2 rounded-full">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">เพิ่มนักเรียน</h1>
                <p class="text-gray-500 mt-1">สร้างบัญชีผู้ใช้งานสำหรับนักเรียนใหม่</p>
            </div>
        </div>
    </div>
    
    <div class="card max-w-2xl mx-auto">
        <form action="{{ route('typing.admin.students.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ชื่อ-นามสกุล</label>
                    <input type="text" id="name" name="name" class="input @error('name') input-error @enderror" value="{{ old('name') }}" placeholder="เช่น นายสมชาย ใจดี" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Student ID -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">รหัสนักเรียน</label>
                    <input type="text" id="student_id" name="student_id" class="input @error('student_id') input-error @enderror" value="{{ old('student_id') }}" placeholder="เช่น 65001" required>
                    @error('student_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Class Name -->
                <div>
                    <label for="class_name" class="block text-sm font-medium text-gray-700 mb-2">ชั้นเรียน</label>
                    <input type="text" id="class_name" name="class_name" class="input @error('class_name') input-error @enderror" value="{{ old('class_name') }}" placeholder="เช่น ม.6/1" required>
                    @error('class_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div class="col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">อีเมล (สำหรับเข้าสู่ระบบ)</label>
                    <input type="email" id="email" name="email" class="input @error('email') input-error @enderror" value="{{ old('email') }}" placeholder="email@example.com" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="col-span-2">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">รหัสผ่านเริ่มต้น</label>
                    <input type="text" id="password" name="password" class="input @error('password') input-error @enderror" value="password" required>
                    <p class="text-xs text-gray-500 mt-1">รหัสผ่านเริ่มต้นคือ "password" สามารถเปลี่ยนได้ภายหลัง</p>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
                <a href="{{ route('typing.admin.students.index') }}" class="btn-ghost">ยกเลิก</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</x-typing-app>
