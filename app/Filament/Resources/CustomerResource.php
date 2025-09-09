<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms\Form;                      // v3: Forms\Form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;    // avatar için
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;                    // v3: Tables\Table
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;     // tabloda avatar
use Illuminate\Support\Facades\Storage;

// Telefon format kuralın
use App\Support\Rules\PhoneNumberRule;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Avatar yükleme
            FileUpload::make('avatar_path')
                ->label('Avatar')
                ->image()
                ->directory('avatars')    // storage/app/public/avatars
                ->disk('public')          // public disk (storage:link)
                ->visibility('public')
                ->imageEditor()           // (opsiyonel) basit editor
                // ⚠️ Filament v3 bu callback'e TEK parametre (dosya yolu) ile gelir
                ->deleteUploadedFileUsing(function (string $file): void {
                    Storage::disk('public')->delete($file);
                })
                ->nullable(),

            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(
                    table: 'customers',
                    column: 'email',
                    ignoreRecord: true
                ),

            TextInput::make('phone')
                ->tel()
                ->maxLength(30)
                ->rule(new PhoneNumberRule)
                ->unique(
                    table: 'customers',
                    column: 'phone',
                    ignoreRecord: true
                )
                ->nullable(),

            DatePicker::make('birth_date')
                ->native(false)
                ->closeOnDateSelection(),

            Select::make('gender')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other',
                ])
                ->nullable(),

            TextInput::make('address')
                ->maxLength(255)
                ->nullable(),

            TextInput::make('loyalty_points')
                ->numeric()
                ->default(0),

            Toggle::make('receive_newsletters')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_path')
                    ->disk('public')
                    ->square()   // istersen ->circular()
                    ->height(40)
                    ->width(40),

                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('birth_date')->date(),
                TextColumn::make('loyalty_points')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit'   => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
