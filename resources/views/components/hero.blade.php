<section class="text-gray-600 body-font border-b border-gray-100">
    <div class="container mx-auto flex flex-col px-5 pt-16 pb-8 justify-center items-center">
        <div class="w-full md:w-2/3 flex flex-col items-center text-center">
            <h1 class="title-font sm:text-4xl text-3xl mb-10 font-medium text-gray-900">Top jobs in Web Development</h1>

            <!-- <p class="mb-8 leading-relaxed">Whether you're looking to move to a new role or just seeing what's available, we've gathered this comprehensive list of open positions from a variety of companies and locations for you to choose from.</p> -->
            <form class="flex w-full justify-center items-end" action="{{ route('listings.index') }}" method="get">
                <div class="grid grid-cols-2 gap-4 w-full lg:w-1/2">
                    <div class="relative text-left">
                        <span class="font-bold text-black text-lg">Keyword</span>
                        <input type="text" placeholder="Job Title, Skill or Company Name" id="s" name="s" value="{{ request()->get('s') }}" class="w-full bg-gray-100 bg-opacity-50 rounded focus:ring-2 focus:ring-indigo-200 focus:bg-transparent border border-gray-300 focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                <div class="relative text-left">
                    <span class="font-bold text-black text-lg">Location</span>
                    <input type="text" placeholder="Country, State or City " id="s" name="s" value="{{ request()->get('s') }}" class="w-full bg-gray-100 bg-opacity-50 rounded focus:ring-2 focus:ring-indigo-200 focus:bg-transparent border border-gray-300 focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
                </div>
    <button class="inline-flex text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg ml-4">Find Jobs</button>
</form>

            <!-- <p class="text-sm mt-2 text-gray-500 mb-8 w-full">fullstack php, vue and node, react native</p> -->
        </div>
    </div>
</section>