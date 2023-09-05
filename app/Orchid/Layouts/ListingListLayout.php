<?php

namespace App\Orchid\Layouts;

use App\Models\Listing;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

use Orchid\Screen\Fields\Input;

class ListingListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    public $target = 'listings';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('title', 'Title')
                ->sort()
                ->filter(Input::make())
                ->render(function (Listing $listing) {
                    return Link::make($listing->title)
                    ->route('platform.listing.edit', $listing);
                }),
            
            TD::make('company', 'Company')->sort(),
            TD::make('created_at', 'Created')->filter(Input::make())->sort(),
        ];
    }
}
