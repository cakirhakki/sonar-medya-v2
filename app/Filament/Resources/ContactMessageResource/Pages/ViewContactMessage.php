<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Mesaj')
                ->schema([
                    TextEntry::make('name')
                        ->label('Ad Soyad')
                        ->copyable(),

                    TextEntry::make('email')
                        ->label('E-posta')
                        ->copyable()
                        ->icon('heroicon-o-envelope')
                        ->url(fn (string $state) => 'mailto:' . $state, true),

                    TextEntry::make('phone')
                        ->label('Telefon')
                        ->copyable()
                        ->icon('heroicon-o-phone')
                        ->url(fn (?string $state) => $state
                            ? 'tel:' . preg_replace('/\D+/', '', $state)
                            : null, true),

                    TextEntry::make('company')
                        ->label('Şirket'),

                    TextEntry::make('message')
                        ->label('Mesaj')
                        ->columnSpanFull()
                        ->prose(),
                ])->columns(2),

            Section::make('Meta')
                ->schema([
                    TextEntry::make('page_url')
                        ->label('Sayfa')
                        ->copyable()
                        ->url(fn (?string $state) => $state, true),

                    TextEntry::make('ip')
                        ->label('IP'),

                    TextEntry::make('user_agent')
                        ->label('User-Agent')
                        ->columnSpanFull(),

                    TextEntry::make('consent_at')
                        ->label('Aydınlatma Onayı')
                        ->dateTime('d.m.Y H:i')
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'gray'),

                    TextEntry::make('status')
                        ->label('Durum')
                        ->badge()
                        ->colors([
                            'warning' => 'new',
                            'success' => 'contacted',
                            'danger'  => 'spam',
                        ]),

                    TextEntry::make('created_at')
                        ->label('Oluşturma')
                        ->dateTime('d.m.Y H:i'),
                ])->columns(2),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('mark_contacted')
                ->label('Dönüş Yapıldı')
                ->icon('heroicon-o-check-circle')
                ->visible(fn (ContactMessage $record) => $record->status !== 'contacted')
                ->action(fn (ContactMessage $record) => $record->update(['status' => 'contacted'])),

            \Filament\Actions\DeleteAction::make()->label('Sil'),
            \Filament\Actions\RestoreAction::make()->label('Geri Al'),
            \Filament\Actions\ForceDeleteAction::make()->label('Kalıcı Sil'),
        ];
    }
}
