<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServicePackageResource\Pages;
use App\Models\Service;
use App\Models\ServicePackage;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Get;

class ServicePackageResource extends Resource
{
    protected static ?string $model = ServicePackage::class;

    protected static ?string $navigationIcon  = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Tanımlar';
    protected static ?int    $navigationSort  = 11;

    public static function getModelLabel(): string   { return 'Paket'; }
    public static function getPluralLabel(): string  { return 'Paketler'; }

    public static function form(Form $form): Form
    {
        $serviceOptions = Service::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn (Service $s) => [
                $s->id => $s->name.' — '.number_format((float) $s->price, 2, ',', '.').' ₺',
            ])
            ->all();

        return $form->schema([
            TextInput::make('name')
                ->label('Ad')
                ->required()
                ->maxLength(160)
                ->unique(ignoreRecord: true),

            Textarea::make('summary')
                ->label('Kısa Açıklama')
                ->maxLength(2000),

            Repeater::make('items')
                ->label('Paket İçeriği')
                ->minItems(1)
                ->dehydrated(true)
                ->reactive()
                ->live()
                ->columns(2)
                ->schema([
                    Select::make('service_id')
                        ->label('Hizmet')
                        ->options($serviceOptions)
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->live(),
                    TextInput::make('quantity')
                        ->label('Adet')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->required()
                        ->reactive()
                        ->live(debounce: 300),
                ])
                ->itemLabel(fn (array $state): ?string =>
                    isset($state['service_id']) ? ($serviceOptions[$state['service_id']] ?? null) : null
                ),

            TextInput::make('discount_percent')
                ->label('İndirim (%)')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->nullable()
                ->hint('Önce paket içeriğinden en az bir hizmet seçin')
                ->disabled(fn (Get $get) => empty($get('items')))
                ->reactive()
                ->live(debounce: 300),

            ViewField::make('live_summary')
                ->view('filament.forms.fields.service-package-summary')
                ->viewData([
                    'prices' => Service::query()
                        ->where('is_active', true)
                        ->pluck('price', 'id')
                        ->toArray(),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ad')
                    ->searchable()
                    ->sortable(),

                // İndirimsiz toplam
                TextColumn::make('computed_gross_price')
                    ->label('Toplam (₺)')
                    ->getStateUsing(fn (ServicePackage $r) => $r->computed_gross_price)
                    ->formatStateUsing(fn ($state) =>
                        number_format((float) $state, 2, ',', '.').' ₺'
                    ),

                // İndirim yüzdesi
                TextColumn::make('discount_percent')
                    ->label('İnd. (%)')
                    ->formatStateUsing(fn ($state) =>
                        $state === null ? '-' : number_format((float) $state, 2, ',', '.')
                    ),

                // İndirimli toplam
                TextColumn::make('computed_price')
                    ->label('İndirimli (₺)')
                    ->getStateUsing(fn (ServicePackage $r) => $r->computed_price)
                    ->formatStateUsing(fn ($state) =>
                        number_format((float) $state, 2, ',', '.').' ₺'
                    ),

                // TextColumn::make('computed_duration_minutes')->label('Toplam Süre (dk)'),

                IconColumn::make('is_active')->label('Aktif')->boolean()->sortable(),
                TextColumn::make('created_at')->label('Kayıt')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
}
