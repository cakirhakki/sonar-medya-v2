<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Hizmetler';
    protected static ?string $navigationLabel = 'Hizmetler';
    protected static ?string $modelLabel = 'Hizmet';
    protected static ?string $pluralModelLabel = 'Hizmetler';

    public static function getNavigationBadge(): ?string
    {
        return (string) Service::query()->count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // GENEL
            Forms\Components\Section::make('Genel')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('service_category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder('Kategori seçin')
                        ->helperText('Hizmetin ait olduğu ana kategori.'),

                    Forms\Components\TextInput::make('name')
                        ->label('Ad')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $currentSlug = $get('slug');
                            if (blank($currentSlug)) {
                                $set('slug', Str::slug($state ?? ''));
                            }
                        })
                        ->helperText('Müşterinin göreceği hizmet adı.'),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Boş bırakabilirsiniz, kayıtta otomatik oluşur.'),

                    Forms\Components\Textarea::make('excerpt')
                        ->label('Özet')
                        ->rows(3)
                        ->columnSpanFull()
                        ->helperText('Liste/önizleme alanlarında görünen kısa açıklama (opsiyonel).'),

                    Forms\Components\RichEditor::make('description')
                        ->label('Açıklama')
                        ->toolbarButtons([
                            'bold','italic','strike','link',
                            'orderedList','unorderedList','blockquote',
                            'h2','h3','codeBlock',
                        ])
                        ->columnSpanFull()
                        ->helperText('Detay sayfasında görünen kapsamlı açıklama.'),
                ]),

            // VİTRİN
            Forms\Components\Section::make('Vitrin')
                ->columns(3)
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif mi?')
                        ->default(true)
                        ->helperText('Pasif ise vitrinde ve aramalarda görünmez.'),

                    Forms\Components\Toggle::make('is_featured')
                        ->label('Öne Çıkar?')
                        ->default(false)
                        ->helperText('Anasayfa/özel bloklarda vurgulanması için işaretleyin.'),

                    Forms\Components\TextInput::make('display_order')
                        ->label('Sıra')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Düşük değerler daha üstte listelenir.'),
                ]),

            // SÜRE / FİYAT
            Forms\Components\Section::make('Süre / Fiyat')
                ->columns(4)
                ->schema([
                    Forms\Components\Select::make('unit')
                        ->label('Birim')
                        ->options(['adet' => 'Adet','saat' => 'Saat','gün' => 'Gün'])
                        ->native(false)
                        ->searchable()
                        ->helperText('Fiyatın hangi birime göre hesaplandığı.'),

                    Forms\Components\TextInput::make('base_price')
                        ->label('Liste Fiyatı')
                        ->numeric()
                        ->prefix('₺')
                        ->rules(['nullable','numeric','min:0'])
                        ->helperText('Hizmetin temel ücreti (genellikle KDV hariç).'),

                    Forms\Components\TextInput::make('setup_fee')
                        ->label('Kurulum Bedeli')
                        ->numeric()
                        ->prefix('₺')
                        ->rules(['nullable','numeric','min:0'])
                        ->helperText('Müşteriden <b>bir defaya mahsus</b> tahsil edilen başlangıç/entegrasyon ücreti.'),

                    Forms\Components\TextInput::make('tax_rate_percent')
                        ->label('KDV %')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%')
                        ->helperText('Vergi oranı (0–100).'),

                    // --- SÜRE (insan-okur giriş) ---
                    Forms\Components\TextInput::make('duration_value')
                        ->label('Süre Değeri')
                        ->numeric()
                        ->minValue(0)
                        ->placeholder('Örn: 30')
                        ->helperText('Hizmetin ortalama tamamlanma süresi (aşağıdaki birime göre).')
                        ->reactive()
                        ->dehydrated(false) // DB'ye yazma
                        ->afterStateHydrated(function (
                            Forms\Components\TextInput $component,
                            $state,
                            ?Service $record,
                            Set $set
                        ) {
                            // Kaydı açarken duration_minutes'tan duration_value + duration_unit'i tahmin et
                            if (!$record || $record->duration_minutes === null) {
                                return;
                            }
                            $m = (int) $record->duration_minutes;
                            if ($m % 1440 === 0) {
                                $component->state($m / 1440);
                                $set('duration_unit', 'gün');
                            } elseif ($m % 60 === 0) {
                                $component->state($m / 60);
                                $set('duration_unit', 'saat');
                            } else {
                                $component->state($m);
                                $set('duration_unit', 'dakika');
                            }
                        }),

                    Forms\Components\Select::make('duration_unit')
                        ->label('Süre Birimi')
                        ->options([
                            'dakika' => 'Dakika',
                            'saat'   => 'Saat',
                            'gün'    => 'Gün',
                        ])
                        ->default('gün')
                        ->reactive()
                        ->dehydrated(false)
                        ->helperText('Örn: 30 + gün ⇒ 30 gün'),

                    // Asıl DB alanı (dakika). Kullanıcıya gösterme.
                    Forms\Components\Hidden::make('duration_minutes')
                        ->dehydrateStateUsing(function (Get $get, $state) {
                            $value = (float) ($get('duration_value') ?? 0);
                            $unit  = $get('duration_unit') ?? 'dakika';

                            return match ($unit) {
                                'gün'  => (int) round($value * 1440),
                                'saat' => (int) round($value * 60),
                                default => (int) round($value),
                            };
                        }),
                ]),

            // ETİKETLER
            Forms\Components\Section::make('Etiketler')
                ->schema([
                    Forms\Components\TagsInput::make('tags')
                        ->label('Etiketler')
                        ->placeholder('Etiket ekle')
                        ->suggestions(function () {
                            try {
                                return \Spatie\Tags\Tag::pluck('name')->map(fn ($n) => (string) $n)->all();
                            } catch (\Throwable $e) {
                                return [];
                            }
                        })
                        ->helperText('Arama ve listeleme için etiketler. Kaydederken otomatik senkronize edilir.'),
                ])->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('display_order')
            ->defaultSort('display_order')
            ->columns([
                Tables\Columns\TextColumn::make('display_order')
                    ->label('Sıra')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->width('72px'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Ad')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('unit')
                    ->label('Birim')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Fiyat')
                    ->numeric(2)
                    ->prefix('₺')
                    ->sortable()
                    ->toggleable(),

                // Okunabilir süre (model accessor: duration_readable)
                Tables\Columns\TextColumn::make('duration_readable')
                    ->label('Süre')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Öne Çıkar')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncellendi')
                    ->dateTime('d.m.Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->preload()
                    ->searchable(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif mi?')
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif')
                    ->nullable(),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Öne Çıkar')
                    ->trueLabel('Evet')
                    ->falseLabel('Hayır')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),
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
            'index'  => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit'   => Pages\EditService::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'excerpt', 'description'];
    }
    public static function getRelations(): array
{
    return [
        \App\Filament\Resources\ServiceResource\RelationManagers\FaqsRelationManager::class,
    ];
}
}
