<?php

namespace App\Filament\Resources\ServiceCategoryResource\Concerns;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;

trait HasFormSchema
{
    public static function makeForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Genel')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Ad')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $currentSlug = $get('slug');
                            if (blank($currentSlug)) {
                                $set('slug', Str::slug($state ?? ''));
                            }
                        }),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Boş bırakabilirsiniz, kayıtta otomatik oluşur.'),

                    Textarea::make('description')
                        ->label('Açıklama')
                        ->rows(4)
                        ->columnSpanFull()
                        ->maxLength(10000),

                    Toggle::make('is_active')
                        ->label('Aktif mi?')
                        ->default(true),

                    TextInput::make('display_order')
                        ->label('Sıra')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Listelerde artan şekilde sıralanır.'),
                ]),
        ]);
    }
}
