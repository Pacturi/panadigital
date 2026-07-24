<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\Rules\Password;

/**
 * @property-read Schema $form
 */
class MisDatos extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $navigationLabel = 'Mis datos';

    protected static ?string $title = 'Mis datos';

    protected static ?string $slug = 'mis-datos';

    protected static bool $shouldRegisterNavigation = false;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return Filament::auth()->check();
    }

    public function mount(): void
    {
        $user = $this->getUser();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'password' => null,
            'passwordConfirmation' => null,
            'currentPassword' => null,
        ]);
    }

    public function getHeading(): string|Htmlable
    {
        return 'Mis datos';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Actualizá tu nombre, email o contraseña.';
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->operation('edit')
            ->model($this->getUser())
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos personales')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->autofocus(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ]),
                Section::make('Seguridad')
                    ->description('Para guardar cualquier cambio necesitás confirmar tu contraseña actual.')
                    ->schema([
                        TextInput::make('currentPassword')
                            ->label('Contraseña actual')
                            ->password()
                            ->revealable()
                            ->required()
                            ->currentPassword(guard: Filament::getAuthGuard())
                            ->dehydrated(false),
                        TextInput::make('password')
                            ->label('Nueva contraseña')
                            ->password()
                            ->revealable()
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->helperText('Dejala vacía si no querés cambiarla.')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->live(debounce: 500)
                            ->same('passwordConfirmation'),
                        TextInput::make('passwordConfirmation')
                            ->label('Confirmar nueva contraseña')
                            ->password()
                            ->revealable()
                            ->required()
                            ->visible(fn (Get $get): bool => filled($get('password')))
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Guardar')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                            Action::make('back')
                                ->label('Regresar')
                                ->url(fn (): string => Filament::getUrl())
                                ->color('gray'),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = $this->getUser();

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (filled($data['password'] ?? null)) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);

        if (request()->hasSession() && array_key_exists('password', $payload)) {
            request()->session()->put([
                'password_hash_'.Filament::getAuthGuard() => $user->getAuthPassword(),
            ]);
        }

        Notification::make()
            ->title('Datos guardados')
            ->success()
            ->send();

        $this->form->fill([
            'name' => $user->fresh()->name,
            'email' => $user->fresh()->email,
            'password' => null,
            'passwordConfirmation' => null,
            'currentPassword' => null,
        ]);
    }

    protected function getUser(): User
    {
        /** @var User $user */
        $user = Filament::auth()->user();

        return $user;
    }
}
