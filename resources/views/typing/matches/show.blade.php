<x-typing-app :role="'student'" :title="'1v1 Battle Arena'">
    <div class="h-screen flex flex-col -m-4 md:-m-6 lg:-m-8 bg-[#0f172a] text-white overflow-hidden relative"
        x-data="typingMatch(@js($match))">

        <!-- Background Ambient -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div
                class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[100px] animate-pulse-slow">
            </div>
            <div
                class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[100px] animate-pulse-slow delay-700">
            </div>
        </div>

        <!-- Canvas for specific effects (Confetti) -->
        <canvas id="confetti-canvas" class="absolute inset-0 z-50 pointer-events-none"></canvas>

        <!-- Header / HUD -->
        <div class="bg-[#1e293b]/80 backdrop-blur-md border-b border-white/5 shadow-lg z-20 sticky top-0">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <!-- Player 1 (You) -->
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="relative group">
                            <div
                                class="absolute inset-0 bg-indigo-500 rounded-full blur opacity-40 group-hover:opacity-60 transition-opacity">
                            </div>
                            <img :src="player1.avatar"
                                class="relative h-14 w-14 rounded-full border-2 border-indigo-400 shadow-lg object-cover">
                            <span
                                class="absolute -bottom-2 -right-1 bg-indigo-600 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow border border-indigo-400 tracking-wider">YOU</span>
                        </div>
                        <div>
                            <div class="font-bold text-lg text-white" x-text="player1.name"></div>
                            <div class="text-xs text-indigo-300 font-mono flex gap-3">
                                <span class="bg-indigo-900/50 px-2 py-0.5 rounded border border-indigo-500/30">
                                    <span x-text="player1.wpm" class="font-bold text-white">0</span> WPM
                                </span>
                                <span class="bg-indigo-900/50 px-2 py-0.5 rounded border border-indigo-500/30">
                                    <span x-text="player1.accuracy" class="font-bold text-white">100</span>%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- VS Badge / Timer -->
                    <div class="flex flex-col items-center justify-center px-8 relative">
                        <div class="absolute inset-0 bg-indigo-500/20 blur-xl rounded-full" x-show="!finished"></div>
                        
                        <!-- VS Badge (Only show if there is an opponent and no timer override) -->
                        <div class="text-4xl font-black italic text-transparent bg-clip-text bg-gradient-to-r from-gray-200 to-gray-400 transform -skew-x-12 relative z-10"
                            x-show="!finished && !timeLimit && player2">VS</div>
                        
                        <!-- Solo Badge (If no opponent) -->
                        <div class="text-2xl font-black italic text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-indigo-300 transform -skew-x-12 relative z-10"
                            x-show="!finished && !timeLimit && !player2">SOLO</div>

                        <!-- Timer (If time limit exists) -->
                        <div class="flex flex-col items-center" x-show="!finished && timeLimit">
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Time</span>
                            <span class="text-3xl font-black text-white font-mono" 
                                  :class="timeLeft <= 10 ? 'text-red-500 animate-pulse' : ''"
                                  x-text="formatTime(timeLeft)"></span>
                        </div>
                        <div class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500 drop-shadow-[0_0_10px_rgba(234,179,8,0.5)] animate-bounce"
                            x-show="finished && winnerId == currentUserId">VICTORY!</div>
                        <div class="text-3xl font-black text-gray-500 drop-shadow-lg"
                            x-show="finished && winnerId && winnerId != currentUserId">DEFEAT</div>
                    </div>

                    <!-- Player 2 (Opponent) -->
                    <div class="flex items-center justify-end space-x-4 flex-1 text-right">
                        <div>
                            <div class="font-bold text-white" x-text="player2 ? player2.name : 'Waiting...'"></div>
                            <div class="text-xs text-gray-400 font-mono flex gap-3 justify-end" x-show="player2">
                                <span class="bg-gray-800/50 px-2 py-0.5 rounded border border-gray-600/30">
                                    <span x-text="player2 ? player2.wpm : 0" class="font-bold text-gray-200">0</span>
                                    WPM
                                </span>
                                <span class="bg-gray-800/50 px-2 py-0.5 rounded border border-gray-600/30">
                                    <span x-text="player2 ? player2.progress : 0"
                                        class="font-bold text-gray-200">0</span>%
                                </span>
                            </div>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-0 bg-red-500 rounded-full blur opacity-0 transition-opacity"
                                :class="player2 ? 'opacity-40' : ''"></div>
                            <img :src="player2 ? player2.avatar : 'https://ui-avatars.com/api/?name=WP&background=1e293b&color=94a3b8'"
                                class="relative h-14 w-14 rounded-full border-2 bg-gray-900 object-cover shadow-lg duration-300"
                                :class="player2 ? 'border-red-500' : 'border-gray-600 animate-pulse'">
                        </div>
                        
                        <!-- Surrender Button -->
                        <button @click="leaveMatch()" x-show="started && !finished" class="ml-4 text-gray-500 hover:text-red-500 transition-colors" title="Surrender / Leave">
                            <i class="fas fa-sign-out-alt fa-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Battle Progress Bars -->
                <div
                    class="mt-6 relative h-4 bg-gray-900/50 rounded-full overflow-hidden shadow-inner border border-white/5">
                    <!-- Grid Lines -->
                    <div class="absolute inset-0 flex justify-between px-2">
                        <div class="w-px h-full bg-white/5"></div>
                        <div class="w-px h-full bg-white/5"></div>
                        <div class="w-px h-full bg-white/5"></div>
                        <div class="w-px h-full bg-white/5"></div>
                    </div>

                    <!-- P1 Bar -->
                    <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-indigo-600 to-blue-500 shadow-[0_0_15px_rgba(59,130,246,0.5)] transition-all duration-300 ease-out flex items-center justify-end pr-2"
                        :style="`width: ${player1.progress}%`">
                        <div class="w-1 h-full bg-white/50 blur-[1px]"></div>
                    </div>

                    <!-- P2 Bar -->
                    <div class="absolute top-0 left-0 h-full bg-red-500/60 shadow-[0_0_15px_rgba(239,68,68,0.3)] transition-all duration-300 ease-out z-0"
                        :style="`width: ${player2 ? player2.progress : 0}%`">
                        <div
                            class="w-full h-full bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNCIgaGVpZ2h0PSI0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xIDNoMXYxHDF6IiBmaWxsPSJyZ2JhKDAsMCwwLDAuMikiIGZpbGwtcnVsZT0iZXZlbm9kZCIvPjwvc3ZnPg==')] opacity-50">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Game Area -->
        <div class="flex-1 overflow-y-auto p-4 flex flex-col items-center justify-center relative z-10">

            <!-- Waiting Overlay -->
            <div x-show="!started && !countdown"
                class="absolute inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center transition-all duration-500">
                <div
                    class="bg-[#1e293b] p-10 rounded-2xl shadow-2xl border border-gray-700 text-center max-w-sm mx-4 relative overflow-hidden">
                    <div
                        class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent animate-scan">
                    </div>

                    <div class="relative">
                        <div
                            class="animate-spin rounded-full h-16 w-16 border-4 border-indigo-500/30 border-t-indigo-500 mx-auto mb-6">
                        </div>
                        <div
                            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-indigo-400 text-xs font-bold">
                            VS</div>
                    </div>

                    <h3 class="text-2xl font-black text-white mb-2 tracking-tight">WAITING...</h3>
                    
                    <template x-if="player2">
                        <p class="text-gray-400 mb-4">รอผู้เล่นทุกคนพร้อม... (<span x-text="readyCount">0</span>/2)</p>
                    </template>
                    <template x-if="!player2">
                        <div>
                            <p class="text-gray-400 mb-2" x-show="!startTime && (others.length > 0)">กําลังรอนักเรียนคนอื่นๆ เข้าห้อง... (<span x-text="others.length + 1"></span> คน)</p>
                            <p class="text-indigo-400 font-bold mb-4 animate-pulse">รอครูผู้สอนกดเริ่มการแข่งขันพร้อมกัน</p>
                        </div>
                    </template>
                    
                    <div class="flex gap-4 justify-center text-xs font-mono text-gray-500">
                         <div :class="player1.ready ? 'text-green-400' : 'text-gray-500'">
                             <span x-text="player2 ? 'YOU (' + player1.name + ')' : 'READY STATUS: JOINED'"></span>
                         </div>
                         <div x-show="player2" :class="player2 && player2.ready ? 'text-green-400' : 'text-gray-500'">OPPONENT</div>
                    </div>

                    <button @click="leaveMatch()" class="mt-4 text-red-400 hover:text-red-300 text-sm font-medium underline transition-colors">
                        ออกจากการแข่งขัน
                    </button>
                </div>
            </div>

            <!-- Countdown Overlay -->
            <div x-show="countdown > 0" 
                class="absolute inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center pointer-events-none"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-150">
                <div class="text-center">
                    <div class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-br from-yellow-300 to-yellow-600 animate-pulse drop-shadow-[0_0_30px_rgba(234,179,8,0.5)]"
                         x-text="countdown">3</div>
                    <div class="text-white font-bold text-2xl mt-4 tracking-widest uppercase">Get Ready</div>
                </div>
            </div>
            


            <!-- Game Completed Overlay -->
            <div x-show="finished"
                class="absolute inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md"
                style="display: none;">
                <div
                    class="bg-[#1e293b] p-12 rounded-3xl shadow-2xl border border-white/10 text-center max-w-md mx-4 animate-bounce-in relative overflow-hidden">
                    <!-- Winner Effects -->
                    <div x-show="winnerId == currentUserId"
                        class="absolute inset-0 bg-gradient-to-b from-yellow-500/10 to-transparent pointer-events-none">
                    </div>

                    <div x-show="winnerId == currentUserId" class="relative z-10">
                        <div class="text-7xl mb-4 drop-shadow-[0_0_25px_rgba(234,179,8,0.6)] animate-pulse">🏆</div>
                        <h2
                            class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-b from-yellow-300 to-yellow-600 mb-2 drop-shadow-sm tracking-tight text-stroke-gold">
                            YOU WIN!</h2>
                        <p class="text-yellow-100/80 mb-8 font-medium text-lg">+50 Points Awarded</p>

                        <!-- New Record Badge -->
                        <div x-show="isNewRecord" class="mb-8 animate-rubberBand flex justify-center">
                            <div
                                class="bg-gradient-to-r from-pink-500 to-rose-500 text-white px-4 py-2 rounded-xl font-bold transform -rotate-2 shadow-lg border border-white/20 flex items-center gap-2">
                                <i class="fas fa-fire animate-pulse text-yellow-300"></i> NEW WPM RECORD!
                            </div>
                        </div>
                    </div>

                    <div x-show="winnerId && winnerId != currentUserId" class="relative z-10">
                        <div class="text-7xl mb-4 grayscale opacity-70">☠️</div>
                        <h2 class="text-5xl font-black text-gray-500 mb-2 tracking-tight">DEFEAT</h2>
                        <p class="text-gray-500 mb-8 font-medium">Keep practicing! (+10 Points)</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-8 bg-[#0f172a] p-4 rounded-2xl border border-gray-700/50">
                        <div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Speed</div>
                            <div class="text-3xl font-black text-white" x-text="player1.wpm"></div>
                            <div class="text-[10px] text-gray-500">WPM</div>
                        </div>
                        <div class="border-l border-gray-700">
                            <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Accuracy</div>
                            <div class="text-3xl font-black text-white"><span x-text="player1.accuracy"></span><span
                                    class="text-base font-normal text-gray-500">%</span></div>
                            <div class="text-[10px] text-gray-500">Precision</div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <a href="{{ route('typing.student.matches.index') }}"
                            class="block w-full bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold py-4 px-6 rounded-xl hover:from-indigo-500 hover:to-blue-500 transition-all shadow-lg hover:shadow-indigo-500/25 transform hover:-translate-y-1">
                            Play Again
                        </a>
                        <a href="{{ route('typing.student.matches.ranking') }}"
                            class="block w-full bg-[#0f172a] text-gray-300 font-bold py-3 px-6 rounded-xl hover:bg-gray-800 transition-colors border border-gray-700">
                            View Rankings
                        </a>
                    </div>
                </div>
            </div>

            <!-- Typing Container -->
            <div
                class="w-full max-w-4xl bg-[#1e293b]/50 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-700/50 p-8 relative md:p-12">

                <div class="mb-6 flex justify-between items-center text-sm font-medium tracking-wider">
                    <span class="text-gray-400 uppercase">Input Stream</span>
                    <span x-show="started && !finished" class="flex items-center gap-2 text-green-400">
                        <span class="relative flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        RECORDING
                    </span>
                </div>

                <!-- Text Display -->
                <div class="relative text-2xl md:text-3xl leading-relaxed font-mono mb-8 select-none font-medium"
                    style="min-height: 140px;">
                    <!-- Review/Active text (Unified Layer) -->
                    <div class="relative break-words whitespace-pre-wrap">
                        <span
                            class="text-gray-100 drop-shadow-[0_0_8px_rgba(255,255,255,0.3)] transition-colors duration-100"
                            x-text="completedText"></span><span
                            class="bg-indigo-500/30 text-indigo-200 rounded border-b-2 border-indigo-500 animate-pulse"
                            x-text="currentChar"></span><span class="text-gray-700" x-text="remainingText"></span>
                    </div>
                </div>

                <!-- Input Area (Hidden but focused) -->
                <textarea x-ref="input" @input="handleInput" @blur="$refs.input.focus()"
                    class="opacity-0 absolute inset-0 z-20 cursor-default h-full w-full"
                    :disabled="!started || finished" autofocus spellcheck="false"></textarea>

                <div class="text-center text-gray-500 text-sm mt-8 flex items-center justify-center gap-2 opacity-50">
                    <i class="fas fa-keyboard"></i> Focus mode active
                </div>
            </div>
            <!-- Peer Progress (Classroom Battle Only) -->
            <div x-show="!player2 && others.length > 0" class="w-full max-w-4xl mt-6">
                <div class="bg-[#1e293b]/30 backdrop-blur-sm rounded-xl border border-white/5 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-users text-indigo-400"></i> เพื่อนร่วมชั้นเรียน (Top 5)
                        </h4>
                        <span class="text-[10px] text-gray-500" x-text="others.length + ' others in race'"></span>
                    </div>
                    
                    <div class="space-y-3">
                        <template x-for="(other, index) in others.sort((a,b) => b.progress - a.progress).slice(0, 5)" :key="index">
                            <div class="relative">
                                <div class="flex items-center justify-between text-[10px] mb-1 px-1">
                                    <span class="text-gray-300 flex items-center gap-1">
                                        <img :src="other.avatar" class="w-4 h-4 rounded-full">
                                        <span x-text="other.name"></span>
                                    </span>
                                    <span class="text-indigo-400 font-bold" x-text="other.progress + '%'"></span>
                                </div>
                                <div class="h-1 bg-gray-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-500/50 transition-all duration-500" :style="`width: ${other.progress}%`"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('typingMatch', (initialMatch) => ({
                matchId: initialMatch.id,
                currentUserId: {{ auth()->id() }},
                targetText: initialMatch.text_content,

                // Game State
                started: initialMatch.status === 'ongoing',
                finished: initialMatch.status === 'completed',
                winnerId: initialMatch.winner_id,
                isNewRecord: false,
                startTime: initialMatch.started_at ? new Date(initialMatch.started_at).getTime() : null,
                timeLimit: initialMatch.time_limit || null,
                timeLeft: 0,
                timerInterval: null,

                // Identify Roles
                myDbRole: (initialMatch.player1_id === {{ auth()->id() }}) ? 'player1' : 'player2',

                player1: {
                    id: {{ auth()->id() }},
                    name: '{{ auth()->user()->name }}',
                    avatar: '{{ auth()->user()->avatar_url }}',
                    progress: 0,
                    wpm: 0,
                    accuracy: 100,
                    ready: initialMatch.player1_ready || false
                },
                
                others: [],
                countdown: 0,

                get readyCount() {
                    let count = 0;
                    if (this.player1.ready) count++;
                    if (this.player2 && this.player2.ready) count++;
                    return count;
                },

                player2: null,

                // Typing Logic
                inputText: '',
                currIndex: 0,
                mistakes: 0,

                completedText: '',
                currentChar: initialMatch.text_content[0] || '',
                remainingText: initialMatch.text_content.slice(1),

                pollInterval: null,

                init() {
                    this.initOpponent(initialMatch);

                    if (this.myDbRole === 'player1') {
                        this.player1.progress = initialMatch.player1_progress;
                        this.player1.wpm = initialMatch.player1_wpm || 0;
                        this.currIndex = Math.floor((initialMatch.player1_progress / 100) * this.targetText.length);
                    } else {
                        this.player1.progress = initialMatch.player2_progress;
                        this.player1.wpm = initialMatch.player2_wpm || 0;
                        this.currIndex = Math.floor((initialMatch.player2_progress / 100) * this.targetText.length);
                    }

                    if (this.currIndex > 0) {
                        this.updateTextDisplay();
                        this.inputText = this.targetText.substring(0, this.currIndex);
                    }

                    if (this.started) {
                        // Check if we are still in countdown
                        if (initialMatch.started_at) {
                             const start = new Date(initialMatch.started_at).getTime();
                             const now = Date.now(); // We don't have server time yet, approximate
                             if (start > now) {
                                 this.countdown = Math.ceil((start - now) / 1000);
                             } else {
                                this.$nextTick(() => this.$refs.input.focus());
                             }
                        } else {
                           this.$nextTick(() => this.$refs.input.focus());
                        }
                    }

                    this.pollStatus();
                    this.pollInterval = setInterval(() => {
                        this.pollStatus();
                        if (this.started && !this.finished) { // Independent check
                           this.checkTimeLimit();
                        }
                    }, 1000);

                    if (this.started && !this.startTime) {
                        this.startTime = Date.now();
                    }
                },

                initOpponent(matchData) {
                    let opponentData = null;
                    let opponentProgress = 0;
                    let opponentWpm = 0;

                    if (this.myDbRole === 'player1') {
                        if (matchData.player2) {
                            opponentData = matchData.player2;
                            opponentProgress = matchData.player2_progress;
                            opponentWpm = matchData.player2_wpm;
                        }
                    } else {
                        if (matchData.player1) {
                            opponentData = matchData.player1;
                            opponentProgress = matchData.player1_progress;
                            opponentWpm = matchData.player1_wpm;
                        }
                    }

                    if (opponentData) {
                        this.player2 = {
                            id: opponentData.id,
                            name: opponentData.name,
                            avatar: opponentData.avatar_url || opponentData.avatar,
                            progress: opponentProgress,
                            wpm: opponentWpm || 0,
                            ready: opponentData.ready || false
                        };
                    }
                },

                startInput() {
                    if (this.finished) return;
                    this.startTime = Date.now();
                    this.$nextTick(() => {
                        this.$refs.input.disabled = false;
                        this.$refs.input.focus();
                    });
                },

                async leaveMatch() {
                    if (this.started && !this.finished) {
                        if (!confirm('คุณต้องการยอมแพ้การแข่งขันนี้ใช่หรือไม่?')) return;
                    }

                    try {
                        await fetch('{{ route('typing.student.matches.cancel') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        window.location.href = "{{ route('typing.student.matches.index') }}";
                    } catch (e) {
                        console.error(e);
                    }
                },

                handleInput(e) {
                    if (!this.started || this.finished || this.countdown > 0) return;

                    const inputVal = e.target.value;
                    const charTyped = inputVal.slice(-1);
                    const targetChar = this.targetText[this.currIndex];

                    if (e.inputType === 'deleteContentBackward') {
                        e.target.value = this.inputText;
                        return;
                    }

                    if (charTyped === targetChar) {
                        this.inputText += charTyped;
                        this.currIndex++;
                        this.updateTextDisplay();
                        this.calculateStats();

                        if (this.currIndex >= this.targetText.length) {
                            this.finishGame();
                        }
                    } else {
                        this.mistakes++;
                        e.target.value = this.inputText;
                        this.calculateStats();

                        // Shake effect on error (simple visual cue could be added here)
                        this.$el.classList.add('shake');
                        setTimeout(() => this.$el.classList.remove('shake'), 200);
                    }
                },

                updateTextDisplay() {
                    this.completedText = this.targetText.substring(0, this.currIndex);
                    this.currentChar = this.targetText[this.currIndex] || '';
                    this.remainingText = this.targetText.substring(this.currIndex + 1);
                    this.player1.progress = Math.floor((this.currIndex / this.targetText.length) * 100);
                },

                calculateStats() {
                    if (!this.startTime) return;

                    const timeSpentMin = (Date.now() - this.startTime) / 60000;
                    if (timeSpentMin > 0) {
                        const words = this.currIndex / 5;
                        this.player1.wpm = Math.round(words / timeSpentMin);
                    }

                    const totalTyped = this.currIndex + this.mistakes;
                    if (totalTyped > 0) {
                        this.player1.accuracy = Math.round((this.currIndex / totalTyped) * 100);
                    }
                },

                async pollStatus() {
                    if (this.finished) return;

                    try {
                        const response = await fetch(`{{ url('/typing/student/matches') }}/${this.matchId}/status`);
                        const data = await response.json();

                        if (this.myDbRole === 'player1') {
                            if (data.player2) {
                                if (!this.player2) {
                                    this.player2 = {
                                        id: data.player2.id,
                                        name: data.player2.name,
                                        avatar: data.player2.avatar,
                                        progress: data.player2.progress,
                                        wpm: data.player2.wpm,
                                        ready: data.player2.ready
                                    };
                                } else {
                                    this.player2.ready = data.player2.ready;
                                    this.player2.progress = data.player2.progress;
                                    this.player2.wpm = data.player2.wpm;
                                }
                            }
                        } else {
                            this.player1.ready = data.player1.ready; // Update my ready status if I just refreshed
                            if (data.player1) {
                                if (!this.player2) {
                                    this.player2 = {
                                        id: data.player1.id,
                                        name: data.player1.name,
                                        avatar: data.player1.avatar,
                                        progress: data.player1.progress,
                                        wpm: data.player1.wpm,
                                        ready: data.player1.ready
                                    };
                                } else {
                                    this.player2.ready = data.player1.ready;
                                    this.player2.progress = data.player1.progress;
                                    this.player2.wpm = data.player1.wpm;
                                }
                            }
                        }

                        if (data.status === 'ongoing') {
                            if (data.started_at && data.server_time) {
                                const startAt = new Date(data.started_at).getTime();
                                const serverNow = data.server_time;
                                const diff = startAt - serverNow;
                                
                                if (diff > 0) {
                                    this.countdown = Math.ceil(diff / 1000);
                                    this.started = true;
                                    if (this.$refs.input) this.$refs.input.blur();
                                } else {
                                    const wasCountingDown = this.countdown > 0;
                                    this.countdown = 0;
                                    
                                    if (!this.started || wasCountingDown) {
                                        this.started = true;
                                        // Only start input if we haven't already
                                        if (!this.startTime || wasCountingDown) {
                                            this.startTime = Date.now();
                                            this.$nextTick(() => {
                                                this.$refs.input.disabled = false;
                                                this.$refs.input.focus();
                                            });
                                        }
                                    }
                                }
                            } else if (!this.started) {
                                // Fallback (no server time sync)
                                this.started = true;
                                this.startTime = Date.now();
                                this.$nextTick(() => {
                                    this.$refs.input.disabled = false;
                                    this.$refs.input.focus();
                                });
                            }
                        }

                        if (data.status === 'completed' && !this.finished) {
                            this.finished = true;
                            this.winnerId = data.winner_id;
                            this.handleGameEnd();
                            clearInterval(this.pollInterval);
                        } else if (!this.finished) {
                            this.sendProgress();
                        }

                        // Update Others Progress
                        if (data.others) {
                            this.others = data.others;
                        }

                    } catch (error) {
                        console.error('Poll error', error);
                    }
                },

                async sendProgress() {
                    await fetch(`{{ url('/typing/student/matches') }}/${this.matchId}/progress`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            progress: this.player1.progress,
                            wpm: this.player1.wpm,
                            accuracy: this.player1.accuracy
                        })
                    });
                },

                async finishGame() {
                    this.finished = true;
                    this.winnerId = this.currentUserId;

                    try {
                        const response = await fetch(`{{ url('/typing/student/matches') }}/${this.matchId}/finish`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                wpm: this.player1.wpm
                            })
                        });

                        const data = await response.json();
                        if (data.is_new_record) {
                            this.isNewRecord = true;
                        }
                    } catch (e) {
                        console.error(e);
                    }

                    this.handleGameEnd();
                    clearInterval(this.pollInterval);
                },

                handleGameEnd() {
                    if (this.winnerId == this.currentUserId) {
                        this.fireConfetti();
                    }
                },

                formatTime(seconds) {
                    if (seconds < 0) return '0:00';
                    const m = Math.floor(seconds / 60);
                    const s = seconds % 60;
                    return `${m}:${s.toString().padStart(2, '0')}`;
                },

                checkTimeLimit() {
                    if (!this.started || this.finished || !this.timeLimit || !this.startTime) return;

                    const elapsedSeconds = Math.floor((Date.now() - this.startTime) / 1000);
                    this.timeLeft = Math.max(0, this.timeLimit - elapsedSeconds);

                    if (this.timeLeft <= 0) {
                        this.finishGame();
                    }
                },

                fireConfetti() {
                    var count = 200;
                    var defaults = {
                        origin: { y: 0.7 }
                    };

                    function fire(particleRatio, opts) {
                        confetti(Object.assign({}, defaults, opts, {
                            particleCount: Math.floor(count * particleRatio)
                        }));
                    }

                    fire(0.25, { spread: 26, startVelocity: 55 });
                    fire(0.2, { spread: 60 });
                    fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
                    fire(0.1, { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
                    fire(0.1, { spread: 120, startVelocity: 45 });
                }
            }));
        });
    </script>
    <style>
        @keyframes bounce-in {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            50% {
                transform: scale(1.05);
                opacity: 1;
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
            }
        }

        .animate-bounce-in {
            animation: bounce-in 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both;
        }

        .animate-pulse-slow {
            animation: pulse 6s infinite;
        }

        .text-stroke-gold {
            -webkit-text-stroke: 1px rgba(234, 179, 8, 0.3);
        }

        .animate-rubberBand {
            animation: rubberBand 1s backwards;
            animation-delay: 1s;
        }

        @keyframes rubberBand {
            from {
                transform: scale3d(1, 1, 1);
            }

            30% {
                transform: scale3d(1.25, 0.75, 1);
            }

            40% {
                transform: scale3d(0.75, 1.25, 1);
            }

            50% {
                transform: scale3d(1.15, 0.85, 1);
            }

            65% {
                transform: scale3d(0.95, 1.05, 1);
            }

            75% {
                transform: scale3d(1.05, 0.95, 1);
            }

            to {
                transform: scale3d(1, 1, 1);
            }
        }

        .animate-scan {
            animation: scan 2s linear infinite;
        }

        @keyframes scan {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .shake {
            animation: shake 0.2s cubic-bezier(.36, .07, .19, .97) both;
        }

        @keyframes shake {

            10%,
            90% {
                transform: translate3d(-1px, 0, 0);
            }

            20%,
            80% {
                transform: translate3d(2px, 0, 0);
            }

            30%,
            50%,
            70% {
                transform: translate3d(-4px, 0, 0);
            }

            40%,
            60% {
                transform: translate3d(4px, 0, 0);
            }
        }
    </style>
</x-typing-app>