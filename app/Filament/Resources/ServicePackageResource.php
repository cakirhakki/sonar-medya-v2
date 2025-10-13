<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServicePackageResource\Pages;
use App\Models\ServicePackage;
use App\Models\PackageCategory;
use App\Models\PackageItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServicePackageResource extends Resource
{
    protected static ?string $model = ServicePackage::class;

    protected static ?string $navigationIcon   = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup  = 'Hizmetler';
    protected static ?string $navigationLabel  = 'Paketler';
    protected static ?string $modelLabel       = 'Paket';
    protected static ?string $pluralModelLabel = 'Paketler';

    public static function getNavigationBadge(): ?string
    {
        return (string) ServicePackage::query()->count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // GENEL
            Forms\Components\Section::make('Genel')
                ->columns(2)
                ->schema([
                    Placeholder::make('items_info')
                        ->content('Satırları eklemek için önce paketi oluşturun. Kaydettikten sonra **Satırlar** sekmesi aktif olur.')
                        ->columnSpanFull()
                        ->visible(fn ($livewire) => $livewire instanceof CreateRecord),

                    // Paket Kategorisi (opsiyonel)
                    Forms\Components\Select::make('package_category_id')
                        ->label('Paket Kategorisi (opsiyonel)')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->nullable()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            if (blank($get('slug'))) {
                                $name = (string) ($get('name') ?? '');
                                $base = $name;

                                if ($state) {
                                    $cat = PackageCategory::withTrashed()->find($state);
                                    if ($cat) {
                                        $prefix = $cat->slug ?: ($cat->name ?? '');
                                        if ($prefix) {
                                            $base = trim($prefix . ' ' . $name);
                                        }
                                    }
                                }

                                $set('slug', Str::slug($base, '-', 'tr'));
                            }
                        })
                        ->helperText('İsterseniz bir kategoriye bağlayın; slug boşsa kategori + ad üzerinden otomatik üretilir.'),

                    Forms\Components\TextInput::make('name')
                        ->label('Paket Adı')
                        ->required()
                        ->maxLength(255)
                        ->live(debounce: 500)
                        ->afterStateUpdated(function (string $state, Set $set, Get $get) {
                            if (blank($get('slug'))) {
                                $catId = $get('package_category_id');
                                $base  = $state;

                                if ($catId) {
                                    $cat = PackageCategory::withTrashed()->find($catId);
                                    if ($cat) {
                                        $prefix = $cat->slug ?: ($cat->name ?? '');
                                        if ($prefix) {
                                            $base = trim($prefix . ' ' . $state);
                                        }
                                    }
                                }

                                $set('slug', Str::slug($base, '-', 'tr'));
                            }
                        })
                        ->helperText('Teklif/Paket için görünen ad.'),

                    // SLUG
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->rule('regex:/^[a-z0-9-]+$/')
                        ->helperText('Slug boşsa otomatik üretilir. Küçük harf, sayı ve tire (-) kullanılabilir. Örn: ikas-pro-paketi'),

                    Forms\Components\TextInput::make('code')
                        ->label('Kod')
                        ->maxLength(64)
                        ->unique(ignoreRecord: true)
                        ->helperText('Boş bırakırsanız otomatik üretilir (örn. PKG-YYYY-MM-DD-XXXX).'),

                    // KISA AÇIKLAMA
                    Forms\Components\TextInput::make('short_description')
                        ->label('Kısa Açıklama')
                        ->maxLength(255)
                        ->helperText('Liste ve kart görünümünde özet metin olarak kullanılabilir.')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('description')
                        ->label('Açıklama')
                        ->rows(3)
                        ->columnSpanFull(),

                    // DETAYDA FİYAT GÖSTER
                    Forms\Components\Toggle::make('show_price')
                        ->label('Listede Fiyat Göster')
                        ->inline(false)
                        ->default(true),
                ]),

            // FİYAT & PB
            Forms\Components\Section::make('Fiyat & Para Birimi')
                ->columns(4)
                ->schema([
                    Forms\Components\TextInput::make('currency')
                        ->label('Para Birimi')
                        ->maxLength(3)
                        ->default('TRY')
                        ->helperText('ISO 4217 kodu (örn. TRY, USD, EUR).')
                        ->required(),

                    Forms\Components\TextInput::make('currency_rate')
                        ->label('Kur')
                        ->numeric()
                        ->rules(['nullable', 'numeric', 'min:0'])
                        ->helperText('Opsiyonel. Çoklu para senaryosunda referans kur.'),

                    Forms\Components\TextInput::make('override_price')
                        ->label('Manuel Toplam (Override)')
                        ->numeric()
                        ->prefix('₺')
                        ->rules(['nullable', 'numeric', 'min:0'])
                        ->helperText('Doluysa satır toplamları yerine bu değer **nihai toplam** olarak kullanılır.'),

                    Forms\Components\Select::make('status')
                        ->label('Durum')
                        ->options([
                            ServicePackage::STATUS_DRAFT     => 'Taslak',
                            ServicePackage::STATUS_SENT      => 'Gönderildi',
                            ServicePackage::STATUS_ACCEPTED  => 'Kabul Edildi',
                            ServicePackage::STATUS_PUBLISHED => 'Yayınlandı',
                        ])
                        ->default(ServicePackage::STATUS_DRAFT)
                        ->native(false)
                        ->required(),
                ]),

            // TARİHLER
            Forms\Components\Section::make('Durum Tarihleri (Opsiyonel)')
                ->columns(4)
                ->schema([
                    Forms\Components\DateTimePicker::make('sent_at')->label('Gönderim'),
                    Forms\Components\DateTimePicker::make('accepted_at')->label('Kabul'),
                    Forms\Components\DateTimePicker::make('published_at')->label('Yayınlanma'),
                    Forms\Components\DateTimePicker::make('expires_at')->label('Son Geçerlilik'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kod')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kopyalandı')
                    ->badge(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('name')
                    ->label('Ad')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('short_description')
                    ->label('Kısa Açıklama')
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('show_price')
                    ->label('Fiyat')
                    ->boolean(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            ServicePackage::STATUS_DRAFT     => 'Taslak',
                            ServicePackage::STATUS_SENT      => 'Gönderildi',
                            ServicePackage::STATUS_ACCEPTED  => 'Kabul Edildi',
                            ServicePackage::STATUS_PUBLISHED => 'Yayınlandı',
                            default                          => $state,
                        };
                    })
                    ->colors([
                        'warning' => ServicePackage::STATUS_DRAFT,
                        'info'    => ServicePackage::STATUS_SENT,
                        'success' => [ServicePackage::STATUS_ACCEPTED, ServicePackage::STATUS_PUBLISHED],
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency')
                    ->label('PB')
                    ->tooltip('Para Birimi')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('override_price')
                    ->label('Override')
                    ->numeric(2)
                    ->prefix('₺')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('computed_total')
                    ->label('Hesaplanan Toplam')
                    ->getStateUsing(fn (ServicePackage $record) => $record->computedTotal())
                    ->formatStateUsing(fn ($state, ServicePackage $record) => ($record->currency ?: '₺') . ' ' . number_format((float) $state, 2, ',', '.')),

                Tables\Columns\TextColumn::make('final_total')
                    ->label('Nihai Toplam')
                    ->getStateUsing(fn (ServicePackage $record) => $record->finalTotal())
                    ->formatStateUsing(fn ($state, ServicePackage $record) => ($record->currency ?: '₺') . ' ' . number_format((float) $state, 2, ',', '.'))
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncellendi')
                    ->dateTime('d.m.Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('package_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->placeholder('Tümü'),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        ServicePackage::STATUS_DRAFT     => 'Taslak',
                        ServicePackage::STATUS_SENT      => 'Gönderildi',
                        ServicePackage::STATUS_ACCEPTED  => 'Kabul Edildi',
                        ServicePackage::STATUS_PUBLISHED => 'Yayınlandı',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),

                // 🔁 KOPYALA
                Action::make('duplicate')
                    ->label('Kopyala')
                    ->icon('heroicon-o-document-duplicate')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->label('Yeni Paket Adı')
                            ->default(fn (ServicePackage $record) => $record->name . ' (Kopya)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('package_category_id')
                            ->label('Kategori (opsiyonel)')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->nullable(),
                    ])
                    ->action(function (ServicePackage $record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            // 1) Ana paket kopyala (slug & code null -> model creating içinde yeniden üretilir)
                            $copy = $record->replicate([
                                'slug', 'code', 'status',
                                'sent_at','accepted_at','published_at','expires_at',
                            ]);
                            $copy->name = $data['name'] ?? ($record->name.' (Kopya)');
                            $copy->package_category_id = $data['package_category_id'] ?? $record->package_category_id;
                            $copy->slug = null;
                            $copy->code = null;
                            $copy->status = ServicePackage::STATUS_DRAFT;
                            $copy->sent_at = $copy->accepted_at = $copy->published_at = $copy->expires_at = null;
                            $copy->save();

                            // 2) Satırları kopyala (ebeveyn haritası ile)
                            $record->loadMissing('items');
                            $idMap = [];

                            foreach ($record->items as $item) {
                                $newItem = $item->replicate();
                                $newItem->service_package_id = $copy->id;
                                $newItem->parent_item_id = null; // geçici
                                $newItem->save();
                                $idMap[$item->id] = $newItem->id;
                            }

                            // Ebeveyn referanslarını güncelle
                            foreach ($record->items as $item) {
                                if ($item->parent_item_id) {
                                    $newItemId   = $idMap[$item->id] ?? null;
                                    $newParentId = $idMap[$item->parent_item_id] ?? null;
                                    if ($newItemId) {
                                        PackageItem::where('id', $newItemId)->update([
                                            'parent_item_id' => $newParentId,
                                        ]);
                                    }
                                }
                            }

                            // 3) FAQ'ları kopyala (varsa)
                            $record->loadMissing('faqs');
                            foreach ($record->faqs as $faq) {
                                $faqNew = $faq->replicate();
                                $faqNew->faqable_id = $copy->id;
                                $faqNew->save();
                            }

                            Notification::make()
                                ->title('Paket kopyalandı')
                                ->body('Yeni kayıt taslak olarak oluşturuldu.')
                                ->success()
                                ->send();
                        });
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Paketi Kopyala')
                    ->modalSubmitActionLabel('Kopyala'),

                Tables\Actions\DeleteAction::make()->label('Sil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Seçilenleri Sil'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListServicePackages::route('/'),
            'create' => Pages\CreateServicePackage::route('/create'),
            'edit'   => Pages\EditServicePackage::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'code', 'description', 'short_description', 'status'];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\ServicePackageResource\RelationManagers\ItemsRelationManager::class,
            \App\Filament\Resources\ServicePackageResource\RelationManagers\FaqsRelationManager::class,
        ];
    }
}