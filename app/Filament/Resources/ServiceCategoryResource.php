<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceCategoryResource\Pages;
use App\Filament\Resources\ServiceCategoryResource\Concerns\HasFormSchema;
use App\Filament\Resources\ServiceCategoryResource\Concerns\HasTableSchema;
use App\Models\ServiceCategory;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class ServiceCategoryResource extends Resource
{
    use HasFormSchema;
    use HasTableSchema;

    protected static ?string $model = ServiceCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Hizmetler';
    protected static ?string $navigationLabel = 'Hizmet Kategorileri';
    protected static ?string $modelLabel = 'Hizmet Kategorisi';
    protected static ?string $pluralModelLabel = 'Hizmet Kategorileri';

    public static function getNavigationBadge(): ?string
    {
        return (string) ServiceCategory::query()->count();
    }

    public static function form(Form $form): Form
    {
        return static::makeForm($form);
    }

    public static function table(Table $table): Table
    {
        return static::makeTable($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListServiceCategories::route('/'),
            'create' => Pages\CreateServiceCategory::route('/create'),
            'edit'   => Pages\EditServiceCategory::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'description'];
    }
}
