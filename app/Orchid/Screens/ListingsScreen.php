<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;

use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;
use App\Models\Listing;
use Illuminate\Http\Request;

use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;
use \Illuminate\Support\Str;



class ListingsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'listings' => Listing::latest()->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Listings';
    }

    public function description(): ?string {
        return 'Manage all the listings';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add Listing')
                ->modal('listingModal')
                ->method('create')
                ->icon('plus'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [

            Layout::table('listings', [
                TD::make('title'),

                TD::make('company'),

                TD::make('location'),

                TD::make('apply_link'),

                TD::make('content'),

                TD::make('Actions')
                    ->alignRight()
                    ->render(function (Listing $listing) {
                        return Button::make('Delete Listing')
                        ->confirm('After deleting, the listing will be gone forever')
                        ->method('delete', ['listing' => $listing->id]);
                    }),
            ]),

            Layout::modal('listingModal', Layout::rows([
                Input::make('listing.title')
                    ->title('Title')
                    ->placeholder('Enter listing Title')
                    ->help('The Title of the listing to be created'),
                
                Input::make('listing.company')
                    ->title('Company')
                    ->placeholder('Enter Company Name')
                    ->help('The company name of the listing to be created'),

                Input::make('listing.location')
                    ->title('Location')
                    ->placeholder('Enter the companys location')
                    ->help('The Location of the company to be created'),
                
                Input::make('listing.apply_link')
                    ->title('Apply Link')
                    ->placeholder('Enter the Apply Link')
                    ->help('Link to apply fro the position'),

                Input::make('listing.content')
                    ->title('Content')
                    ->placeholder('Enter Description')
                    ->help('The Description of the company'),

            ]))
            ->title('Create Listing')
            ->applyButton('Add Listing')
        ];
    }

    public function create(Request $request) {

        // Validate form data, save listing to database
        $request->validate([
            'listing.title' => 'required|max:225',
            'listing.company' => 'required',
            'listing.location' => 'required',
            'listing.apply_link' => 'required',
            'listing.content' => 'required',
        ]);

        $listing = new Listing();
        $listing->title = $request->input('listing.title');
        $listing->user_id = 21;
        $listing->slug = Str::slug($request->input('listing.title')) . '-' . rand(1111, 9999);
        $listing->company = $request->input('listing.company');
        $listing->location = $request->input('listing.location');
        $listing->content = $request->input('listing.content');
        $listing->apply_link = $request->input('listing.apply_link');
        $listing->save();
    }

    public function delete(Listing $listing) {
        $listing->delete();
    }
}
