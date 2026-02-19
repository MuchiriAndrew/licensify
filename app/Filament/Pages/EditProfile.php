<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Actions\Action;
use Filament\Pages\Page;
use Filament\Pages\Concerns\HasFormActions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EditProfile extends Page
{
    use HasFormActions;

    protected static ?string $navigationIcon = null;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'profile';

    protected static ?string $title = 'Edit profile';

    protected static string $view = 'filament.pages.edit-profile';

    public function mount(): void
    {
        $user = $this->getUser();
        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->maxLength(255)
                ->rule(fn () => Rule::unique('users', 'email')->ignore($this->getUser()->id)),
            Forms\Components\TextInput::make('password')
                ->label('New password')
                ->password()
                ->nullable()
                ->minLength(8)
                ->dehydrated(fn ($state) => filled($state))
                ->helperText('Leave blank to keep your current password.'),
        ];
    }

    protected function getFormModel(): \Illuminate\Database\Eloquent\Model|string|null
    {
        $user = $this->getUser();
        assert($user instanceof \Illuminate\Database\Eloquent\Model);

        return $user;
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = $this->getUser();
        assert($user instanceof \Illuminate\Database\Eloquent\Model);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        \Filament\Notifications\Notification::make()
            ->title('Profile updated.')
            ->success()
            ->send();
    }

    protected function getUser(): \Illuminate\Contracts\Auth\Authenticatable
    {
        return \Filament\Facades\Filament::auth()->user();
    }
}
