<?php

namespace App\Filament\Resources\ServiceCategoryResource\Actions;

use App\Models\ServiceCategory;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

final class MenuPickAction
{
    public static function make(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('menuPick')
            ->label('Menü Seçimi')
            ->icon('heroicon-o-list-bullet')
            ->slideOver()
            ->modalHeading(fn (ServiceCategory $r) => 'Menü Seçimi — ' . $r->name)
            ->modalSubmitActionLabel('Kaydet')
            ->form(function (ServiceCategory $r) {
                $serviceOptions = $r->services()
                    ->where('is_active', true)
                    ->when(
                        Schema::hasColumn('services', 'display_order'),
                        fn ($q) => $q->orderBy('display_order')->orderBy('name'),
                        fn ($q) => $q->orderBy('name')
                    )
                    ->pluck('name', 'id');

                return [
                    Group::make()
                        ->statePath('menu')
                        ->schema([
                            Toggle::make('show_in_menu')
                                ->label('Menüde Göster')
                                ->inline(false)
                                ->default((bool) $r->show_in_menu),

                            Radio::make('menu_mode')
                                ->label('Listeleme Modu')
                                ->inline()
                                ->options([0 => 'Hepsi', 1 => 'Seçililer'])
                                ->default((int) ($r->menu_mode ?? 0))
                                ->live()
                                ->afterStateUpdated(function (int $state, Set $set, Get $get) use ($r) {
                                    if ($state === 1 && empty($get('menu_selected_service_ids'))) {
                                        $ids = $r->services()->where('is_active', true)->pluck('id')->all();
                                        $set('menu_selected_service_ids', $ids);
                                    }
                                })
                                ->helperText('Hepsi: tüm AKTİF hizmetler. Seçililer: sadece seçtiklerin.'),

                            // DEĞİŞTİ: dehydrated(false) kaldırıldı + reactive ve normalleştirme eklendi
                            Select::make('menu_selected_service_ids')
                                ->label('Menüde Gösterilecek Hizmetler')
                                ->options($serviceOptions)
                                ->default(fn () => (array) $r->menu_selected_service_ids)
                                ->searchable()
                                ->preload()
                                ->multiple()
                                ->placeholder('Hizmet seçin')
                                ->reactive()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $set('menu_selected_service_ids', array_values(array_map('intval', (array) $state)));
                                })
                                ->visible(fn (Get $get) => (string) ($get('menu_mode') ?? '0') === '1')
                                ->nullable(),

                            Placeholder::make('info')
                                ->content(fn () => 'Aktif hizmet sayısı: ' . $serviceOptions->count()),
                        ])
                        ->columns(1),
                ];
            })
            ->action(function (ServiceCategory $r, array $data) {
                $form = (array) ($data['menu'] ?? []);
                $mode = (int) ($form['menu_mode'] ?? 0);
                $show = (bool) ($form['show_in_menu'] ?? $r->show_in_menu);
                $selected = array_values(array_map('intval', (array) ($form['menu_selected_service_ids'] ?? [])));

                $payload = [
                    'show_in_menu'              => $show,
                    'menu_mode'                 => $mode,
                    'menu_selected_service_ids' => [],
                    'menu_excluded_service_ids' => [],
                ];

                if ($show) {
                    if ($mode === 1) {
                        // Seçililer: kullanıcı seçimini aynen kaydet
                        $payload['menu_selected_service_ids'] = $selected;
                    } else {
                        // Hepsi: tüm aktifleri yaz
                        $ids = $r->services()->where('is_active', true)->pluck('id')->all();
                        $payload['menu_selected_service_ids'] = array_map('intval', $ids);
                    }
                }

                // Sadece beklenen sütunları güncelle
                $r->refresh();
                ServiceCategory::query()
                    ->whereKey($r->getKey())
                    ->update($payload);

                Cache::forget('menu.services');
            });
    }
}
