<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Listing;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;


class dataLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = '';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            Layout::metrics([
                'Total Users'  => 'metrics.sales', // Use the userCount value from the query
            ]),
        ];   
    }
}
