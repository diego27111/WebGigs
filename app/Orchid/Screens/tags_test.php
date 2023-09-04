<?php

if ($request->has('tag')) {
            $selectedTag = $request->get('tag');
    
            // If the tag is already selected, unset the 'tag' parameter
            if ($selectedTag === session('selectedTag')) {
                $request->session()->forget('selectedTag');
                return redirect('/');
            } else {
                // Set the 'tag' parameter in the session
                $request->session()->put('selectedTag', $selectedTag);
    
                // Filter the listings by the selected tag
                $listings = $listings->filter(function ($listing) use ($selectedTag) {
                    return $listing->tags->contains('slug', $selectedTag);
                });
            }
        } else {
            // If no 'tag' parameter is present, check if there's a selected tag in the session
            $selectedTag = session('selectedTag');
    
            // If a selected tag is found in the session, filter the listings by that tag
            if ($selectedTag) {
                $listings = $listings->filter(function ($listing) use ($selectedTag) {
                    return $listing->tags->contains('slug', $selectedTag);
                });
            }
        }