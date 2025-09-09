<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Tanımlar';
    protected static ?int $navigationSort = 10;

    public static function getModelLabel(): string
    {
        return 'Hizmet';
    }

    public static function getPluralLabel(): string
    {
        return 'Hizmetler';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Ad')
                ->required()
                ->maxLength(160)
                // Sadece Filament'in unique kuralı: mevcut kaydı kesin olarak IGNORE eder.
                ->unique(
                    table: 'services',
                    column: 'name',
                    ignoreRecord: true,
                )
                ->validationAttribute('Hizmet adı'),

            Textarea::make('summary')
                ->label('Kısa Açıklama')
                ->maxLength(2000),

            TextInput::make('duration_minutes')
                ->label('Süre (dk)')
                ->numeric()
                ->minValue(5)
                ->maxValue(1440)
                ->required(),

            TextInput::make('price')
                ->label('Fiyat (₺)')
                ->numeric()
                ->minValue(0)
                ->required(),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ad')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('duration_minutes')
                    ->label('Süre (dk)')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Fiyat')
                    ->formatStateUsing(
                        fn (string|int|float|null $state): string =>
                            number_format((float) ($state ?? 0), 2, ',', '.') . ' ₺'
                    )
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Kayıt')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // Örnek: Aktif/Pasif filtresi istersen açabilirsin
                // Tables\Filters\TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit'   => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
