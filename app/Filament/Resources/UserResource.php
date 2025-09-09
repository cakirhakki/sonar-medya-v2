<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Support\Rules\UserNameRule;
use App\Support\Rules\PhoneNumberRule;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Kullanıcı Yönetimi';
    protected static ?string $navigationLabel = 'Kullanıcılar';
    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?int    $navigationSort  = 1;

    // Başlık/label metinleri
    public static function getPluralLabel(): string
    {
        return 'Kullanıcılar';
    }

    public static function getLabel(): string
    {
        return 'Kullanıcı';
    }

    /**
     * Panelde oturum açan kullanıcı (Filament admin guard).
     */
    protected static function currentUser(): ?User
    {
        /** @var User|null $u */
        $u = Filament::auth()->user();
        return $u;
    }

    /**
     * Menü görünürlüğü — Shield izin adıyla koşullandır.
     * view_any_user izni olan menüyü görür.
     */
    public static function shouldRegisterNavigation(): bool
    {
        $u = static::currentUser();
        return $u?->can('view_any_user') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Ad')
                ->required()
                ->rules([new UserNameRule()])
                ->validationMessages([
                    'required' => 'Ad zorunludur.',
                    'min'      => 'Ad en az :min karakter olmalı.',
                    'max'      => 'Ad en fazla :max karakter olmalı.',
                ]),

            TextInput::make('phone')
                ->label('Telefon')
                ->required()
                ->rules([new PhoneNumberRule()])
                ->validationMessages([
                    'required' => 'Telefon zorunludur.',
                    'regex'    => 'Telefon sadece rakamlardan oluşmalıdır.',
                    'max'      => 'Telefon en fazla :max karakter olmalı.',
                    'unique'   => 'Bu telefon daha önce kaydedilmiş.',
                ]),

            TextInput::make('email')
                ->label('E-posta')
                ->email()
                ->required()
                ->rules(fn (?Model $record) => [
                    'required',
                    'string',
                    'email:rfc,dns',
                    'max:255',
                    // update sırasında kendi kaydını hariç tut
                    'unique:users,email,' . ($record?->getKey() ?? 'NULL'),
                ])
                ->validationMessages([
                    'required' => 'E-posta zorunludur.',
                    'email'    => 'Geçerli bir e-posta yazın.',
                    'unique'   => 'Bu e-posta zaten kullanımda.',
                ]),

            TextInput::make('password')
                ->label('Şifre')
                ->password()
                ->rules(fn (string $context) => $context === 'create'
                    ? ['required', 'confirmed', Password::min(8)->letters()->numbers()->symbols()]
                    : ['nullable', 'confirmed', Password::min(8)->letters()->numbers()->symbols()]
                )
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->validationMessages([
                    'required'  => 'Şifre zorunludur.',
                    'min'       => 'Şifre en az :min karakter olmalı.',
                    'confirmed' => 'Şifreler eşleşmiyor.',
                ]),

            TextInput::make('password_confirmation')
                ->label('Şifre (tekrar)')
                ->password()
                ->dehydrated(false)
                ->rules(fn (string $context) => $context === 'create' ? ['required'] : ['nullable'])
                ->validationMessages([
                    'required' => 'Şifre tekrar zorunludur.',
                ]),

            Select::make('roles')
                ->label('Roller')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable()
                // Rol atama alanı, kullanıcı güncelleme (update_user) yetkisi olana gösterilsin
                ->visible(fn () => static::currentUser()?->can('update_user') ?? false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Ad')->sortable()->searchable(),
                TextColumn::make('phone')->label('Telefon')->sortable()->searchable(),
                TextColumn::make('email')->label('E-posta')->sortable()->searchable(),
                TextColumn::make('roles.name')->label('Roller')->badge(),
                TextColumn::make('created_at')->label('Kayıt')->dateTime('d.m.Y H:i'),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
