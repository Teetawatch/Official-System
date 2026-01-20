<x-typing-app :role="'student'" :title="'ฝึกพิมพ์ - ' . $assignment->title">

    <div class="max-w-4xl mx-auto" x-data="typingGame()">

        <!-- Game Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $assignment->title }}</h1>
                <p class="text-gray-500">พิมพ์ข้อความด้านล่างให้ถูกต้องและรวดเร็วที่สุด</p>
            </div>

            <!-- Live Stats -->
            <div class="flex gap-4">
                <div class="card p-3 min-w-[100px] text-center bg-white shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">WPM</p>
                    <p class="text-2xl font-bold text-primary-600" x-text="wpm">0</p>
                </div>
                <div class="card p-3 min-w-[100px] text-center bg-white shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Accuracy</p>
                    <p class="text-2xl font-bold text-secondary-600"><span x-text="accuracy">100</span>%</p>
                </div>
                <div class="card p-3 min-w-[100px] text-center bg-white shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Time</p>
                    <p class="text-2xl font-bold" :class="{
                           'text-red-600': timeRemaining <= 30 && timeRemaining > 0,
                           'text-gray-700': timeRemaining > 30 || timeRemaining === 0,
                           'animate-shake': timeRemaining <= 15 && timeRemaining > 0
                       }" x-text="formatTime(timeRemaining || timer)">00:00</p>
                </div>
            </div>
        </div>

        <!-- Typing Area -->
        <div class="card p-8 mb-6 relative overflow-hidden min-h-[300px] flex flex-col justify-center text-lg md:text-xl leading-relaxed font-mono"
            :class="{'border-primary-500 shadow-xl': isFocused, 'border-gray-200': !isFocused, 'opacity-50 blur-sm pointer-events-none': isFinished}">

            <!-- Focus Overlay -->
            <div x-show="!isFocused && !isStarted && !isFinished"
                class="absolute inset-0 flex items-center justify-center bg-white/80 z-10 cursor-pointer"
                @click="focusInput">
                <div class="text-center animate-bounce">
                    <div
                        class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg text-white">
                        <i class="fas fa-play text-2xl pl-1"></i>
                    </div>
                    <p class="text-gray-600 font-medium">คลิกเพื่อเริ่มพิมพ์</p>
                </div>
            </div>

            <!-- Virtual Text Display -->
            <div class="relative z-0 select-none pointer-events-none whitespace-pre-wrap break-words" id="text-display">
                <template x-for="(char, index) in textArray" :key="index">
                    <span :class="{
                        'text-green-500': index < currentIndex && userInput[index] === char,
                        'text-red-500 bg-red-100': index < currentIndex && userInput[index] !== char,
                        'bg-primary-200 text-primary-900 border-b-2 border-primary-500 animate-pulse': index === currentIndex && isFocused,
                        'text-gray-400': index > currentIndex,
                        'text-gray-600': index === currentIndex && !isFocused
                    }" x-text="char"></span>
                </template>
            </div>

            <!-- Hidden Input for Mobile/IME support -->
            <textarea x-ref="typingInput" class="absolute inset-0 opacity-0 cursor-default resize-none z-10"
                @input="handleInput" @keydown="handleKeydown" @focus="isFocused = true" @blur="isFocused = false"
                spellcheck="false" autocomplete="off" :disabled="isFinished"></textarea>

        </div>

        <!-- Controls -->
        <div class="flex justify-between items-center">
            <button @click="resetGame" class="btn-ghost text-gray-500 hover:text-gray-700">
                <i class="fas fa-redo-alt mr-2"></i>เริ่มใหม่
            </button>
            <div x-show="isFinished" style="display: none;" class="flex gap-3">
                <a href="{{ route('typing.student.assignments') }}" class="btn-outline">
                    กลับหน้าหลัก
                </a>
                <button @click="submitResult" class="btn-primary" :disabled="isSubmitting">
                    <span x-show="!isSubmitting"><i class="fas fa-save mr-2"></i>บันทึกผล</span>
                    <span x-show="isSubmitting"><i class="fas fa-spinner fa-spin mr-2"></i>กำลังบันทึก...</span>
                </button>
            </div>
        </div>

        <!-- Result Modal -->
        <div x-show="showResultModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r"
                    :class="timeExpired ? 'from-red-500 via-orange-500 to-red-500' : 'from-primary-500 via-secondary-500 to-primary-500'">
                </div>

                <!-- Time Expired Icon -->
                <div x-show="timeExpired"
                    class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-clock text-4xl text-red-600"></i>
                </div>

                <!-- Success Icon -->
                <div x-show="!timeExpired"
                    class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-trophy text-4xl text-green-600"></i>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-2"
                    x-text="timeExpired ? 'หมดเวลาแล้ว!' : 'ยอดเยี่ยมมาก!'"></h2>
                <p class="text-gray-500 mb-8"
                    x-text="timeExpired ? 'เวลาของคุณหมดแล้ว นี่คือผลคะแนนของคุณ' : 'คุณพิมพ์เสร็จเรียบร้อยแล้ว'"></p>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">ความเร็ว (WPM)</p>
                        <p class="text-3xl font-bold text-primary-600" x-text="wpm">0</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">ความแม่นยำ</p>
                        <p class="text-3xl font-bold text-secondary-600"><span x-text="accuracy">0</span>%</p>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button @click="submitResult" class="btn-primary w-full py-3" :disabled="isSubmitting">
                        <span x-show="!isSubmitting">บันทึกผลคะแนน</span>
                        <span x-show="isSubmitting"><i class="fas fa-spinner fa-spin mr-2"></i>กำลังบันทึก...</span>
                    </button>
                    <button @click="resetGame" class="btn-ghost w-full py-3">
                        ลองอีกครั้ง
                    </button>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function typingGame() {
                return {
                    fullText: @json($assignment->content),
                    textArray: [],
                    userInput: [],
                    currentIndex: 0,

                    isStarted: false,
                    isFinished: false,
                    isFocused: false,
                    isSubmitting: false,
                    showResultModal: false,
                    timeExpired: false,

                    timer: 0,
                    timerInterval: null,
                    startTime: null,
                    timeLimit: @json($assignment->time_limit), // in minutes
                    timeRemaining: null, // in seconds

                    wpm: 0,
                    accuracy: 100,
                    totalKeystrokes: 0,
                    correctKeystrokes: 0,
                    errorCount: 0,

                    assignmentId: @json($assignment->id),
                    maxScore: @json($assignment->max_score),

                    init() {
                        // Pre-process text (clean up invisible chars if any)
                        // Split into array for easier rendering
                        this.textArray = this.fullText.split('');

                        // Set time remaining if time limit is set
                        if (this.timeLimit && this.timeLimit > 0) {
                            this.timeRemaining = this.timeLimit * 60; // convert minutes to seconds
                        }

                        // Focus handling
                        this.$watch('isFocused', value => {
                            if (value && !this.$refs.typingInput.disabled) {
                                this.$refs.typingInput.focus();
                            }
                        });
                    },

                    focusInput() {
                        this.isFocused = true;
                        this.$refs.typingInput.focus();
                    },

                    handleInput(e) {
                        if (this.isFinished) return;

                        const value = e.target.value;
                        const char = value.slice(-1); // Get last typed char

                        // Reset textarea value to avoid scrolling/overflow issues
                        // We maintain state in userInput array
                        if (value.length > 0) {
                            // Only process if adding text (not deleting) - though specialized logic needed for backspace
                            // Simple implementation:
                        }

                        // Better approach: Listen to keydown for special keys, input for text
                        // But for simple "type forward" game:

                        if (!this.isStarted) {
                            this.startTimer();
                            this.isStarted = true;
                        }
                    },

                    handleKeydown(e) {
                        if (this.isFinished || this.isSubmitting) {
                            e.preventDefault();
                            return;
                        }

                        // Prevent Default behavior for some keys
                        if (['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'].includes(e.key)) {
                            e.preventDefault();
                        }

                        // Backspace Logic
                        if (e.key === 'Backspace') {
                            if (this.currentIndex > 0) {
                                this.currentIndex--;
                                let deletedChar = this.userInput.pop();
                                // Logic to revert stats could be complex, for simple WPM usually ignored
                            }
                            return;
                        }

                        // Ignore modifier keys
                        if (e.key.length > 1 && e.key !== 'Enter') return;

                        if (!this.isStarted) {
                            this.startTimer();
                            this.isStarted = true;
                        }

                        // Get expected char
                        const expectedChar = this.textArray[this.currentIndex];
                        let typedChar = e.key;

                        if (typedChar === 'Enter') typedChar = '\n';

                        // Record Input
                        this.userInput[this.currentIndex] = typedChar;
                        this.totalKeystrokes++;

                        if (typedChar === expectedChar) {
                            this.correctKeystrokes++;
                        } else {
                            this.errorCount++;
                        }

                        this.currentIndex++;

                        // Update Stats
                        this.calculateStats();

                        // Check Finish
                        if (this.currentIndex >= this.textArray.length) {
                            this.finishGame();
                        }

                        // Auto-scroll logic (basic)
                        // In a real optimized engine, we'd move the view or cursor
                    },

                    startTimer() {
                        // Clear any existing interval to prevent duplicates
                        if (this.timerInterval) {
                            clearInterval(this.timerInterval);
                        }

                        this.startTime = Date.now();
                        this.timerInterval = setInterval(() => {
                            // Safety check: if game is finished, stop everything
                            if (this.isFinished) {
                                clearInterval(this.timerInterval);
                                return;
                            }

                            this.timer = Math.floor((Date.now() - this.startTime) / 1000);

                            // If time limit is set, count down
                            if (this.timeLimit && this.timeLimit > 0) {
                                this.timeRemaining = (this.timeLimit * 60) - this.timer;

                                // Auto-finish when time is up
                                if (this.timeRemaining <= 0) {
                                    this.timeRemaining = 0;
                                    this.finishGame(true); // true = time expired
                                    return;
                                }
                            }

                            this.calculateStats();
                        }, 1000);
                    },

                    calculateStats() {
                        if (this.timer > 0) {
                            // Standard WPM Formula:
                            // 1. Count all typed characters (currentIndex includes letters, numbers, spaces, punctuation)
                            // 2. Divide by 5 (1 standard word = 5 characters)
                            // 3. Divide by time in minutes
                            const totalCharactersTyped = this.currentIndex;
                            const words = totalCharactersTyped / 5;
                            const minutes = this.timer / 60;
                            this.wpm = Math.round(words / minutes);
                        }

                        if (this.totalKeystrokes > 0) {
                            this.accuracy = Math.round((this.correctKeystrokes / this.totalKeystrokes) * 100);
                        }
                    },

                    finishGame(expired = false) {
                        this.isFinished = true;
                        this.timeExpired = expired;
                        clearInterval(this.timerInterval);
                        this.$refs.typingInput.blur();

                        if (expired) {
                            Swal.fire({
                                title: 'หมดเวลา!',
                                text: 'กำลังบันทึกคะแนนของคุณ...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            this.submitResult();
                        } else {
                            this.showResultModal = true;
                        }
                    },

                    resetGame() {
                        this.userInput = [];
                        this.currentIndex = 0;
                        this.isStarted = false;
                        this.isFinished = false;
                        this.showResultModal = false;
                        this.timeExpired = false;
                        this.timer = 0;
                        // Reset time remaining if time limit is set
                        if (this.timeLimit && this.timeLimit > 0) {
                            this.timeRemaining = this.timeLimit * 60;
                        }
                        this.wpm = 0;
                        this.accuracy = 100;
                        this.totalKeystrokes = 0;
                        this.correctKeystrokes = 0;
                        this.errorCount = 0;
                        clearInterval(this.timerInterval);
                        this.$refs.typingInput.value = '';
                        this.focusInput();
                    },

                    formatTime(seconds) {
                        const m = Math.floor(seconds / 60).toString().padStart(2, '0');
                        const s = (seconds % 60).toString().padStart(2, '0');
                        return `${m}:${s}`;
                    },

                    submitResult() {
                        if (this.isSubmitting) return;
                        this.isSubmitting = true;

                        // Use Laravel route helper to get the correct URL (works with subdirectories)
                        const submitUrl = "{{ route('typing.student.practice.submit', ':id') }}".replace(':id', this.assignmentId);
                        const redirectUrl = "{{ route('typing.student.submissions') }}";

                        fetch(submitUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                wpm: this.wpm,
                                accuracy: this.accuracy,
                                time_taken: this.timer,
                                score: this.calculateScore(),
                                keystrokes_data: JSON.stringify(this.userInput)
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'บันทึกสำเร็จ!',
                                        text: 'ผลการพิมพ์ของคุณถูกบันทึกเรียบร้อยแล้ว',
                                        confirmButtonText: 'ตกลง',
                                        confirmButtonColor: '#3b82f6',
                                        allowOutsideClick: false
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = redirectUrl;
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        text: data.message || 'ไม่สามารถบันทึกข้อมูลได้',
                                        confirmButtonText: 'ลองใหม่'
                                    });
                                    this.isSubmitting = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                                    confirmButtonText: 'ตกลง'
                                });
                                this.isSubmitting = false;
                            });
                    },

                    calculateScore() {
                        // Base calculated percentage (0-100)
                        // Formula: (Accuracy * 0.6) + (WPM_Ratio * 0.4)
                        let baseScore = (this.accuracy * 0.6) + (Math.min(this.wpm, 100) * 0.4);

                        // Scale to assignment's max_score
                        let finalScore = (baseScore / 100) * this.maxScore;

                        return Math.round(finalScore * 100) / 100; // 2 decimal places
                    }
                }
            }
        </script>
    @endpush

    <style>
        .font-mono {
            font-family: 'Courier Prime', 'Sarabun', monospace;
            /* Use a good monospaced font if available */
            line-height: 2;
        }

        /* Cursor Blinking Animation */
        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        /* Shake Animation */
        @keyframes shake {
            0% {
                transform: translate(1px, 1px) rotate(0deg);
            }

            10% {
                transform: translate(-1px, -2px) rotate(-1deg);
            }

            20% {
                transform: translate(-3px, 0px) rotate(1deg);
            }

            30% {
                transform: translate(3px, 2px) rotate(0deg);
            }

            40% {
                transform: translate(1px, -1px) rotate(1deg);
            }

            50% {
                transform: translate(-1px, 2px) rotate(-1deg);
            }

            60% {
                transform: translate(-3px, 1px) rotate(0deg);
            }

            70% {
                transform: translate(3px, 1px) rotate(-1deg);
            }

            80% {
                transform: translate(-1px, -1px) rotate(1deg);
            }

            90% {
                transform: translate(1px, 2px) rotate(0deg);
            }

            100% {
                transform: translate(1px, -2px) rotate(-1deg);
            }
        }

        .animate-shake {
            animation: shake 0.5s;
            animation-iteration-count: infinite;
        }
    </style>
</x-typing-app>