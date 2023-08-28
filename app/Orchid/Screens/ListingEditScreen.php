<?php


namespace App\Orchid\Screens;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class ListingEditScreen extends Screen
{
    /**
     * @var Listing
     */
    public $listing;

    /**
     * Query data.
     *
     * @param Listing $listing
     *
     * @return array
     */
    public function query(Listing $listing): array
    {
        return [
            'listing' => $listing
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers
     */
    public function name(): ?string
    {
        return $this->listing->exists ? 'Edit listing' : 'Creating a new listing';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Blog listings";
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Create listing')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->listing->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->listing->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->listing->exists),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('listing.title')
                    ->title('Title')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this listing.'),

                Relation::make('listing.company')
                    ->title('Author')
                    ->fromModel(User::class, 'name'),

                Quill::make('listing.content')
                    ->title('Main text'),

            ])
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $this->listing->fill($request->get('listing'))->save();

        Alert::info('You have successfully created a listing.');

        return redirect()->route('platform.listing.list');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove()
    {
        $this->listing->delete();

        Alert::info('You have successfully deleted the listing.');

        return redirect()->route('platform.listing.list');
    }
}