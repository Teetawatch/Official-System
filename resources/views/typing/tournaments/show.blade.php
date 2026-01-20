@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $tournament->name }}</h1>
                <p class="text-gray-500 mt-1">{{ $tournament->description }}</p>
                <div class="mt-4 flex items-center gap-4">
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                        Status: {{ ucfirst($tournament->status) }}
                    </span>
                    <span class="text-sm text-gray-400">
                        Participants: {{ $tournament->participants->count() }} / {{ $tournament->max_participants }}
                    </span>
                </div>
            </div>
            
            <div class="flex gap-3">
                @if($tournament->status === 'open')
                    @if(!$tournament->participants->contains(Auth::id()))
                        <form action="{{ route('tournaments.join', $tournament->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transform transition hover:scale-105">
                                Join Tournament
                            </button>
                        </form>
                    @else
                        <button disabled class="bg-green-600 text-white font-bold py-2 px-6 rounded-lg opacity-75 cursor-not-allowed">
                            Joined ✓
                        </button>
                    @endif

                    @if((Auth::user()->role === 'admin' || Auth::user()->role === 'teacher') && $tournament->participants->count() >= 2)
                         {{-- Minimum 2 for testing, normally max_participants --}}
                        <form action="{{ route('tournaments.join', $tournament->id) }}" method="POST"> 
                            {{-- Wait, join route used for joining. Setup separate start route --}}
                            {{-- For now I put start logic in join if full, but let's make explicit button --}}
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <!-- Bracket Visualization -->
        <div class="overflow-x-auto pb-12">
            <div class="min-w-max flex gap-16 px-4">
                @php
                    $rounds = [1 => 'Round of 16', 2 => 'Quarter Finals', 3 => 'Semi Finals', 4 => 'Final'];
                    $matchCounts = [1 => 8, 2 => 4, 3 => 2, 4 => 1]; // Taking 16 participants assumption
                @endphp

                @foreach($rounds as $roundNum => $roundName)
                    <div class="flex flex-col justify-around gap-8">
                        <h3 class="text-center text-lg font-bold text-gray-400 mb-4 sticky top-0 bg-gray-100 dark:bg-gray-900 py-2">{{ $roundName }}</h3>
                        
                        @for($i = 0; $i < $matchCounts[$roundNum]; $i++)
                            @php
                                $match = $matchesByRound->get($roundNum)?->firstWhere('bracket_index', $i);
                            @endphp
                            
                            <div class="w-64 bg-white dark:bg-gray-800 border {{ $match ? ($match->status == 'completed' ? 'border-green-500' : 'border-indigo-500') : 'border-gray-300 dark:border-gray-700 dashed' }} rounded-lg shadow-sm p-4 relative flex flex-col justify-center gap-2" style="min-height: 100px;">
                                
                                {{-- Connector lines could go here --}}

                                @if($match)
                                    <!-- Player 1 -->
                                    <div class="flex justify-between items-center {{ $match->winner_id == $match->player1_id ? 'font-bold text-green-600' : '' }}">
                                        <span class="truncate flex items-center gap-2">
                                            @if($match->player1)
                                                <img src="{{ $match->player1->avatar_url }}" class="w-6 h-6 rounded-full">
                                                {{ $match->player1->name }}
                                            @else
                                                <span class="text-gray-400 italic">Waiting...</span>
                                            @endif
                                        </span>
                                        @if($match->player1_wpm)
                                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-1 rounded">{{ $match->player1_wpm }}</span>
                                        @endif
                                    </div>

                                    <div class="h-px bg-gray-200 dark:bg-gray-700 w-full my-1"></div>

                                    <!-- Player 2 -->
                                    <div class="flex justify-between items-center {{ $match->winner_id == $match->player2_id ? 'font-bold text-green-600' : '' }}">
                                        <span class="truncate flex items-center gap-2">
                                            @if($match->player2)
                                                <img src="{{ $match->player2->avatar_url }}" class="w-6 h-6 rounded-full">
                                                {{ $match->player2->name }}
                                            @else
                                                <span class="text-gray-400 italic">Waiting...</span>
                                            @endif
                                        </span>
                                        @if($match->player2_wpm)
                                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-1 rounded">{{ $match->player2_wpm }}</span>
                                        @endif
                                    </div>

                                    <!-- Match Action -->
                                    @if($match->status !== 'completed')
                                        @if(Auth::id() == $match->player1_id || Auth::id() == $match->player2_id)
                                            <a href="{{ route('typing.matches.show', $match->id) }}" class="absolute -right-3 -top-3 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg" title="Play Match">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </a>
                                        @endif
                                    @endif
                                @else
                                    <div class="text-center text-gray-400 text-sm italic py-2">
                                        TBD
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
