<?php

namespace App\Filament\Resources\ServiceCategoryResource\Concerns;

use App\Filament\Resources\ServiceCategoryResource\Actions\MenuPickAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

trait HasTableSchema
{
    public static function makeTable(Table $table): Table
    {
        return $table
            ->reorderable('display_order')
            ->defaultSort('display_order')
            ->columns([
                Tables\Columns\TextColumn::make('display_order')
                    ->label('Sıra')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->width('80px'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Ad')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->copyMessage('Kopyalandı')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(80)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\ToggleColumn::make('show_in_menu')
                    ->label('Menüde')
                    ->sortable()
                    ->afterStateUpdated(function (bool $state, $record) {
                        // Menüde açılırsa kategori pasif ise aktifleştir
                        if ($state && ! $record->is_active) {
                            $record->is_active = true;
                        }
                        $record->save();
                        Cache::forget('menu.services');
                    }),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable()
                    ->afterStateUpdated(function () {
                        Cache::forget('menu.services');
                    }),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif mi?')
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif')
                    ->nullable()
                    ->queries(
                        true: fn (Builder $q) => $q->where('is_active', true),
                        false: fn (Builder $q) => $q->where('is_active', false),
                        blank: fn (Builder $q) => $q
                    ),

                Tables\Filters\TernaryFilter::make('show_in_menu')
                    ->label('Menüde mi?')
                    ->trueLabel('Evet')
                    ->falseLabel('Hayır')
                    ->nullable(),
            ])
            ->actions([
                // Menü seçimi butonu sadece menüde gösterilenlerde görünsün
                MenuPickAction::make()->visible(fn ($record) => (bool) $record->show_in_menu),
                Tables\Actions\EditAction::make()->label('Düzenle'),
                Tables\Actions\DeleteAction::make()->label('Sil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Seçilenleri Sil'),
                ]),
            ]);
    }
}
