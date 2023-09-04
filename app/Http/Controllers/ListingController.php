<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Listing;
use App\Models\Tag;

use \Illuminate\Support\Str;
//use App\Http\Controllers\Auth;
use Auth;
use App\Models\User;
use Hash;

class ListingController extends Controller
{
    public function index(Request $request) {
        // Start with the base query
        $listings = Listing::where('is_active', true)->with('tags');
    
        $tags = Tag::orderBy('name')->get();
    
        if ($request->has('s')) {
            $query = strtolower($request->get('s'));
            $listings = $listings->where(function ($queryBuilder) use ($query) {
                $queryBuilder->whereRaw('LOWER(title) LIKE ?', ['%' . $query . '%'])
                    ->orWhereRaw('LOWER(company) LIKE ?', ['%' . $query . '%'])
                    ->orWhereRaw('LOWER(location) LIKE ?', ['%' . $query . '%']);
            });
        }
    
        if ($request->has('tag')) {
            $selectedTag = $request->get('tag');

            if ($selectedTag === session('selectedTag')) {
                $request->session()->forget('selectedTag');
                return redirect('/');
            } else {

                $request->session()->put('selectedTag', $selectedTag);

                $listings = $listings->whereHas('tags', function ($queryBuilder) use ($selectedTag) {
                    $queryBuilder->where('slug', $selectedTag);
            });
        }
        } else {
            $selectedTag = session('selectedTag');

            if ($selectedTag) {
                $listings = $listings->whereHas('tags', function ($queryBuilder) use ($selectedTag) {
                    $queryBuilder->where('slug', $selectedTag);
                });
            }
        }
    
        $sortBy = $request->query('sort_by', 'high_to_low');
    
        if ($sortBy === 'low_to_high') {
            $listings = $listings->orderByRaw('CAST(REPLACE(salary, ",", "") AS SIGNED) ASC');
        } elseif ($sortBy === 'high_to_low') {
            $listings = $listings->orderByRaw('CAST(REPLACE(salary, ",", "") AS SIGNED) DESC');
        } elseif ($sortBy === 'date_newest') {
            $listings = $listings->orderBy('created_at', 'desc');
        } elseif ($sortBy === 'date_oldest') {
            $listings = $listings->orderBy('created_at', 'asc');
        } // No need for an "else" block for "all" since it's already filtered
    
        // Execute the final query and retrieve the results
        $listings = $listings->get();
    
        return view('index', compact('listings', 'tags'));
    }
    
    public function show(Listing $listing, Request $request) {
        return view('show', compact('listing'));
    }

    public function apply (Listing $listing, Request $request) {
        $listing->clicks()->create([
            'user_agent'=> $request->userAgent(),
            'ip' => $request->ip()
        ]);

        return redirect()->to($listing->apply_link);
    }

    public function create() {
        return view('create');
    }

    public function store(Request $request) {

        $validationArray =[
            'title' => 'required',
            'company' => 'required',
            'logo' => 'file|max:2048',
            'location' => 'required',
            'apply_link' => 'required|url',
            'content' => 'required',
            'payment_method_id' => 'required',
            'salary' => 'required|string',
        ];

        if(!Auth::check()) {
            $validationArray = array_merge($validationArray, [
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:5',
                'name' => 'required'
            ]);
        }

        $request->validate($validationArray);

        // is a user signed in? if not, create one and authenticate
        $user = Auth::user();

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user->createAsStripeCustomer();

            Auth::login($user);
        }

        // process the payment and create the listing
        try {
            $amount = 9900; // $99.00 USD in cents
            if ($request->filled('is_highlighted')) {
                $amount += 1900;
            }

            $user->charge($amount, $request->payment_method_id);

            $md = new \ParsedownExtra();

            $listing = $user->listings()
                ->create([
                    'title' => $request->title,
                    'slug' => Str::slug($request->title) . '-' . rand(1111, 9999),
                    'company' => $request->company,
                    'logo' => basename($request->file('logo')->store('public')),
                    'location' => $request->location,
                    'apply_link' => $request->apply_link,
                    'content' => $md->text($request->input('content')),
                    'is_highlighted' => $request->filled('is_highlighted'),
                    'is_active' => true,
                    'salary' => $request->salary,
                ]);

            foreach(explode(',', $request->tags) as $requestTag) {
                $tag = Tag::firstOrCreate([
                    'slug' => Str::slug(trim($requestTag))
                ], [
                    'name' => ucwords(trim($requestTag))
                ]);

                $tag->listings()->attach($listing->id);

                $tag->increment('used');
            }

            return redirect()->route('dashboard');
        } catch(\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Listing $listing) {
        return view('edit', compact('listing'));
    }


    // Fix this
    public function update(Request $request, Listing $listing) {
            // Make sure logged in user is owner
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $validationArray =[
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'apply_link' => 'url',
            'content' => '',
            'payment_method_id' => '',
            'salary' => '',
        ];

        if(!Auth::check()) {
            $validationArray = array_merge($validationArray, [
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:5',
                'name' => 'required'
            ]);
        }



        //////////////////
        $validatedData = $request->validate($validationArray);

        if ($request->hasFile('logo')) {
            $logoPath = basename($request->file('logo')->store('public'));
            $validatedData['logo'] = $logoPath;
        }
        

        $listing->update($validatedData);

        $tagNames = collect(explode(',', $request->input('tags')))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->unique();

        $tagIds = [];

        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName], ['slug' => Str::slug(trim($tagName))]);
            $tagIds[] = $tag->id;
            $tag->increment('used');
        }

        $listing->tags()->sync($tagIds);


        return redirect('/')->with('message', 'Listing Updated successfully!');
        
    }

    public function destroy(Listing $listing) {

        if($listing->user_id != auth()->id()) {
            abort(403, 'Unothorized Action');
        }

        $listing->delete();

        return redirect('/')->with('message', 'Listing Deleted Successfully');
    }
}
