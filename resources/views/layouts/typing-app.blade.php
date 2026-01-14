<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'ระบบวิชาพิมพ์หนังสือราชการ 1' }}</title>
    
    <!-- Google Font: Kanit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile Sidebar Overlay -->
    <div 
        x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"
        @click="sidebarOpen = false"
        x-cloak
    ></div>
    
    <!-- Sidebar -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 z-50 h-full w-64 bg-white shadow-xl transition-transform duration-300 lg:translate-x-0"
    >
        @include('components.typing.sidebar', ['role' => $role ?? 'student'])
    </aside>
    
    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen flex flex-col">
        
        <!-- Navbar -->
        @include('components.typing.navbar')
        
        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-6 lg:p-8">
            {{ $slot }}
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 py-4 px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-2 text-sm text-gray-500">
                <p>© {{ date('Y') }} ระบบวิชาพิมพ์หนังสือราชการ 1</p>
                <p class="flex items-center gap-2">
                    <i class="fas fa-heart text-red-500"></i>
                    พัฒนาด้วยความรัก
                </p>
            </div>
        </footer>
    </div>
    
    @stack('scripts')
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: "{{ session('error') }}",
                });
            @endif
        });
    </script>
</body>
</html>
