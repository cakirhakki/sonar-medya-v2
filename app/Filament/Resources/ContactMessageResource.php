<?php

// app/Filament/Resources/ContactMessageResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Columns\TextColumn;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'İletişim';
    protected static ?string $navigationLabel = 'Mesajlar';
    protected static ?string $modelLabel = 'Mesaj';
    protected static ?string $pluralModelLabel = 'Mesajlar';

    public static function canCreate(): bool { return false; } // admin panelden oluşturma yok
    public static function getGloballySearchableAttributes(): array
    {
        return ['name','email','phone','company','message'];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('E-posta')
                    ->url(fn ($record) => 'mailto:'.$record->email, true)
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->copyMessage('Kopyalandı')
                    ->copyMessageDuration(1500)
                    ->searchable(),

                TextColumn::make('phone')
    ->label('Telefon')
    ->url(fn (ContactMessage $record) => $record->phone ? 'tel:'.preg_replace('/\D+/', '', $record->phone) : null, true)
    ->icon('heroicon-o-phone')
    ->toggleable()
    ->searchable(),

                

                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->colors([
                        'warning' => 'new',
                        'success' => 'contacted',
                        'danger'  => 'spam',
                    ])
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'new'       => 'Yeni',
                        'contacted' => 'Dönüş Yapıldı',
                        'spam'      => 'Spam',
                    ]),
                TrashedFilter::make()
                    ->label('Silinmiş'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Göster'),
                Tables\Actions\Action::make('mark_contacted')
                    ->label('Dönüş Yapıldı')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status !== 'contacted')
                    ->action(fn (ContactMessage $record) => $record->update(['status' => 'contacted'])),
                Tables\Actions\DeleteAction::make()
                    ->label('Sil'), // soft delete
                Tables\Actions\RestoreAction::make()
                    ->label('Geri Al'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('Kalıcı Sil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Sil'),
                    Tables\Actions\RestoreBulkAction::make()->label('Geri Al'),
                    Tables\Actions\ForceDeleteBulkAction::make()->label('Kalıcı Sil'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view'  => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
