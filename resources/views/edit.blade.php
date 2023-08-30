<x-app-layout>
    <section class="text-gray-600 body-font overflow-hidden">
        <div class="w-full md:w-1/2 py-24 mx-auto">
            <div class="mb-4">
                <h2 class="text-2xl font-medium text-gray-900 title-font">
                    Edit Listing
                </h2>
            </div>
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-200 text-red-800">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('listing.update', $listing) }}" class="bg-gray-100 p-4" method="post" enctype="multipart/form-data">
                @csrf    
                @method('PUT')
                
                <div class="mb-4 mx-2">
                    <x-input-label for="title" value="Job Title"></x-input-label>
                    <x-input
                        id="title"
                        class="block mt-1 w-full"
                        type="text"
                        name="title"
                        value=" {{ $listing->title }}"
                    ></x-input>
                </div>

                <div class="mb-4 mx-2">
                    <x-input-label for="company" value="Company Name"></x-input-label>
                    <x-input
                        id="company"
                        class="block mt-1 w-full"
                        type="text"
                        name="company"
                        value=" {{ $listing->company }}"
                    ></x-input>
                </div>
                
                <div class="mb-4 mx-2">
                    <x-input-label for="logo" value="Company Logo"></x-input-label>
                    <x-input
                        id="logo"
                        class="block mt-1 w-full"
                        type="file"
                        name="logo"
                        value="{{ $listing->logo }}"
                    ></x-input>
                </div>

                <div class="mb-4 mx-2">
                    <x-input-label for="location" value="Location"></x-input-label>
                    <x-input
                        id="location"
                        class="block mt-1 w-full"
                        type="text"
                        name="location"
                        value=" {{ $listing->location }}"
                    ></x-input>
                </div>

                <div class="mb-4 mx-2">
                    <x-input-label for="appky_link" value="Link To Apply"></x-input-label>
                    <x-input
                        id="apply_link"
                        class="block mt-1 w-full"
                        type="text"
                        name="apply_link"
                        value=" {{ $listing->apply_link }}"
                    ></x-input>
                </div>

                <div class="mb-4 mx-2">
                    <x-input-label for="tags" value="Tags (seperate by comma)"></x-input-label>
                    <x-input
                        id="tags"
                        class="block mt-1 w-full"
                        type="text"
                        name="tags"
                        value=" {{ $listing->tags }}"
                    ></x-input>
                </div>

                <div class="mb-4 mx-2">
                    <x-input-label for="content" value="Listing Content (Markdown is okay)"></x-input-label>
                    <x-input
                        id="content"
                        class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full"
                        name="content"
                        rows="8"
                        value=" {{ $listing->content }}"
                    ></x-input>
                </div>

                <div class="mb-4 mx-2">
                    <input-label for="is_highlighted" class="inline-flex items-center font-medium text-sm text-gray-700">
                    <input
                        id="title"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50"
                        type="checkbox"
                        name="is_highlighted"
                        value=" {{ $listing->title }}"
                        
                    >
                    <span class="ml-2">Highlight this post (extra $19)</span>

                    </input-label>
                </div>
                <button type="submit" id="form_submit" class="block w-full items-center bg-indigo-500 text-white border-0 py-2 focus:outline-none hover:bg-indigo-600 rounded text-base mt-4 md:mt-0">Pay + Continue</button>

            </form>
        </div>
    </section>
</x-app-layout>
