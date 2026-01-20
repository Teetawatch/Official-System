<x-typing-app :role="'student'" :title="'1v1 Competition'">
    <div class="h-screen flex flex-col -m-4 md:-m-6 lg:-m-8" x-data="typingMatch(@js($match))">
    <!-- Header / HUD -->
    <div class="bg-white border-b border-gray-200 shadow-sm z-10">
        <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Player 1 (You) -->
                <div class="flex items-center space-x-4 flex-1">
                    <div class="relative">
                        <img :src="player1.avatar" class="h-12 w-12 rounded-full border-2 border-indigo-500">
                        <span class="absolute -bottom-1 -right-1 bg-indigo-600 text-white text-xs px-1.5 py-0.5 rounded-full font-bold">YOU</span>
                    </div>
                    <div>
                        <div class="font-bold text-gray-900" x-text="player1.name"></div>
                        <div class="text-xs text-gray-500 font-mono">
                            <span x-text="player1.wpm">0</span> WPM | <span x-text="player1.accuracy">100</span>%
                        </div>
                    </div>
                </div>

                <!-- VS Badge / Timer -->
                <div class="flex flex-col items-center justify-center px-8">
                    <div class="text-3xl font-black italic text-gray-300 transform -skew-x-12" x-show="!finished">VS</div>
                    <div class="text-2xl font-black text-indigo-600" x-show="finished && winnerId == currentUserId">VICTORY!</div>
                    <div class="text-2xl font-black text-red-500" x-show="finished && winnerId && winnerId != currentUserId">DEFEAT</div>
                </div>

                <!-- Player 2 (Opponent) -->
                <div class="flex items-center justify-end space-x-4 flex-1 text-right">
                    <div>
                        <div class="font-bold text-gray-900" x-text="player2 ? player2.name : 'Waiting...'"></div>
                        <div class="text-xs text-gray-500 font-mono">
                            <span x-text="player2 ? player2.wpm : 0">0</span> WPM | <span x-text="player2 ? player2.progress : 0">0</span>%
                        </div>
                    </div>
                    <div class="relative">
                        <img :src="player2 ? player2.avatar : 'https://ui-avatars.com/api/?name=WP&background=eee&color=999'" class="h-12 w-12 rounded-full border-2" :class="player2 ? 'border-red-500' : 'border-gray-200'">
                    </div>
                </div>
            </div>

            <!-- Progress Bars -->
            <div class="mt-4 relative h-2 bg-gray-100 rounded-full overflow-hidden">
                <!-- P1 Bar -->
                <div class="absolute top-0 left-0 h-full bg-indigo-500 transition-all duration-300 ease-out" :style="`width: ${player1.progress}%`"></div>
                <!-- P2 Bar -->
                <div class="absolute top-0 left-0 h-full bg-red-400 opacity-70 transition-all duration-300 ease-out" :style="`width: ${player2 ? player2.progress : 0}%`"></div>
            </div>
        </div>
    </div>

    <!-- Main Game Area -->
    <div class="flex-1 overflow-y-auto bg-gray-50 p-4 flex flex-col items-center justify-center relative">
        
        <!-- Waiting Overlay -->
        <div x-show="!started" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white p-8 rounded-xl shadow-2xl text-center max-w-sm mx-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                <h3 class="text-xl font-bold text-gray-900">Waiting for opponent...</h3>
                <p class="text-gray-500 mt-2">The game will start automatically.</p>
            </div>
        </div>
        
        <!-- Game Completed Overlay -->
        <div x-show="finished" class="absolute inset-0 bg-indigo-900/80 backdrop-blur-md z-50 flex items-center justify-center" style="display: none;">
            <div class="bg-white p-10 rounded-2xl shadow-2xl text-center max-w-md mx-4 animate-bounce-in">
                <div x-show="winnerId == currentUserId">
                    <div class="text-6xl mb-4">🏆</div>
                    <h2 class="text-4xl font-black text-indigo-600 mb-2">YOU WIN!</h2>
                    <p class="text-gray-600 mb-6">+50 Points Awarded</p>
                </div>
                <div x-show="winnerId && winnerId != currentUserId">
                    <div class="text-6xl mb-4">😢</div>
                    <h2 class="text-4xl font-black text-gray-600 mb-2">YOU LOSE</h2>
                    <p class="text-gray-400 mb-6">+10 Participation Points</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-8 bg-gray-50 p-4 rounded-xl">
                    <div>
                        <div class="text-sm text-gray-500">WPM</div>
                        <div class="text-2xl font-bold text-gray-900" x-text="player1.wpm"></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Accuracy</div>
                        <div class="text-2xl font-bold text-gray-900"><span x-text="player1.accuracy"></span>%</div>
                    </div>
                </div>

                <a href="{{ route('typing.student.matches.index') }}" class="block w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-indigo-700 transition">
                    Play Again
                </a>
            </div>
        </div>

        <!-- Typing Container -->
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg border border-gray-200 p-8 relative">
            
            <div class="mb-4 text-gray-400 text-sm font-medium uppercase tracking-wider flex justify-between">
                <span>Type the text below</span>
                <span x-show="started && !finished" class="text-indigo-600 animate-pulse">Running...</span>
            </div>

            <!-- Text Display -->
            <div class="relative text-2xl leading-relaxed font-mono mb-8 select-none" style="min-height: 120px;">
                <div class="absolute inset-0 text-gray-300 pointer-events-none" x-text="targetText"></div>
                <div class="relative">
                    <span class="text-green-500" x-text="completedText"></span><span class="bg-indigo-100 border-b-2 border-indigo-500 text-indigo-900" x-text="currentChar"></span><span class="text-gray-800" x-text="remainingText"></span>
                </div>
            </div>

            <!-- Input Area (Hidden but focused) -->
            <textarea 
                x-ref="input"
                @input="handleInput" 
                @blur="$refs.input.focus()"
                class="opacity-0 absolute inset-0 z-20 cursor-default h-full w-full" 
                :disabled="!started || finished"
                autofocus
            ></textarea>

            <div class="text-center text-gray-400 text-sm mt-4">
                Click here and start typing to play
            </div>
        </div>
    </div>
</div>

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
            startTime: initialMatch.started_at ? new Date(initialMatch.started_at).getTime() : null,
            
            // Identify Roles
            myDbRole: (initialMatch.player1_id === {{ auth()->id() }}) ? 'player1' : 'player2',
            
            // My Stats (Visuals)
            player1: {
                id: {{ auth()->id() }},
                name: '{{ auth()->user()->name }}',
                avatar: '{{ auth()->user()->avatar_url }}',
                progress: 0,
                wpm: 0,
                accuracy: 100
            },
            
            // Opponent Stats (Visuals)
            player2: null, // Will init below

            // Typing Logic
            inputText: '',
            currIndex: 0,
            mistakes: 0,
            
            completedText: '',
            currentChar: initialMatch.text_content[0] || '',
            remainingText: initialMatch.text_content.slice(1),

            init() {
                // Initialize Opponent
                this.initOpponent(initialMatch);

                // Set initial progress if rejoining
                if (this.myDbRole === 'player1') {
                    this.player1.progress = initialMatch.player1_progress;
                    this.player1.wpm = initialMatch.player1_wpm || 0;
                    this.currIndex = Math.floor((initialMatch.player1_progress / 100) * this.targetText.length);
                } else {
                    this.player1.progress = initialMatch.player2_progress;
                    this.player1.wpm = initialMatch.player2_wpm || 0;
                    this.currIndex = Math.floor((initialMatch.player2_progress / 100) * this.targetText.length);
                }
                
                // Recalculate text display based on rejoined progress
                if (this.currIndex > 0) {
                   this.updateTextDisplay();
                   this.inputText = this.targetText.substring(0, this.currIndex); // Restore input (cheat a bit)
                }

                // Only focus if game already started
                if (this.started) {
                    this.$refs.input.focus();
                }
                
                // Poll immediately to get latest status, then poll every second
                this.pollStatus();
                this.pollInterval = setInterval(() => this.pollStatus(), 1000);

                if (this.started && !this.startTime) {
                    this.startTime = Date.now();
                }
            },

            initOpponent(matchData) {
                let opponentData = null;
                let opponentProgress = 0;
                let opponentWpm = 0;

                if (this.myDbRole === 'player1') {
                    // Opponent is DB player2
                    if (matchData.player2) {
                        opponentData = matchData.player2;
                        opponentProgress = matchData.player2_progress;
                        opponentWpm = matchData.player2_wpm;
                    }
                } else {
                    // Opponent is DB player1
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
                        avatar: opponentData.avatar_url || opponentData.avatar, // Handle structure variance
                        progress: opponentProgress,
                        wpm: opponentWpm || 0
                    };
                }
            },

            handleInput(e) {
                if (!this.started || this.finished) return;

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
                try {
                    const response = await fetch(`{{ url('/typing/student/matches') }}/${this.matchId}/status`);
                    const data = await response.json(); // returns { player1: {...}, player2: {...}, status: ... }

                    // Sync Opponent
                    if (this.myDbRole === 'player1') {
                        // Opponent is DB Player 2
                        if (data.player2) {
                            if (!this.player2) {
                                // Init opponent if they just joined
                                this.player2 = {
                                    id: data.player2.id,
                                    name: data.player2.name,
                                    avatar: data.player2.avatar,
                                    progress: data.player2.progress,
                                    wpm: data.player2.wpm
                                };
                            } else {
                                // Update stats
                                this.player2.progress = data.player2.progress;
                                this.player2.wpm = data.player2.wpm;
                            }
                        }
                    } else {
                        // Opponent is DB Player 1
                        if (data.player1) {
                             if (!this.player2) {
                                this.player2 = {
                                    id: data.player1.id,
                                    name: data.player1.name,
                                    avatar: data.player1.avatar,
                                    progress: data.player1.progress,
                                    wpm: data.player1.wpm
                                };
                             } else {
                                this.player2.progress = data.player1.progress;
                                this.player2.wpm = data.player1.wpm;
                             }
                        }
                    }

                    // Check game start
                    if (data.status === 'ongoing' && !this.started) {
                        this.started = true;
                        this.startTime = Date.now();
                        // Use $nextTick to ensure DOM updates before focusing
                        this.$nextTick(() => {
                            this.$refs.input.disabled = false;
                            this.$refs.input.focus();
                        });
                    }

                    // Check game finish
                    if (data.status === 'completed' && !this.finished) {
                        this.finished = true;
                        this.winnerId = data.winner_id;
                        clearInterval(this.pollInterval);
                    } else if (!this.finished) {
                        // Send my progress
                        this.sendProgress();
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
                this.winnerId = this.currentUserId; // Optimistic update
                
                await fetch(`{{ url('/typing/student/matches') }}/${this.matchId}/finish`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                clearInterval(this.pollInterval);
            }
        }));
    });
</script>
<style>
    @keyframes bounce-in {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); opacity: 1; }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
    .animate-bounce-in {
        animation: bounce-in 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
    }
</style>
</x-typing-app>
