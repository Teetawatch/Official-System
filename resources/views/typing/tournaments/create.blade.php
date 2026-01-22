<x-typing-app :role="auth()->user()->role" :title="'สร้างการแข่งขัน - ระบบวิชาพิมพ์หนังสือราชการ 1'">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('typing.tournaments.index') }}" class="text-gray-400 hover:text-primary-500 transition-colors mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> กลับไปหน้ารวม
            </a>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-plus-circle text-primary-500 mr-2"></i>
                สร้างการแข่งขันใหม่
            </h1>
            <p class="text-gray-500 mt-1">กำหนดรูปแบบและกติกาการแข่งขัน</p>
        </div>

        <div class="card">
            <div class="p-8">
                <form action="{{ route('typing.tournaments.store') }}" method="POST"> 
                    <!-- Using GET just to reusing existing controller logic which currently handles creation on GET for testing -->
                    <!-- Wait, existing controller creates immediately on 'create' action. 
                         We should probably change 'create' to 'store' but for now let's make this form SUBMIT to the create endpoint 
                         which currently executes creation. 
                         Actually, the existing 'create' method is a GET that creates immediately. This is bad practice but I should adapt to it 
                         OR fix it to be correct (create -> show form, store -> save).
                         
                         Given the user request, I should probably split it:
                         1. 'create' displays this form (now).
                         2. 'store' handles the saving.
                         
                         Let's refactor Controller slightly to follow standard REST if possible or utilize a new route.
                         However, to minimize friction, I will modify the 'create' route in Controller to:
                         - If Request has input (is submitted), do creation.
                         - Else show this view.
                    -->
                    
                    @csrf
                    <!-- Type Hidden (or selectable) -->
                    <input type="hidden" name="is_submission" value="1">
                    
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">ชื่อการแข่งขัน</label>
                        <input type="text" name="name" class="form-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror" 
                               value="{{ old('name', request('type') == 'class_battle' ? 'Classroom Battle Room #' . rand(1, 999) : 'Weekly Speed Cubing #' . rand(1, 999)) }}" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">รายละเอียด</label>
                        <textarea name="description" rows="3" class="form-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror" required>{{ old('description', request('type') == 'class_battle' ? 'Compete with the entire class! Free for all.' : 'A bracket tournament for the fastest typists!') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">รูปแบบการแข่งขัน</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="bracket" class="peer sr-only" {{ request('type') != 'class_battle' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:bg-gray-50 transition-all text-center">
                                    <i class="fas fa-sitemap text-2xl mb-2 text-gray-400 peer-checked:text-amber-500"></i>
                                    <div class="font-bold text-gray-700 peer-checked:text-amber-700">Tournament Bracket</div>
                                    <div class="text-xs text-gray-500">จับคู่แพ้คัดออก (1v1)</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="class_battle" class="peer sr-only" {{ request('type') == 'class_battle' ? 'checked' : '' }}>
                                <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:bg-gray-50 transition-all text-center">
                                    <i class="fas fa-users-class text-2xl mb-2 text-gray-400 peer-checked:text-indigo-500"></i>
                                    <div class="font-bold text-gray-700 peer-checked:text-indigo-700">Classroom Battle</div>
                                    <div class="text-xs text-gray-500">แข่งรวมทั้งห้อง (Free for All)</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-6" x-data="{ type: '{{ old('type', request('type') ?? 'bracket') }}' }" x-init="$watch('$el.closest(\'form\').type.value', value => type = value)" @change="type = $event.target.form.type.value">
                        <label class="block text-sm font-bold text-gray-700 mb-2">จำนวนผู้เข้าร่วมสูงสุด</label>
                        <input type="number" name="max_participants" class="form-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('max_participants') border-red-500 @enderror" 
                               value="{{ old('max_participants', request('type') == 'class_battle' ? 100 : 16) }}">
                        @error('max_participants')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Game Settings -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-xl border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-gamepad mr-2 text-primary-500"></i> ตั้งค่าการแข่งขัน (Game Settings)
                        </h3>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                กำหนดบทความเอง (Custom Text)
                                <span class="text-xs font-normal text-gray-500">(ถ้าไม่ใส่ จะสุ่มบทความให้)</span>
                            </label>
                            <textarea name="custom_text" rows="4" class="form-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('custom_text') border-red-500 @enderror" placeholder="พิมพ์ข้อความที่ต้องการใช้แข่งขันที่นี่...">{{ old('custom_text') }}</textarea>
                            @error('custom_text')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                จำกัดเวลา (วินาที)
                                <span class="text-xs font-normal text-gray-500">(ถ้าไม่ใส่ คือไม่จำกัดเวลา)</span>
                            </label>
                            <input type="number" name="time_limit" min="30" max="1800" class="form-input w-full md:w-1/2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('time_limit') border-red-500 @enderror" placeholder="เช่น 60, 120 (วินาที)" value="{{ old('time_limit') }}">
                            @error('time_limit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Scoring Config (Only for Class Battle) -->
                    <div x-show="type === 'class_battle'" class="mb-8 bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                        <h3 class="font-bold text-indigo-800 mb-4 flex items-center">
                            <i class="fas fa-star mr-2"></i> ตั้งค่าคะแนน (Scoring Rules)
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="text-xs font-bold text-indigo-600 uppercase">ที่ 1 ได้รับ</label>
                                <input type="number" name="scoring_config[first_place]" value="100" class="mt-1 w-full rounded-md border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-indigo-600 uppercase">ที่ 2 ได้รับ</label>
                                <input type="number" name="scoring_config[second_place]" value="90" class="mt-1 w-full rounded-md border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-indigo-600 uppercase">ที่ 3 ได้รับ</label>
                                <input type="number" name="scoring_config[third_place]" value="80" class="mt-1 w-full rounded-md border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-indigo-600 uppercase">ลดลงลำดับละ (Points)</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="scoring_config[decrement]" value="2" class="mt-1 w-full rounded-md border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="text-xs text-indigo-400 whitespace-nowrap">คะแนน</span>
                                </div>
                                <p class="text-[10px] text-indigo-400 mt-1">อันดับ 4, 5, 6... จะลดลงเรื่อยๆ</p>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-indigo-600 uppercase">คะแนนขั้นต่ำ (Min)</label>
                                <input type="number" name="scoring_config[min_points]" value="10" class="mt-1 w-full rounded-md border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="text-[10px] text-indigo-400 mt-1">ผู้เข้าร่วมทุกคนจะได้ไม่ต่ำกว่านี้</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-indigo-100 flex items-center justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-indigo-900">ใช้หลักการ WPM คำนวณคะแนน?</h4>
                                <p class="text-[10px] text-indigo-500">ถ้าเปิดใช้งาน คะแนนที่ได้จะเป็นค่า WPM ของผู้เล่นโดยตรง แทนการจัดอันดับ</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="scoring_config[use_wpm_as_points]" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="submit" class="btn-primary px-8 py-3 text-lg shadow-lg hover:translate-y-[-2px] transition-transform">
                            <i class="fas fa-check-circle mr-2"></i> สร้างการแข่งขัน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Simple script to update max participants based on radio selection if Alpine is not fully loaded immediately
        const radios = document.querySelectorAll('input[name="type"]');
        const maxInput = document.querySelector('input[name="max_participants"]');
        const configSection = document.querySelector('[x-show="type === \'class_battle\'"]');
        
        radios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                if(e.target.value === 'class_battle') {
                    maxInput.value = 100;
                    configSection.style.display = 'block';
                } else {
                    maxInput.value = 16;
                    configSection.style.display = 'none';
                }
            });
        });
        
        // Init
        document.querySelector('input[name="type"]:checked').dispatchEvent(new Event('change'));
    </script>
</x-typing-app>
