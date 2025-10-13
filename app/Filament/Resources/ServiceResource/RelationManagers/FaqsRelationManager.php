<?php

namespace App\Filament\Resources\ServiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FaqsRelationManager extends RelationManager
{
    protected static string $relationship = 'faqs';
    protected static ?string $title = 'SSS';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('question')
                ->label('Soru')->required()->maxLength(255),

            Forms\Components\RichEditor::make('answer')
                ->label('Cevap')
                ->toolbarButtons(['bold','italic','strike','link','orderedList','unorderedList','blockquote','h2','h3'])
                ->columnSpanFull(),

            Forms\Components\TextInput::make('sort_order')
                ->label('Sıra')->numeric()->default(0)->minValue(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->label('Sıra')->sortable()->width('72px')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('question')->label('Soru')->searchable()->limit(80)->wrap(),
                Tables\Columns\TextColumn::make('updated_at')->label('Güncellendi')->dateTime('d.m.Y H:i')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([ Tables\Actions\CreateAction::make()->label('SSS Ekle') ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),
                Tables\Actions\DeleteAction::make()->label('Sil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([ Tables\Actions\DeleteBulkAction::make()->label('Seçilenleri Sil') ]),
            ]);
    }
}
