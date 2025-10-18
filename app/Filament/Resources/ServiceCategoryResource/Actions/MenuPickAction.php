<?php

namespace App\Filament\Resources\ServiceCategoryResource\Actions;

use App\Models\ServiceCategory;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
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
                            Radio::make('menu_mode')
                                ->label('Listeleme Modu')
                                ->inline()
                                ->options([0 => 'Hepsi', 1 => 'Seçililer'])
                                ->default((int) ($r->menu_mode ?? 0))
                                ->live()
                                ->afterStateUpdated(function (int $state, Set $set, Get $get) use ($r) {
                                    if ($state !== 1) {
                                        return;
                                    }
                                    $current = (array) $get('menu_selected_service_ids');
                                    if (!empty($current)) {
                                        return;
                                    }
                                    $ids = $r->menuServices()->pluck('services.id')->all();
                                    if (empty($ids)) {
                                        $ids = $r->services()->where('is_active', true)->pluck('id')->all();
                                    }
                                    $set('menu_selected_service_ids', array_values(array_map('intval', $ids)));
                                })
                                ->helperText('Hepsi: tüm AKTİF hizmetler. Seçililer: sadece seçtiklerin.'),

                            Select::make('menu_selected_service_ids')
                                ->label('Menüde Gösterilecek Hizmetler')
                                ->options($serviceOptions)
                                ->afterStateHydrated(function (Select $component, $state) use ($r) {
                                    if (!empty($state)) {
                                        return;
                                    }
                                    if ((int) ($r->menu_mode ?? 0) !== 1) {
                                        return;
                                    }
                                    $ids = $r->menuServices()->pluck('services.id')->all();
                                    if (!empty($ids)) {
                                        $component->state(array_values(array_map('intval', $ids)));
                                    }
                                })
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
                $form     = (array) ($data['menu'] ?? $data ?? []);
                $mode     = (int) ($form['menu_mode'] ?? 0);
                $selected = array_values(array_unique(array_map('intval', (array) ($form['menu_selected_service_ids'] ?? []))));

                // show_in_menu burada değişmez; liste ekranındaki toggle yönetir.
                $r->menu_mode = $mode;
                $r->save();

                if (! $r->show_in_menu) {
                    // Menüde değilse pivotu temiz tut
                    $r->menuServices()->detach();
                } else {
                    if ($mode === 1) {
                        // Seçililer: sırayı koru
                        $sync = [];
                        foreach ($selected as $i => $sid) {
                            $sync[$sid] = ['position' => $i];
                        }
                        $r->menuServices()->sync($sync);
                    } else {
                        // Hepsi: pivot boş
                        $r->menuServices()->detach();
                    }
                }

                Cache::forget('menu.services');
            });
    }
}
