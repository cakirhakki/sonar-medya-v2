<?php

namespace App\Filament\Resources\ServicePackageResource\RelationManagers;

use App\Models\PackageItem;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Satırlar';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(12)->schema([
                Forms\Components\Select::make('type')
                    ->label('Tip')
                    ->options([
                        'service' => 'Hizmet (katalogdan)',
                        'custom'  => 'Özel Satır',
                    ])
                    ->default('service')
                    ->required()
                    ->reactive()
                    ->columnSpan(3),

                Forms\Components\Select::make('service_id')
                    ->label('Hizmet')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn (callable $get) => $get('type') === 'service')
                    ->helperText('Katalogdaki bir hizmetle eşle.')
                    ->columnSpan(5),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Sıra')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->columnSpan(2),

                Forms\Components\TextInput::make('unit')
                    ->label('Birim')
                    ->datalist(['adet', 'saat', 'gün'])
                    ->columnSpan(2),

                Forms\Components\TextInput::make('qty')
                    ->label('Adet')
                    ->numeric()
                    ->default(1)
                    ->minValue(0)
                    ->columnSpan(2),

                Forms\Components\TextInput::make('unit_price')
                    ->label('Birim Fiyat')
                    ->numeric()
                    ->prefix('₺')
                    ->minValue(0)
                    ->columnSpan(3),

                Forms\Components\TextInput::make('discount_amount')
                    ->label('İndirim')
                    ->numeric()
                    ->prefix('₺')
                    ->minValue(0)
                    ->default(0)
                    ->helperText('Vergi öncesi ara toplamdan düşülür.')
                    ->columnSpan(3),

                Forms\Components\TextInput::make('tax_rate_percent')
                    ->label('KDV %')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->columnSpan(2),

                Forms\Components\TextInput::make('name')
                    ->label('Satır Adı')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('description')
                    ->label('Açıklama')
                    ->rows(3)
                    ->columnSpanFull(),

                // ✅ GÖRSEL
                Forms\Components\FileUpload::make('image_path')
                    ->label('Görsel')
                    ->image()
                    ->imageEditor()
                    ->directory('package-items')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(2048) // 2MB
                    ->helperText('Önerilen: 1600×900 WebP (<400KB)')
                    ->columnSpan(6),

                Forms\Components\TextInput::make('image_alt')
                    ->label('Görsel Alt Metni')
                    ->maxLength(255)
                    ->columnSpan(6),

                Forms\Components\KeyValue::make('snapshot_json')
                    ->label('Ek Özellikler (ops.)')
                    ->keyLabel('Sunduğumuz Çözüm')
                    ->valueLabel('Neden Gerekli?')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Görsel')
                    ->disk('public')
                    ->height(36)
                    ->width(36)
                    ->circular()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable()
                    ->width('64px')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tip')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'service' ? 'Hizmet' : 'Özel'),

                Tables\Columns\TextColumn::make('service.name')
                    ->label('Hizmet')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Satır Adı')
                    ->searchable()
                    ->limit(40)
                    ->wrap(),

                Tables\Columns\TextColumn::make('qty')
                    ->label('Adet')
                    ->numeric(3)
                    ->alignRight(),

                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Birim Fiyat')
                    ->numeric(2)
                    ->prefix('₺')
                    ->alignRight(),

                Tables\Columns\TextColumn::make('discount_amount')
                    ->label('İndirim')
                    ->numeric(2)
                    ->prefix('₺')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('tax_rate_percent')
                    ->label('KDV %')
                    ->suffix('%')
                    ->alignRight()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('line_subtotal')
                    ->label('Ara Toplam')
                    ->getStateUsing(fn (PackageItem $record) => $record->lineSubtotal())
                    ->formatStateUsing(fn ($state) => '₺ ' . number_format((float) $state, 2, ',', '.'))
                    ->alignRight(),

                Tables\Columns\TextColumn::make('line_total')
                    ->label('Toplam')
                    ->getStateUsing(fn (PackageItem $record) => $record->lineTotal())
                    ->formatStateUsing(fn ($state) => '₺ ' . number_format((float) $state, 2, ',', '.'))
                    ->weight('bold')
                    ->alignRight(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Satır Ekle')
                    ->mutateFormDataUsing(fn (array $data) => $this->applyServiceSnapshot($data)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Düzenle')
                    ->mutateFormDataUsing(fn (array $data) => $this->applyServiceSnapshot($data)),

                Tables\Actions\DeleteAction::make()
                    ->label('Sil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Seçilenleri Sil'),
                ]),
            ]);
    }

    /**
     * type=service ise servis değerlerinden boş alanları snapshot olarak doldur.
     */
    protected function applyServiceSnapshot(array $data): array
    {
        if (($data['type'] ?? null) === 'service' && ! empty($data['service_id'])) {
            if ($service = Service::find($data['service_id'])) {
                $data['name']        = $data['name']        ?: $service->name;
                $data['description'] = $data['description'] ?: ($service->excerpt ?? $service->description ?? null);
                $data['unit']        = $data['unit']        ?: $service->unit;

                if ($data['unit_price'] === null || $data['unit_price'] === '') {
                    $data['unit_price'] = $service->base_price;
                }
                if ($data['tax_rate_percent'] === null || $data['tax_rate_percent'] === '') {
                    $data['tax_rate_percent'] = $service->tax_rate_percent;
                }

                // (Opsiyonel) Hizmette görsel varsa boş ise kopyala:
                // if (empty($data['image_path']) && !empty($service->image_path)) {
                //     $data['image_path'] = $service->image_path;
                //     $data['image_alt']  = $service->name;
                // }
            }
        }

        return $data;
    }
}
