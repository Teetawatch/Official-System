<x-typing-app :role="'student'" :title="'1v1 แข่งพิมพ์งาน'">
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block text-indigo-600 mb-2">1v1 Typing Battle</span>
                <span class="block text-2xl text-gray-500 font-medium">ท้าดวลความเร็วในการพิมพ์แบบเรียลไทม์</span>
            </h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                แข่งกับเพื่อนหรือคู่ต่อสู้แบบสุ่ม ผู้ชนะรับคะแนนสะสมพิเศษ!
            </p>
        </div>

        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transform transition-all hover:scale-105 duration-300">
            <div class="p-8 text-center">
                <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                    <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <div class="absolute inset-0 rounded-full border-4 border-indigo-50 animate-pulse"></div>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-900 mb-2">พร้อมหรือยัง?</h3>
                <p class="text-gray-600 mb-6">เลือกภาษาและค้นหาคู่ต่อสู้</p>

                <!-- Language Selection -->
                <div class="mb-6" id="language-selector">
                    <p class="text-sm text-gray-500 mb-3">เลือกภาษาที่ต้องการแข่ง:</p>
                    <div class="flex gap-3 justify-center">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="language" value="th" class="sr-only peer" checked>
                            <div class="px-6 py-3 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all duration-200 hover:border-indigo-300">
                                <span class="text-2xl mb-1 block">🇹🇭</span>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-indigo-700">ภาษาไทย</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="language" value="en" class="sr-only peer">
                            <div class="px-6 py-3 rounded-xl border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all duration-200 hover:border-indigo-300">
                                <span class="text-2xl mb-1 block">🇺🇸</span>
                                <span class="text-sm font-medium text-gray-700 peer-checked:text-indigo-700">English</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="match-status" class="hidden mb-6">
                    <div class="flex items-center justify-center space-x-3 text-indigo-600">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="font-medium">กำลังค้นหาคู่ต่อสู้...</span>
                    </div>
                </div>

                <div id="countdown" class="hidden mb-6">
                    <span class="text-6xl font-black text-indigo-600 animate-bounce block" id="countdown-text">3</span>
                    <span class="text-sm text-gray-500 mt-2 block">เจอคู่ต่อสู้แล้ว!</span>
                </div>

                <button id="find-match-btn" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-4 px-6 rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition duration-150 ease-in-out shadow-lg flex items-center justify-center space-x-2">
                    <span>ค้นหาคู่ต่อสู้</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-100">
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>คะแนนสะสมของคุณ:</span>
                    <span class="font-bold text-indigo-600 text-lg">{{ auth()->user()->points }} ✨</span>
                </div>
            </div>
        </div>

        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="text-4xl mb-4">⚡</div>
                <h3 class="text-lg font-bold text-gray-900">เรียลไทม์</h3>
                <p class="text-gray-500 mt-2">เห็นความคืบหน้าของคู่ต่อสู้แบบสดๆ ระหว่างแข่ง</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="text-4xl mb-4">🏆</div>
                <h3 class="text-lg font-bold text-gray-900">รางวัล</h3>
                <p class="text-gray-500 mt-2">ผู้ชนะรับ 50 คะแนน ผู้แพ้รับ 10 คะแนน</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="text-4xl mb-4">📈</div>
                <h3 class="text-lg font-bold text-gray-900">สถิติ</h3>
                <p class="text-gray-500 mt-2">บันทึก WPM และความแม่นยำของคุณทุกการแข่งขัน</p>
            </div>
        </div>
    </div>
</div>

<script>
    const findMatchBtn = document.getElementById('find-match-btn');
    const matchStatus = document.getElementById('match-status');
    const countdownDiv = document.getElementById('countdown');
    const countdownText = document.getElementById('countdown-text');
    const languageSelector = document.getElementById('language-selector');
    let matchId = null;
    let checkInterval = null;

    findMatchBtn.addEventListener('click', async () => {
        // Get selected language
        const selectedLanguage = document.querySelector('input[name="language"]:checked').value;
        
        findMatchBtn.disabled = true;
        findMatchBtn.classList.add('opacity-50', 'cursor-not-allowed');
        matchStatus.classList.remove('hidden');
        findMatchBtn.classList.add('hidden');
        languageSelector.classList.add('hidden');

        try {
            const response = await fetch('{{ route('typing.student.matches.find') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    language: selectedLanguage
                })
            });
            
            const data = await response.json();
            matchId = data.match_id;

            if (data.status === 'joined' || data.status === 'found') {
                // Instantly found (joined existing or re-joined)
                startCountdown();
            } else if (data.status === 'created') {
                // Waiting for someone
                waitForOpponent();
            }

        } catch (error) {
            console.error('Error finding match:', error);
            alert('เกิดข้อผิดพลาดในการค้นหาคู่ต่อสู้');
            resetUI();
        }
    });

    function waitForOpponent() {
        checkInterval = setInterval(async () => {
            try {
                const response = await fetch(`{{ url('/typing/student/matches') }}/${matchId}/status`);
                const data = await response.json();

                if (data.player2 !== null) {
                    clearInterval(checkInterval);
                    startCountdown();
                }
            } catch (error) {
                console.error('Checking status failed', error);
            }
        }, 2000); // Poll every 2 seconds
    }

    function startCountdown() {
        matchStatus.classList.add('hidden');
        countdownDiv.classList.remove('hidden');
        
        let count = 3;
        const countInterval = setInterval(() => {
            count--;
            countdownText.textContent = count;
            if (count <= 0) {
                clearInterval(countInterval);
                window.location.href = `{{ url('/typing/student/matches') }}/${matchId}`;
            }
        }, 1000);
    }

    function resetUI() {
        findMatchBtn.disabled = false;
        findMatchBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'hidden');
        matchStatus.classList.add('hidden');
        languageSelector.classList.remove('hidden');
    }
</script>
</x-typing-app>
