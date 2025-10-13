<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageCategoryResource\Pages;
use App\Models\PackageCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Components\Actions\Action;

class PackageCategoryResource extends Resource
{
    protected static ?string $model = PackageCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Hizmetler';
    protected static ?string $navigationLabel = 'Paket Kategorileri';
    protected static ?string $modelLabel = 'Paket Kategorisi';
    protected static ?string $pluralModelLabel = 'Paket Kategorileri';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Kategori Bilgileri')
                ->description('Ad yazıldıkça slug otomatik oluşur. İsterseniz el ile düzenleyebilirsiniz.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Ad')
                        ->required()
                        ->maxLength(120)
                        ->live(debounce: 500)
                        ->afterStateUpdated(function (string $state, Set $set, Get $get) {
                            // Kullanıcı slug alanını elle değiştirmediyse otomatik üret
                            if ($get('slug_auto') || blank($get('slug'))) {
                                $set('slug', Str::slug($state, '-', 'tr'));
                            }
                        }),

                    // Slug otomatik kontrolü (form state'te tutulur, DB'ye yazılmaz)
                    Forms\Components\Toggle::make('slug_auto')
                        ->label('Slug otomatik')
                        ->helperText('Ad değiştikçe slug otomatik güncellensin.')
                        ->default(true)
                        ->dehydrated(false)
                        ->live(),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->placeholder('Boş bırakırsanız adınıza göre otomatik oluşur')
                        ->helperText('SEO için düzenleyebilirsiniz.')
                        ->maxLength(160)
                        ->unique(ignoreRecord: true)
                        ->live(debounce: 500)
                        ->afterStateUpdated(function (?string $state, Set $set) {
                            // Kullanıcı slug'a değer girerse artık otomatik güncellemeyi kapat
                            if (filled($state)) {
                                $set('slug_auto', false);
                            }
                        })
                        ->hintAction(
                            Action::make('slugify')
                                ->label('Slug üret')
                                ->icon('heroicon-o-sparkles')
                                ->action(function (Get $get, Set $set) {
                                    $name = (string) $get('name');
                                    if (filled($name)) {
                                        $set('slug', Str::slug($name, '-', 'tr'));
                                        $set('slug_auto', false);
                                    }
                                })
                        ),

                    Forms\Components\FileUpload::make('image_path')
                        ->label('Görsel (128×128) — Opsiyonel')
                        ->image()
                        ->disk('public')
                        ->directory('package-categories')
                        ->preserveFilenames()
                        ->rules([
                            'nullable',
                            'mimes:png,jpg,jpeg,webp',
                            'dimensions:width=128,height=128',
                            'max:512', // KB
                        ])
                        ->hint('Tam 128×128 px olmalı. Yüklemek zorunlu değil.'),

                    Forms\Components\TextInput::make('image_alt')
                        ->label('Görsel Alt Metni')
                        ->maxLength(160)
                        ->helperText('Erişilebilirlik ve SEO için önerilir (opsiyonel).'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    Forms\Components\TextInput::make('sort')
                        ->label('Sıra')
                        ->numeric()
                        ->default(0),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Görsel')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Ad')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort')
                    ->label('Sıra')
                    ->sortable(),

                Tables\Columns\TextColumn::make('packages_count')
                    ->label('Paket')
                    ->counts('packages')
                    ->sortable(),
            ])
            ->defaultSort('sort')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Aktiflik'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPackageCategories::route('/'),
            'create' => Pages\CreatePackageCategory::route('/create'),
            'edit'   => Pages\EditPackageCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) PackageCategory::query()->count();
    }
}
