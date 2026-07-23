<?php

namespace App\Filament\Pages;

use App\Models\Tenant;
use App\Support\CatalogTemplates;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

/**
 * @property-read Schema $form
 */
class PanaleraDigital extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $navigationLabel = 'Pañalera Digital';

    protected static ?string $title = 'Pañalera Digital';

    protected static ?string $slug = 'panalera-digital';

    protected static ?int $navigationSort = 5;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function mount(): void
    {
        $this->form->fill($this->formStateFromTenant($this->getTenant()));
    }

    public function getHeading(): string|Htmlable
    {
        return 'Pañalera Digital';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Configurá la identidad y el catálogo público de tu pañalera.';
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->operation('edit')
            ->model($this->getTenant())
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Negocio')
                    ->description('Datos básicos que ven tus clientes.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del negocio')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug (URL pública)')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('No se puede cambiar para no romper links ya compartidos.'),
                        TextInput::make('catalog_url')
                            ->label('Link del catálogo')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Compartí este link con tus clientes.'),
                        Textarea::make('description')
                            ->label('Descripción corta')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Se usa en el catálogo si no cargás un subtítulo de hero.'),
                        TextInput::make('phone')
                            ->label('Teléfono / WhatsApp')
                            ->tel()
                            ->helperText('Incluí código de país. Ej: 54911xxxxxxxx'),
                        TextInput::make('instagram')
                            ->label('Instagram')
                            ->placeholder('@tupanalera o URL')
                            ->maxLength(255),
                    ]),
                Section::make('Identidad visual')
                    ->description('Logo e imagen principal de la plantilla Avanzada.')
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Logo de la pañalera')
                            ->image()
                            ->disk('public')
                            ->directory('logos')
                            ->visibility('public')
                            ->imageEditor()
                            ->imagePreviewHeight('120')
                            ->helperText('Aparece en la barra superior del catálogo.'),
                        FileUpload::make('banner_path')
                            ->label('Imagen del Hero')
                            ->image()
                            ->disk('public')
                            ->directory('banners')
                            ->visibility('public')
                            ->imageEditor()
                            ->imagePreviewHeight('180')
                            ->helperText('Foto ancha y luminosa (bebés, productos, local). Recomendado 1600×900.'),
                        TextInput::make('hero_title')
                            ->label('Título del Hero')
                            ->maxLength(120)
                            ->placeholder(fn (): string => $this->getTenant()->name)
                            ->helperText('Opcional. Si lo dejás vacío, se usa el nombre del negocio.'),
                        Textarea::make('hero_subtitle')
                            ->label('Subtítulo del Hero')
                            ->rows(2)
                            ->maxLength(220)
                            ->placeholder('Ej: Todo para tu bebé, con envío y asesoramiento.')
                            ->helperText('Opcional. Si lo dejás vacío, se usa la descripción corta.'),
                    ]),
                Section::make('Plantilla')
                    ->schema([
                        Select::make('template')
                            ->label('Plantilla del catálogo')
                            ->options(CatalogTemplates::options())
                            ->required()
                            ->native(false)
                            ->helperText(fn (): string => collect(CatalogTemplates::all())
                                ->map(fn (array $meta, string $key): string => ($meta['label'] ?? $key).': '.($meta['description'] ?? ''))
                                ->implode(' · ')),
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
                            Action::make('openCatalog')
                                ->label('Ver catálogo')
                                ->url(fn (): string => $this->getTenant()->catalogUrl())
                                ->openUrlInNewTab()
                                ->color('gray'),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->getTenant()->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'phone' => $data['phone'] ?? null,
            'instagram' => $data['instagram'] ?? null,
            'logo_path' => $data['logo_path'] ?? null,
            'banner_path' => $data['banner_path'] ?? null,
            'hero_title' => $data['hero_title'] ?? null,
            'hero_subtitle' => $data['hero_subtitle'] ?? null,
            'template' => CatalogTemplates::resolve($data['template'] ?? null),
        ]);

        Notification::make()
            ->title('Datos guardados')
            ->success()
            ->send();

        $this->form->fill($this->formStateFromTenant($this->getTenant()->fresh()));
    }

    /**
     * @return array<string, mixed>
     */
    private function formStateFromTenant(Tenant $tenant): array
    {
        return [
            'name' => $tenant->name,
            'slug' => $tenant->slug,
            'description' => $tenant->description,
            'phone' => $tenant->phone,
            'instagram' => $tenant->instagram,
            'logo_path' => $tenant->logo_path,
            'banner_path' => $tenant->banner_path,
            'hero_title' => $tenant->hero_title,
            'hero_subtitle' => $tenant->hero_subtitle,
            'template' => CatalogTemplates::resolve($tenant->template),
            'catalog_url' => $tenant->catalogUrl(),
        ];
    }

    protected function getTenant(): Tenant
    {
        /** @var Tenant $tenant */
        $tenant = Filament::getTenant();

        return $tenant;
    }
}
