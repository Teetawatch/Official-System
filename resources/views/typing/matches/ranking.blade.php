<x-typing-app :role="'student'" :title="'1v1 Battle Arena - Rankings'">
    <div class="min-h-screen bg-[#0f172a] text-white overflow-hidden relative">
        <!-- Background Effects -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointing-events-none">
            <div
                class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-indigo-600/20 rounded-full blur-[100px] animate-pulse-slow">
            </div>
            <div
                class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-purple-600/20 rounded-full blur-[100px] animate-pulse-slow delay-1000">
            </div>
            <div
                class="absolute top-[40%] left-[50%] transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-500/10 rounded-full blur-[120px]">
            </div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header -->
            <div class="text-center mb-16 relative">
                <div class="inline-block relative">
                    <h1
                        class="text-5xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 mb-4 tracking-tight drop-shadow-lg">
                        BATTLE RANKINGS
                    </h1>
                    <div class="absolute -top-6 -right-8 text-yellow-400 text-4xl animate-bounce">👑</div>
                </div>
                <p class="text-gray-400 text-xl max-w-2xl mx-auto font-light">
                    ทำเนียบผู้แข็งแกร่งที่สุดในสมรภูมิความเร็ว 1v1 ใครจะเป็นเจ้าแห่งคีย์บอร์ด?
                </p>

                <div class="mt-8 flex justify-center gap-4">
                    <a href="{{ route('typing.student.matches.index') }}"
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-full font-bold transition-all shadow-lg hover:shadow-indigo-500/50 flex items-center gap-2 group">
                        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        กลับสู่ลานประลอง
                    </a>
                </div>
            </div>

            <!-- Top 3 Podium -->
            @if($topPlayers->count() > 0)
                <div class="flex flex-col md:flex-row items-end justify-center gap-4 mb-20 px-4 min-h-[400px]">
                    <!-- Rank 2 -->
                    <div class="order-2 md:order-1 flex-1 max-w-[280px] w-full group">
                        @if(isset($topPlayers[1]))
                            <div class="flex flex-col items-center">
                                <div class="relative mb-4 transition-transform group-hover:-translate-y-2 duration-300">
                                    <div
                                        class="w-20 h-20 md:w-24 md:h-24 rounded-full p-[3px] bg-gradient-to-b from-gray-300 to-gray-500 shadow-xl shadow-gray-500/20">
                                        <img src="{{ $topPlayers[1]['avatar'] }}"
                                            class="w-full h-full rounded-full object-cover border-2 border-[#0f172a]">
                                    </div>
                                    <div
                                        class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-gray-700 text-gray-200 font-bold px-3 py-0.5 rounded-full border border-gray-500 text-sm shadow-lg">
                                        #2</div>
                                </div>
                                <div
                                    class="w-full bg-gradient-to-b from-gray-700/80 to-gray-800/80 backdrop-blur-md rounded-t-2xl p-6 text-center border-t border-gray-600 h-[220px] flex flex-col items-center justify-start pt-8 relative overflow-hidden transition-all duration-300 group-hover:shadow-[0_0_30px_rgba(156,163,175,0.3)]">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60">
                                    </div>
                                    <h3 class="font-bold text-lg text-white mb-1 relative z-10 truncate w-full px-2">
                                        {{ $topPlayers[1]['name'] }}</h3>
                                    <div class="flex items-center gap-2 text-gray-300 text-sm mb-3 relative z-10 font-mono">
                                        <span class="text-green-400 font-bold">{{ $topPlayers[1]['wins'] }}</span> Wins
                                    </div>
                                    <div
                                        class="grid grid-cols-2 gap-2 w-full mt-auto relative z-10 bg-gray-900/40 rounded-lg p-2">
                                        <div>
                                            <div class="text-[10px] text-gray-400 uppercase">Win Rate</div>
                                            <div class="text-sm font-bold text-blue-400">{{ $topPlayers[1]['win_rate'] }}%</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-400 uppercase">Best WPM</div>
                                            <div class="text-sm font-bold text-yellow-500">{{ $topPlayers[1]['best_wpm'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Rank 1 -->
                    <div class="order-1 md:order-2 flex-1 max-w-[320px] w-full z-20 -mt-12 group">
                        @if(isset($topPlayers[0]))
                            <div class="flex flex-col items-center">
                                <div class="relative mb-6 transition-transform group-hover:-translate-y-2 duration-300">
                                    <i
                                        class="fas fa-crown text-5xl text-yellow-400 absolute -top-12 left-1/2 -translate-x-1/2 drop-shadow-[0_0_15px_rgba(250,204,21,0.6)] animate-bounce-slow"></i>
                                    <div
                                        class="w-28 h-28 md:w-36 md:h-36 rounded-full p-[4px] bg-gradient-to-b from-yellow-300 via-yellow-500 to-orange-600 shadow-2xl shadow-yellow-500/40">
                                        <img src="{{ $topPlayers[0]['avatar'] }}"
                                            class="w-full h-full rounded-full object-cover border-4 border-[#0f172a]">
                                    </div>
                                    <div
                                        class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-black px-5 py-1 rounded-full border border-yellow-400 text-base shadow-lg shadow-orange-900/50">
                                        #1</div>
                                </div>
                                <div
                                    class="w-full bg-gradient-to-b from-yellow-600/20 to-orange-900/40 backdrop-blur-md rounded-t-3xl p-6 text-center border-t-2 border-yellow-500/50 h-[280px] flex flex-col items-center justify-start pt-10 relative overflow-hidden transition-all duration-300 group-hover:shadow-[0_0_50px_rgba(234,179,8,0.4)]">
                                    <div
                                        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10">
                                    </div>
                                    <div
                                        class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-yellow-500/10 to-transparent">
                                    </div>

                                    <h3 class="font-black text-2xl text-white mb-2 relative z-10 truncate w-full px-2">
                                        {{ $topPlayers[0]['name'] }}</h3>
                                    <div class="flex items-center gap-2 text-yellow-200 text-lg mb-6 relative z-10 font-mono">
                                        <i class="fas fa-trophy"></i> <span
                                            class="font-bold">{{ $topPlayers[0]['wins'] }}</span> Wins
                                    </div>
                                    <div
                                        class="flex justify-between w-full mt-auto relative z-10 bg-black/40 rounded-xl p-3 backdrop-blur-sm border border-yellow-500/20">
                                        <div class="text-center flex-1 border-r border-white/10">
                                            <div class="text-[10px] text-gray-400 uppercase tracking-wider">Win Rate</div>
                                            <div class="text-xl font-black text-white">{{ $topPlayers[0]['win_rate'] }}%</div>
                                        </div>
                                        <div class="text-center flex-1">
                                            <div class="text-[10px] text-gray-400 uppercase tracking-wider">Best WPM</div>
                                            <div class="text-xl font-black text-yellow-400">{{ $topPlayers[0]['best_wpm'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Rank 3 -->
                    <div class="order-3 md:order-3 flex-1 max-w-[280px] w-full group">
                        @if(isset($topPlayers[2]))
                            <div class="flex flex-col items-center">
                                <div class="relative mb-4 transition-transform group-hover:-translate-y-2 duration-300">
                                    <div
                                        class="w-20 h-20 md:w-24 md:h-24 rounded-full p-[3px] bg-gradient-to-b from-orange-400 to-amber-700 shadow-xl shadow-orange-700/20">
                                        <img src="{{ $topPlayers[2]['avatar'] }}"
                                            class="w-full h-full rounded-full object-cover border-2 border-[#0f172a]">
                                    </div>
                                    <div
                                        class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-amber-900 text-amber-100 font-bold px-3 py-0.5 rounded-full border border-amber-600 text-sm shadow-lg">
                                        #3</div>
                                </div>
                                <div
                                    class="w-full bg-gradient-to-b from-amber-900/40 to-orange-950/40 backdrop-blur-md rounded-t-2xl p-6 text-center border-t border-amber-700/50 h-[200px] flex flex-col items-center justify-start pt-8 relative overflow-hidden transition-all duration-300 group-hover:shadow-[0_0_30px_rgba(245,158,11,0.2)]">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-60">
                                    </div>
                                    <h3 class="font-bold text-lg text-white mb-1 relative z-10 truncate w-full px-2">
                                        {{ $topPlayers[2]['name'] }}</h3>
                                    <div class="flex items-center gap-2 text-gray-300 text-sm mb-3 relative z-10 font-mono">
                                        <span class="text-green-400 font-bold">{{ $topPlayers[2]['wins'] }}</span> Wins
                                    </div>
                                    <div
                                        class="grid grid-cols-2 gap-2 w-full mt-auto relative z-10 bg-gray-900/40 rounded-lg p-2">
                                        <div>
                                            <div class="text-[10px] text-gray-400 uppercase">Win Rate</div>
                                            <div class="text-sm font-bold text-blue-400">{{ $topPlayers[2]['win_rate'] }}%</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-400 uppercase">Best WPM</div>
                                            <div class="text-sm font-bold text-yellow-500">{{ $topPlayers[2]['best_wpm'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left: Full Leaderboard -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-gray-800/50 backdrop-blur-xl rounded-3xl border border-gray-700/50 overflow-hidden shadow-2xl">
                        <div class="p-6 border-b border-gray-700/50 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <i class="fas fa-list-ol text-blue-500"></i> Top Fighters
                            </h3>
                            <span class="text-xs text-gray-400 bg-gray-700 px-2 py-1 rounded">SS 2024</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr
                                        class="bg-gray-900/30 text-gray-400 text-xs uppercase font-medium tracking-wider">
                                        <th class="px-6 py-4 rounded-tl-lg">Rank</th>
                                        <th class="px-6 py-4">Player</th>
                                        <th class="px-6 py-4 text-center">Wins</th>
                                        <th class="px-6 py-4 text-center hidden sm:table-cell">Lose</th>
                                        <th class="px-6 py-4 text-center hidden md:table-cell">W/L Rate</th>
                                        <th class="px-6 py-4 text-right rounded-tr-lg">Best WPM</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700/30">
                                    @foreach($topPlayers as $player)
                                        <tr
                                            class="hover:bg-white/5 transition-colors {{ $player['id'] === auth()->id() ? 'bg-indigo-500/10 border-l-4 border-indigo-500' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-bold text-gray-400 font-mono">
                                                    @if($player['rank'] <= 3)
                                                        <i
                                                            class="fas fa-medal text-{{ $player['rank'] == 1 ? 'yellow' : ($player['rank'] == 2 ? 'gray' : 'orange') }}-400"></i>
                                                    @else
                                                        #{{ $player['rank'] }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <img class="h-10 w-10 rounded-full border border-gray-600"
                                                        src="{{ $player['avatar'] }}" alt="">
                                                    <div>
                                                        <div
                                                            class="font-bold text-white {{ $player['id'] === auth()->id() ? 'text-indigo-400' : '' }}">
                                                            {{ $player['name'] }}</div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $player['class_name'] ?? 'Classroom' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-green-400 font-bold text-lg">{{ $player['wins'] }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center hidden sm:table-cell">
                                                <span class="text-red-400/70">{{ $player['losses'] }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center hidden md:table-cell">
                                                <div class="w-16 h-2 bg-gray-700 rounded-full overflow-hidden mx-auto">
                                                    <div class="h-full bg-blue-500"
                                                        style="width: {{ $player['win_rate'] }}%"></div>
                                                </div>
                                                <span
                                                    class="text-xs text-gray-400 mt-1 block">{{ $player['win_rate'] }}%</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span
                                                    class="text-yellow-400 font-bold font-mono text-lg">{{ $player['best_wpm'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right: Stats & Recent -->
                <div class="flex flex-col gap-6">
                    <!-- My Stats Card -->
                    <div
                        class="bg-gradient-to-br from-indigo-900/80 to-purple-900/80 backdrop-blur-xl rounded-3xl p-6 border border-indigo-500/30 shadow-lg relative overflow-hidden group">
                        <div
                            class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/30 rounded-full blur-2xl group-hover:bg-indigo-400/40 transition-all duration-500">
                        </div>

                        <h3 class="text-lg font-bold text-white mb-6 relative z-10">Your Battle Stats</h3>

                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 rounded-full p-1 bg-gradient-to-tr from-indigo-400 to-purple-500">
                                <img src="{{ auth()->user()->avatar_url }}"
                                    class="w-full h-full rounded-full border-2 border-[#0f172a]">
                            </div>
                            <div>
                                <div class="text-xs text-indigo-300 font-bold uppercase tracking-wide">Current Rank
                                </div>
                                <div class="text-2xl font-black text-white">
                                    {{ $myStats['rank'] ? '#' . $myStats['rank'] : 'UNRANKED' }}
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-black/30 rounded-xl p-3">
                                <div class="text-xs text-gray-400">Wins</div>
                                <div class="text-xl font-bold text-green-400">{{ $myStats['wins'] }}</div>
                            </div>
                            <div class="bg-black/30 rounded-xl p-3">
                                <div class="text-xs text-gray-400">Total Bets</div>
                                <div class="text-xl font-bold text-white">{{ $myStats['wins'] + $myStats['losses'] }}
                                </div>
                            </div>
                            <div class="bg-black/30 rounded-xl p-3 col-span-2">
                                <div class="text-xs text-gray-400">Personal Best</div>
                                <div class="flex items-end gap-2">
                                    <div class="text-3xl font-black text-yellow-400 leading-none">
                                        {{ $myStats['best_wpm'] }}</div>
                                    <div class="text-sm text-yellow-600 font-bold mb-1">WPM</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Matches -->
                    <div class="bg-gray-800/40 backdrop-blur-xl rounded-3xl p-6 border border-gray-700/50">
                        <h3 class="text-lg font-bold text-white mb-4">Recent Battles</h3>
                        <div class="space-y-3">
                            @forelse($recentMatches as $match)
                                <div
                                    class="bg-gray-900/40 rounded-xl p-3 flex items-center justify-between border-l-4 {{ $match['won'] ? 'border-green-500' : 'border-red-500' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-700 overflow-hidden">
                                            @if($match['opponent'])
                                                <img src="{{ $match['opponent']['avatar'] }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-xs text-gray-500">
                                                    ?</div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-200">vs
                                                {{ $match['opponent']['name'] ?? 'Unknown' }}</div>
                                            <div class="text-[10px] text-gray-500">{{ $match['date'] }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div
                                            class="text-sm font-bold {{ $match['won'] ? 'text-green-400' : 'text-red-400' }}">
                                            {{ $match['won'] ? 'VICTORY' : 'DEFEAT' }}
                                        </div>
                                        <div class="text-[10px] text-gray-400">{{ $match['my_wpm'] }} WPM</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 py-4">No recent matches found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-pulse-slow {
            animation: pulse-slow 8s infinite;
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(1);
            }

            50% {
                opacity: 0.6;
                transform: scale(1.1);
            }
        }

        .animate-bounce-slow {
            animation: bounce-slow 3s infinite;
        }

        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0) translateX(-50%);
            }

            50% {
                transform: translateY(-10px) translateX(-50%);
            }
        }
    </style>
</x-typing-app>