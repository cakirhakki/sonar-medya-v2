<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Support\Rules\RoleNameRule;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Group;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationGroup = 'Kullanıcı Yönetimi';
    protected static ?string $navigationLabel = 'Roller';
    protected static ?string $navigationIcon  = 'heroicon-o-key';
    protected static ?int    $navigationSort  = 2;

    public static function getPluralLabel(): string
    {
        return 'Roller';
    }

    public static function getLabel(): string
    {
        return 'Rol';
    }

    protected static function currentUser(): ?\App\Models\User
    {
        /** @var \App\Models\User|null $u */
        $u = Filament::auth()->user();
        return $u;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $u = static::currentUser();
        return $u?->can('view_any_role') ?? false;
    }

    public static function form(Form $form): Form
    {
        $titleMap = [
            'user'        => 'Kullanıcı',
            'role'        => 'Rol',
            'service'     => 'Hizmet',
            'appointment' => 'Randevu',
        ];

        $labelsOf = [
            'view_any'         => 'Listele',
            'view'             => 'Görüntüle',
            'create'           => 'Oluştur',
            'update'           => 'Güncelle',
            'delete'           => 'Sil',
            'delete_any'       => 'Toplu Sil',
            'restore'          => 'Geri Yükle',
            'restore_any'      => 'Toplu Geri Yükle',
            'force_delete'     => 'Kalıcı Sil',
            'force_delete_any' => 'Toplu Kalıcı Sil',
            'replicate'        => 'Kopyala',
            'reorder'          => 'Sırala',
            'widget'           => 'Widget',
            'page'             => 'Sayfa',
        ];

        return $form->schema([
            TextInput::make('name')
                ->label('Rol adı')
                ->required()
                ->rules([new RoleNameRule()])
                ->validationMessages([
                    'required' => 'Rol adı zorunludur.',
                    'min' => 'Rol adı en az :min karakter olmalı.',
                    'max' => 'Rol adı en fazla :max karakter olmalı.',
                    'unique' => 'Bu rol adı zaten mevcut.',
                ]),

            Hidden::make('guard_name')
                ->default('admin')
                ->dehydrated(true),

            Section::make('İzinler')
                ->description('Modül başlıkları altında ilgili aksiyonları işaretleyin.')
                ->extraAttributes(['class' => 'whitespace-normal break-words'])
                ->schema(function () use ($titleMap, $labelsOf) {
                    $byModule = Permission::query()
                        ->where('guard_name', 'admin')
                        ->orderBy('name')
                        ->get()
                        ->groupBy(fn (Permission $p) => Str::afterLast($p->name, '_'));

                    $rows = [];

                    foreach ($byModule as $module => $perms) {
                        $title = $titleMap[$module] ?? Str::headline($module);

                        $options = [];
                        foreach ($labelsOf as $action => $label) {
                            $perm = $perms->firstWhere('name', $action . '_' . $module);
                            if ($perm) {
                                $options[$perm->id] = $label;
                            }
                        }
                        if (empty($options)) continue;

                        $rows[] = Grid::make()
                            ->columns(12)
                            ->schema([
                                // Sol: başlık + altında "Tümünü Seç"
                                Group::make()
                                    ->schema([
                                        Placeholder::make('m_'.$module)
                                            ->label('')
                                            ->content($title)
                                            ->extraAttributes(['class' => 'font-medium']),
                                        Checkbox::make('select_all_'.$module)
                                            ->label('Tümünü Seç')
                                            ->reactive()
                                            ->dehydrated(false) // DB’ye yazma
                                            ->afterStateUpdated(function ($state, callable $set) use ($options, $module) {
                                                $set('permissions_'.$module, $state ? array_keys($options) : []);
                                            }),
                                    ])
                                    ->columnSpan(2),

                                // Sağ: izinler (ARTIK $data'ya gidecek)
                                CheckboxList::make('permissions_'.$module)
                                    ->label('')
                                    ->options($options)
                                    ->columns(min(4, count($options)))
                                    ->extraAttributes(['class' => 'whitespace-normal break-words text-sm'])
                                    ->columnSpan(10)
                                    ->dehydrated(true) // <-- ÖNEMLİ
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) use ($options, $module) {
                                        $allIds = array_keys($options);
                                        $state  = (array) $state;
                                        $set('select_all_'.$module, ! array_diff($allIds, $state));
                                    }),
                            ]);
                    }

                    return $rows;
                })
                ->columnSpan('full'),

            // Edit ekranında tikleri dağıtmak için: DB’ye dehydrate ETME
            CheckboxList::make('permissions')
                ->label('')
                ->hidden()
                ->dehydrated(false)
                ->relationship(
                    name: 'permissions',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn (Builder $q) => $q->where('guard_name', 'admin'),
                )
                ->afterStateHydrated(function (CheckboxList $component, $state, callable $set) {
                    $selected = collect($state);

                    $byModule = Permission::query()
                        ->where('guard_name', 'admin')
                        ->get()
                        ->groupBy(fn (Permission $p) => Str::afterLast($p->name, '_'));

                    foreach ($byModule as $module => $perms) {
                        $ids     = $perms->whereIn('id', $selected)->pluck('id')->all();
                        $allIds  = $perms->pluck('id')->all();

                        $set('permissions_' . $module, $ids);
                        $set('select_all_' . $module, ! array_diff($allIds, $ids));
                    }
                }),
        ]);
    }

        public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->label('Rol')
                ->sortable()
                ->searchable(),

            // İzin adlarını listelemiyoruz, sadece toplam sayıyı gösteriyoruz
            TextColumn::make('permissions_count')
                ->label('İzin sayısı')
                ->counts('permissions')   // <-- Filament otomatik withCount ekler
                ->sortable(),

            TextColumn::make('created_at')
                ->label('Kayıt')
                ->dateTime('d.m.Y H:i'),
        ])
        ->actions([
            \Filament\Tables\Actions\EditAction::make(),
            \Filament\Tables\Actions\DeleteAction::make(),
        ]);
}
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
