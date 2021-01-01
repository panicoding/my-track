<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <div class="space-y-5">

                    <div>
                        <h2 class="mb-3 font-semibold tracking-wide text-purple-600 uppercase sm:text-lg sm:leading-snug">You</h2>
                        @if ($player = Auth::user()->currentlyPlaying())
                            <div class="relative self-center pt-6 pl-8 sm:px-6 md:px-8 md:pt-8 lg:px-0 lg:pt-0">
                                <div class="relative z-10 rounded-xl sm:rounded-xl lg:rounded-xl">
                                    <div class="p-4 pb-6 space-y-6 transition-colors duration-500 bg-white dark:bg-gray-800 rounded-xl sm:rounded-xl sm:p-8 lg:p-4 lg:pb-6 xl:p-8 sm:space-y-8 lg:space-y-6 xl:space-y-8">
                                        <div class="flex items-center space-x-3.5 sm:space-x-5 lg:space-x-3.5 xl:space-x-5">
                                            <img src="{{ $player['item']['album']['images'][0]['url'] }}" loading="lazy" alt="" width="160" height="160" class="flex-none w-20 h-20 bg-gray-100 rounded-lg">
                                            <div class="min-w-0 flex-auto space-y-0.5">
                                                <p class="text-sm font-semibold uppercase transition-colors duration-500 text-lime-600 dark:text-lime-400 sm:text-base lg:text-sm xl:text-base">
                                                    {{ $player['item']['album']['name'] }}
                                                </p>
                                                <h2 class="text-base font-semibold text-black truncate transition-colors duration-500 dark:text-white sm:text-xl lg:text-base xl:text-xl">
                                                    {{ $player['item']['name'] }}
                                                </h2>
                                                <p class="text-base font-medium text-gray-500 transition-colors duration-500 dark:text-gray-400 sm:text-lg lg:text-base xl:text-lg">
                                                    by {{ collect($player['item']['artists'])->pluck('name')->implode(', ') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="overflow-hidden transition-colors duration-500 bg-gray-200 rounded-full dark:bg-black">
                                                <div class="bg-lime-500 transition-colors duration-500 dark:bg-lime-400 w-1/2 h-1.5" role="progressbar" aria-valuenow="1456" aria-valuemin="0" aria-valuemax="4550"></div>
                                            </div>
                                            <div class="flex justify-between text-sm font-medium text-gray-500 transition-colors duration-500 dark:text-gray-400 tabular-nums">
                                                <div>{{ $player['progress_ms'] }}</div>
                                                <div>{{ $player['item']['duration_ms'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <h2 class="mb-3 font-semibold tracking-wide text-purple-600 uppercase sm:text-lg sm:leading-snug">Friends</h2>
                        @forelse (Auth::user()->facebookFriends() as $friend)
                            <div class="relative self-center pt-6 pl-8 sm:px-6 md:px-8 md:pt-8 lg:px-0 lg:pt-0">
                                <div class="relative z-10 rounded-xl sm:rounded-xl lg:rounded-xl">
                                    <div class="p-4 pb-6 space-y-6 transition-colors duration-500 bg-white dark:bg-gray-800 rounded-xl sm:rounded-xl sm:p-8 lg:p-4 lg:pb-6 xl:p-8 sm:space-y-8 lg:space-y-6 xl:space-y-8">
                                        <div class="flex items-center space-x-3.5 sm:space-x-5 lg:space-x-3.5 xl:space-x-5">
                                            <img src="{{ $friend['item']['album']['images'][0]['url'] }}" loading="lazy" alt="" width="160" height="160" class="flex-none w-20 h-20 bg-gray-100 rounded-lg">
                                            <div class="min-w-0 flex-auto space-y-0.5">
                                                <p class="text-sm font-semibold uppercase transition-colors duration-500 text-lime-600 dark:text-lime-400 sm:text-base lg:text-sm xl:text-base">
                                                    {{ $friend['item']['album']['name'] }}
                                                </p>
                                                <h2 class="text-base font-semibold text-black truncate transition-colors duration-500 dark:text-white sm:text-xl lg:text-base xl:text-xl">
                                                    {{ $friend['item']['name'] }}
                                                </h2>
                                                <p class="text-base font-medium text-gray-500 transition-colors duration-500 dark:text-gray-400 sm:text-lg lg:text-base xl:text-lg">
                                                    by {{ collect($friend['item']['artists'])->pluck('name')->implode(', ') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="overflow-hidden transition-colors duration-500 bg-gray-200 rounded-full dark:bg-black">
                                                <div class="bg-lime-500 transition-colors duration-500 dark:bg-lime-400 w-1/2 h-1.5" role="progressbar" aria-valuenow="1456" aria-valuemin="0" aria-valuemax="4550"></div>
                                            </div>
                                            <div class="flex justify-between text-sm font-medium text-gray-500 transition-colors duration-500 dark:text-gray-400 tabular-nums">
                                                <div>24:16</div>
                                                <div>75:50</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            No current playing
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
