<?php

namespace App\Filament\Pages;

use App\Models\User as AppUser;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Components\{Section, Grid, TextInput, Textarea, FileUpload, Placeholder};
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Support\Rules\UserNameRule;
use App\Support\Rules\PhoneNumberRule;
use Illuminate\Support\Str;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    /** @var view-string */
    protected static string $view = 'filament.pages.profile';
    protected static ?string $navigationIcon = null; // sidebar’a eklemiyoruz
    protected static ?string $title = 'Profilim';

    /** Sidebar’da görünmesin. */
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    /** Form state */
    public ?array $data = [];

    public function mount(): void
    {
        /** @var AppUser|null $user */
        $user = Filament::auth()->user();
        abort_unless($user instanceof AppUser, 403);

        $this->form->fill([
            'name'          => $user->name,
            // 'job_title'    => (artık form alanı yok)
            'bio'           => $user->bio,
            'avatar_path'   => $user->avatar_path,
            'avatar_alt'    => $user->avatar_alt,
            'phone'         => $user->phone,
            'email'         => $user->email,
            'password'      => null,
            'password_confirmation' => null,
            'website_url'   => $user->website_url,
            'twitter_url'   => $user->twitter_url,
            'linkedin_url'  => $user->linkedin_url,
            'facebook_url'  => $user->facebook_url,
            'instagram_url' => $user->instagram_url,
            'github_url'    => $user->github_url,
        ]);
    }

    public function form(Form $form): Form
    {
        $userId = Filament::auth()->id();

        return $form
            ->schema([
                Section::make('Genel Bilgiler')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Ad')
                            ->required()
                            ->rules([new UserNameRule()])
                            ->maxLength(255),

                        // 🎯 Ünvanı rolden üret – sadece göster
                        Placeholder::make('role_title')
                            ->label('Ünvan')
                            ->content(function () {
                                /** @var AppUser|null $u */
                                $u = Filament::auth()->user();
                                if (! $u) return '-';

                                // İstersen burada TR etiket eşlemesi yap:
                                $labels = [
                                    'super_admin' => 'Super Admin',
                                    'admin'       => 'Yönetici',
                                    'editor'      => 'Editör',
                                    'author'      => 'Yazar',
                                    'user'        => 'Kullanıcı',
                                ];

                                // Birden fazla rol varsa virgül ile gösterelim.
                                return $u->getRoleNames()
                                    ->map(fn ($r) => $labels[$r] ?? Str::headline(str_replace(['_', '-'], ' ', $r)))
                                    ->join(', ');
                            })
                            ->helperText('Spatie Permission rollerinizden otomatik üretilir.'),
                    ]),

                    Textarea::make('bio')
                        ->label('Kısa Tanıtım')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

                Section::make('Avatar')->schema([
                    FileUpload::make('avatar_path')
                        ->label('Profil Görseli')
                        ->disk('public')
                        ->directory('users/avatars')
                        ->image()
                        ->imageEditor()
                        ->visibility('public')
                        ->maxSize(1024)
                        ->nullable(),
                    TextInput::make('avatar_alt')
                        ->label('Alt Metin')
                        ->maxLength(255),
                ])->columns(2),

                Section::make('İletişim')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('phone')
                            ->label('Telefon')
                            ->required()
                            ->rules([new PhoneNumberRule()])
                            ->maxLength(20),

                        TextInput::make('email')
                            ->label('E-posta')
                            ->email()
                            ->required()
                            ->rules([
                                'required', 'email:rfc,dns', 'max:255',
                                "unique:users,email,{$userId}",
                            ]),
                    ]),
                ]),

                Section::make('Şifre Değiştir')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('password')
                            ->label('Yeni Şifre')
                            ->password()
                            ->rules(['nullable', 'confirmed', Password::min(8)->letters()->numbers()->symbols()]),
                        TextInput::make('password_confirmation')
                            ->label('Yeni Şifre (tekrar)')
                            ->password()
                            ->dehydrated(false),
                    ]),
                ]),

                Section::make('Bağlantılar')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('website_url')->label('Web Sitesi')->url()->maxLength(255),
                        TextInput::make('twitter_url')->label('Twitter')->url()->maxLength(255),
                        TextInput::make('linkedin_url')->label('LinkedIn')->url()->maxLength(255),
                        TextInput::make('facebook_url')->label('Facebook')->url()->maxLength(255),
                        TextInput::make('instagram_url')->label('Instagram')->url()->maxLength(255),
                        TextInput::make('github_url')->label('GitHub')->url()->maxLength(255),
                    ]),
                ]),
            ])
            ->statePath('data');
    }

    /** Üst sağ “Kaydet” butonu */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Kaydet')
                ->icon('heroicon-m-check')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        /** @var AppUser|null $user */
        $user = Filament::auth()->user();
        abort_unless($user instanceof AppUser, 403);

        $data = $this->form->getState();

        // E-posta değişirse doğrulamayı sıfırla (MustVerifyEmail)
        if (! empty($data['email']) && $data['email'] !== $user->email) {
            $user->email = $data['email'];
            $user->email_verified_at = null;
        }

        // Şifre girilmişse değiştir
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Basit alanlar (⚠️ job_title artık kaydedilmiyor)
        $user->fill([
            'name'          => $data['name'] ?? $user->name,
            'bio'           => $data['bio'] ?? null,
            'avatar_path'   => $data['avatar_path'] ?? null,
            'avatar_alt'    => $data['avatar_alt'] ?? null,
            'phone'         => $data['phone'] ?? $user->phone,
            'website_url'   => $data['website_url'] ?? null,
            'twitter_url'   => $data['twitter_url'] ?? null,
            'linkedin_url'  => $data['linkedin_url'] ?? null,
            'facebook_url'  => $data['facebook_url'] ?? null,
            'instagram_url' => $data['instagram_url'] ?? null,
            'github_url'    => $data['github_url'] ?? null,
        ]);

        $user->save();

        // E-posta değiştiyse doğrulama e-postasını gönder (metot varsa)
        if (is_null($user->email_verified_at) && method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

        Notification::make()
            ->title('Profilin güncellendi.')
            ->success()
            ->send();
    }
}
