@props(['role' => 'student'])

<div class="h-full flex flex-col">
    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-100">
        <a href="{{ url('/typing') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-file-alt text-white text-lg"></i>
            </div>
            <div>
                <h1 class="font-bold text-gray-800 text-lg leading-tight">พิมพ์หนังสือราชการ</h1>
                <p class="text-xs text-gray-500">ระบบวิชา</p>
            </div>
        </a>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        @if($role === 'admin')
            {{-- Admin Menu --}}
            <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">เมนูหลัก</p>
            
            <a href="{{ url('/typing/admin/dashboard') }}" class="sidebar-link {{ request()->is('typing/admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-home w-5 text-center"></i>
                <span>แดชบอร์ด</span>
            </a>
            
            <a href="{{ url('/typing/admin/students') }}" class="sidebar-link {{ request()->is('typing/admin/students*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate w-5 text-center"></i>
                <span>จัดการนักเรียน</span>
            </a>
            
            <a href="{{ url('/typing/admin/assignments') }}" class="sidebar-link {{ request()->is('typing/admin/assignments*') ? 'active' : '' }}">
                <i class="fas fa-tasks w-5 text-center"></i>
                <span>จัดการงาน</span>
            </a>
            
            <a href="{{ url('/typing/admin/submissions') }}" class="sidebar-link {{ request()->is('typing/admin/submissions*') ? 'active' : '' }}">
                <i class="fas fa-file-upload w-5 text-center"></i>
                <span>ตรวจงานที่ส่ง</span>
            </a>
            
            <p class="px-4 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">รายงาน</p>
            
            <a href="{{ url('/typing/admin/grades') }}" class="sidebar-link {{ request()->is('typing/admin/grades*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar w-5 text-center"></i>
                <span>ตารางคะแนน</span>
            </a>
            
            <a href="{{ url('/typing/leaderboard') }}" class="sidebar-link {{ request()->is('typing/leaderboard') ? 'active' : '' }}">
                <i class="fas fa-trophy w-5 text-center"></i>
                <span>บอร์ดผู้นำ</span>
            </a>
            
        @else
            {{-- Student Menu --}}
            <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">เมนูหลัก</p>
            
            <a href="{{ url('/typing/student/dashboard') }}" class="sidebar-link {{ request()->is('typing/student/dashboard') ? 'active' : '' }}">
                <i class="fas fa-home w-5 text-center"></i>
                <span>แดชบอร์ด</span>
            </a>
            
            <a href="{{ url('/typing/student/assignments') }}" class="sidebar-link {{ request()->is('typing/student/assignments*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list w-5 text-center"></i>
                <span>งานที่ได้รับ</span>
            </a>
            
            <a href="{{ url('/typing/student/submissions') }}" class="sidebar-link {{ request()->is('typing/student/submissions*') ? 'active' : '' }}">
                <i class="fas fa-paper-plane w-5 text-center"></i>
                <span>งานที่ส่งแล้ว</span>
            </a>
            
            <p class="px-4 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">ผลการเรียน</p>
            
            <a href="{{ url('/typing/student/grades') }}" class="sidebar-link {{ request()->is('typing/student/grades*') ? 'active' : '' }}">
                <i class="fas fa-star w-5 text-center"></i>
                <span>คะแนนของฉัน</span>
            </a>
            
            <a href="{{ url('/typing/leaderboard') }}" class="sidebar-link {{ request()->is('typing/leaderboard') ? 'active' : '' }}">
                <i class="fas fa-trophy w-5 text-center"></i>
                <span>บอร์ดผู้นำ</span>
            </a>
            
            <p class="px-4 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">บัญชีผู้ใช้</p>
            
            <a href="{{ url('/typing/profile') }}" class="sidebar-link {{ request()->is('typing/profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle w-5 text-center"></i>
                <span>โปรไฟล์ของฉัน</span>
            </a>
        @endif
    </nav>
    
    <!-- User Section -->
    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="avatar-sm object-cover">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    {{ auth()->user()->role === 'admin' ? 'ผู้ดูแลระบบ' : 'รหัส: ' . (auth()->user()->student_id ?? '-') }}
                </p>
            </div>
        </div>
    </div>
</div>
