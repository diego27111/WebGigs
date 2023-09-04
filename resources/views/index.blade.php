<x-app-layout>
    <x-hero></x-hero>

    <section class="container px-5 py-12 mx-auto">
        <div class="mb-12 justify-center">
            <div class="flex justify-center">
                @php
                    $mostUsedTags = \App\Models\Tag::orderBy('used', 'desc')->take(10)->get();
                    $selectedTag = request()->session()->get('selectedTag');
                @endphp

                @foreach($mostUsedTags as $tag)

                @php
                    $isSelectedTag = $tag->slug === $selectedTag;
                @endphp
                    <a href="{{ route('listings.index', ['tag' => $tag->slug]) }}" class="mb-2 rounded inline-block ml-2 tracking-wide text-s font-medium title-font py-0.5 px-1.5 border border-indigo-500 uppercase {{ $isSelectedTag ? 'bg-indigo-500 text-white' : 'bg-white text-indigo-500'}}" data-tag-slug="{{ $tag->slug }}">                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="mb-12 flex justify-between items-center">
            <h2 class="text-2xl font-medium text-gray-900 title-font px-4">All jobs ({{$listings->count() }})</h2>
            <div class="relative inline-block text-left">
                <div>
                    <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring focus:ring-offset-2 focus:ring-indigo-500" id="sorting-options-menu" aria-haspopup="true" aria-expanded="true">
                        @php
                            $sortBy =  request()->query('sort_by', 'high_to_low');
                            $sortText = '';

                            if ($sortBy === 'low_to_high') {
                                $sortText =  'Salary: lowest first';
                            } elseif ($sortBy === 'high_to_low') {
                                $sortText =  'Salary: highest first';
                            } elseif ($sortBy === 'date_newest') {
                                $sortText =  'Posted: newest first';
                            } elseif ($sortBy === 'date_oldest') {
                                $sortText =  'Posted: oldest first';
                            } else {
                                $sortText = 'ERROR';
                            }

                        @endphp
                        <div>
                            <label class="grid block justify-items-start text-gray-500 text-xs">Sort By: </label>
                            <span class="block text-base mr-6">{{ $sortText }}</span>
                        </div>
                        <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
                <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden" role="menu" aria-orientation="vertical" aria-labelledby="sorting-options-menu" id="sorting-options-dropdown" tabindex="-1">
                    <div class="py-1 text-base" role="none"> 
                        <a href="{{ route('listings.index', ['sort_by' => 'low_to_high']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100 hover:text-indigo-900 text-base" role="menuitem">Salary: lowest first</a>
                        <a href="{{ route('listings.index', ['sort_by' => 'high_to_low']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100 hover:text-indigo-900 text-base" role="menuitem">Salary: highest first</a>
                        <a href="{{ route('listings.index', ['sort_by' => 'date_newest']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100 hover:text-indigo-900 text-base" role="menuitem">Posted: newest first</a>
                        <a href="{{ route('listings.index', ['sort_by' => 'date_oldest']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100 hover:text-indigo-900 text-base" role="menuitem">Posted: oldest first</a>

                    </div>
                </div>
            </div>
        </div>

        <div class="my-6">
            @foreach($listings as $listing)
            <a href="{{route('listings.show', $listing->slug)}}" class="py-6 px-4 flex flex-wrap md:flex-nowrap border-b border-gray-300 {{ $listing->is_highlighted ? '' : 'bg-white hover:bg-gray-100'}}">
                <div class="md:w-16 md:mb-0 mb-6 mr-4 flex-shrink-0 flex flex-col">
                    <img src="{{ asset('storage/' . $listing->logo) }}" alt="No Logo" class="w-16 h-16 rounded-full object-cover">
                </div>
                <div class="md:w-1/2 mr-8 flex flex-col items-start justify-center">
                    <h2 class="text-xl font-bold text-[#0073bd] title-font mb-1">{{ $listing->title }} &mdash; <span class="text-green-600 text-sm">  US${{ $listing->salary}}</span></h2>
                    <p class="leading-relax text-gray-900">
                        {{ $listing->company}} &mdash; <span class="text-gray-600">{{ $listing->location}}</span>
                    </p>
                </div>
                <div class="md:flex-grow mr-8 flex items-center justify-start">
                    @foreach($listing->tags as $tag)
                        <span class=" rounded inline-block ml-2 tracking-wide text-xs font-medium title-font py-0.5 px-1.5 border border-indigo-500 uppercase {{ $tag->slug == request()->get('tag') ? 'bg-indigo-500 text-white' : 'bg-white text-indigo-500'}}">
                            {{$tag->name}}
                        </span>
                    @endforeach
                </div>
                <span class="md:flex-grow flex items-center justify-end">
                    <span>{{ $listing->created_at->diffForHumans()}}</span>
                </span>
            </a>
            @endforeach
        </div>
    </section>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sortingOptionsButton = document.getElementById('sorting-options-menu');
        const sortingOptionsMenu = document.getElementById('sorting-options-dropdown');

        sortingOptionsButton.addEventListener('click', function () {
            sortingOptionsMenu.classList.toggle('hidden');
            sortingOptionsButton.setAttribute('aria-expanded', sortingOptionsMenu.classList.contains('hidden') ? 'false' : 'true');
        });

        document.addEventListener('click', function (event) {
            if (!sortingOptionsButton.contains(event.target) && !sortingOptionsMenu.contains(event.target)) {
                sortingOptionsMenu.classList.add('hidden');
                sortingOptionsButton.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>