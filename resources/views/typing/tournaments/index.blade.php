@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">🏆 Tournaments</h1>
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'teacher')
                    <a href="{{ route('tournaments.create') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        + Create Tournament
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tournaments as $tournament)
                        <div
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $tournament->name }}
                                        </h2>
                                        <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $tournament->description }}</p>
                                    </div>
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $tournament->status === 'open' ? 'bg-green-100 text-green-800' :
                    ($tournament->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($tournament->status) }}
                                    </span>
                                </div>

                                <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        {{ $tournament->participants_count }} / {{ $tournament->max_participants }}
                                    </span>
                                    <span>{{ $tournament->created_at->format('M d, Y') }}</span>
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('tournaments.show', $tournament->id) }}"
                                        class="block w-full text-center bg-gray-900 dark:bg-gray-700 text-white py-2 rounded-md hover:bg-gray-800 transition-colors">
                                        View Bracket
                                    </a>
                                </div>
                            </div>
                        </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-500">
                        No tournaments found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection