<?php


namespace App\Orchid\Screens;

use App\Models\Listing;
use App\Models\User;
use App\Models\Tag;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Picture;

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
        $listing->load('attachment');

        return [
            'listing' => Listing::filters()->defaultSort('id')->paginate()
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
        return "";
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
                    ->title('Job Title')
                    ->help('Specify a short descriptive title for this listing.'),
                
                Picture::make('picture')
                    ->storage('listing.logo'),
                    // ->targetRelativeUrl()
                    // ->targetId(),

                Input::make('listing.company')
                    ->title('Company')
                    ->fromModel(User::class, 'company'),
                
                Input::make('listing.location')
                    ->title('Location')
                    ->fromModel(User::class, 'location'),
                
                Input::make('listing.salary')
                    ->title('Salary in USD')
                    ->fromModel(User::class, 'salary'),

                Input::make('listing.apply_link')
                    ->title('Link to apply')
                    ->fromModel(User::class, 'apply_link'),

                Input::make('tags')
                    ->title('Tags')
                    ->fromModel(Tag::class, 'name', 'name'),

                Quill::make('listing.content')
                    ->title('Content')
                    ->fromModel(User::class, 'content'),

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

        $this->listing->attachment()->syncWithoutdetaching(
            $request->input('listing.attachment', [])
        );

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