<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationGroup = 'İçerik';
    protected static ?string $navigationLabel = 'Markalar';
    protected static ?string $modelLabel = 'Marka';
    protected static ?string $pluralModelLabel = 'Markalar';

    // GEÇİCİ: Menüde her zaman göster
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Ad')
                            ->required()
                            ->maxLength(150)
                            ->live(debounce: 400),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->helperText('Boş bırakılırsa otomatik üretilir.'),

                        Forms\Components\TextInput::make('url')
                            ->label('Bağlantı')
                            ->url()
                            ->suffixIcon('heroicon-o-link'),

                        Forms\Components\Select::make('group')
                            ->label('Grup')
                            ->options([
                                'top' => 'Üst Slider',
                                'bottom' => 'Alt Slider',
                                'default' => 'Diğer',
                            ])
                            ->required()
                            ->default('top'),

                        Forms\Components\TextInput::make('display_order')
                            ->label('Sıra')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Açıklama')->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Açıklama')
                        ->rows(4)
                        ->maxLength(2000)
                        ->nullable()
                        ->columnSpanFull(),
                ]),

                Forms\Components\Section::make('Logo')->schema([
                    SpatieMediaLibraryFileUpload::make('logo')
                        ->collection('logo')
                        ->image()
                        ->imageEditor()
                        ->required()
                        ->downloadable()
                        ->openable(),
                ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logo')
                    ->square()
                    ->label('Logo'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Ad'),

                Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->label('Grup'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Açıklama')
                    ->toggleable(isToggledHiddenByDefault: true) // tabloyu sade tut
                    ->limit(80),

                Tables\Columns\TextColumn::make('display_order')
                    ->sortable()
                    ->label('Sıra'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i')
                    ->since()
                    ->label('Güncel'),
            ])
            ->defaultSort('display_order')
            ->filters([])
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
            'index'  => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit'   => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
