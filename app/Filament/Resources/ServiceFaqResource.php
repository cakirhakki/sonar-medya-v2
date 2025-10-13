<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceFaqResource\Pages;
use App\Models\Service;
use App\Models\ServiceFaq;
use App\Models\ServicePackage;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceFaqResource extends Resource
{
    protected static ?string $model = ServiceFaq::class;

    protected static ?string $navigationIcon   = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup  = 'Hizmetler';
    protected static ?string $navigationLabel  = 'SSS (Genel)';
    protected static ?string $modelLabel       = 'SSS';
    protected static ?string $pluralModelLabel = 'SSS';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Bağlantı')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('faqable_type')
                        ->label('Bağlı Tip')
                        ->options([
                            Service::class        => 'Hizmet',
                            ServicePackage::class => 'Paket',
                        ])
                        ->required()
                        ->reactive()
                        // Tip değişince bağlı id’yi sıfırla
                        ->afterStateUpdated(fn (Set $set) => $set('faqable_id', null)),

                    Forms\Components\Select::make('faqable_id')
                        ->label('Bağlı Kayıt')
                        ->searchable()
                        ->required()
                        ->options(function (callable $get) {
                            $type = (string) $get('faqable_type');
                            if ($type === Service::class) {
                                return Service::query()->orderBy('name')->pluck('name', 'id');
                            }
                            if ($type === ServicePackage::class) {
                                return ServicePackage::query()->orderBy('name')->pluck('name', 'id');
                            }
                            return collect();
                        })
                        ->preload()
                        // Seçili id gerçekten seçilen tip içinde var mı?
                        ->rule(fn (Get $get) => function (string $attribute, $value, Closure $fail) use ($get) {
                            $type = (string) $get('faqable_type');

                            if (! $type) {
                                $fail('Önce "Bağlı Tip" seçilmelidir.');
                                return;
                            }

                            if ($type === Service::class) {
                                if (! Service::query()->whereKey($value)->exists()) {
                                    $fail('Seçilen kayıt "Hizmet" içinde bulunamadı.');
                                }
                                return;
                            }

                            if ($type === ServicePackage::class) {
                                if (! ServicePackage::query()->whereKey($value)->exists()) {
                                    $fail('Seçilen kayıt "Paket" içinde bulunamadı.');
                                }
                                return;
                            }

                            $fail('Geçersiz bağlı tip.');
                        }),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Sıra')
                        ->numeric()
                        ->default(0)
                        ->minValue(0),
                ]),

            Forms\Components\Section::make('İçerik')
                ->schema([
                    Forms\Components\TextInput::make('question')
                        ->label('Soru')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\RichEditor::make('answer')
                        ->label('Cevap')
                        ->toolbarButtons([
                            'bold','italic','strike','link',
                            'orderedList','unorderedList','blockquote',
                            'h2','h3',
                        ])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // >>> Gruplama yok: her SSS tek satır
            ->query(fn () => ServiceFaq::query()->with('faqable'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('type_label')
                    ->label('Tip')
                    ->badge()
                    ->getStateUsing(function (ServiceFaq $record) {
                        return match ($record->faqable_type) {
                            Service::class        => 'Hizmet',
                            ServicePackage::class => 'Paket',
                            default               => 'Bilinmiyor',
                        };
                    }),

                Tables\Columns\TextColumn::make('parent_name')
                    ->label('Kayıt')
                    ->getStateUsing(function (ServiceFaq $record) {
                        return $record->faqable?->name ?? "ID #{$record->faqable_id}";
                    })
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('question')
                    ->label('Soru')
                    ->limit(60)
                    ->searchable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // Tip + Parent seçmeli gelişmiş filtre
            ->filters([
                Filter::make('baglanti')
                    ->label('Bağlantı')
                    ->form([
                        Forms\Components\Select::make('faqable_type')
                            ->label('Bağlı Tip')
                            ->options([
                                Service::class        => 'Hizmet',
                                ServicePackage::class => 'Paket',
                            ])
                            ->reactive()
                            ->afterStateUpdated(fn (Set $set) => $set('faqable_id', null)),

                        Forms\Components\Select::make('faqable_id')
                            ->label('Bağlı Kayıt')
                            ->options(function (Get $get) {
                                $type = (string) $get('faqable_type');
                                if ($type === Service::class) {
                                    return Service::query()->orderBy('name')->pluck('name', 'id');
                                }
                                if ($type === ServicePackage::class) {
                                    return ServicePackage::query()->orderBy('name')->pluck('name', 'id');
                                }
                                return collect();
                            })
                            ->searchable()
                            ->preload(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (! empty($data['faqable_type'])) {
                            $query->where('faqable_type', $data['faqable_type']);
                        }
                        if (! empty($data['faqable_id'])) {
                            $query->where('faqable_id', $data['faqable_id']);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),
                Tables\Actions\DeleteAction::make()->label('Sil'),
                // İstersen parent’a git butonu da kalsın:
                // Tables\Actions\Action::make('parent')
                //     ->label('Parent’a Git')
                //     ->icon('heroicon-o-arrow-top-right-on-square')
                //     ->url(fn (ServiceFaq $r) => match ($r->faqable_type) {
                //         Service::class => \App\Filament\Resources\ServiceResource::getUrl('edit', [
                //             'record' => $r->faqable_id,
                //             'activeRelationManager' => 'faqs',
                //         ]),
                //         ServicePackage::class => \App\Filament\Resources\ServicePackageResource::getUrl('edit', [
                //             'record' => $r->faqable_id,
                //             'activeRelationManager' => 'faqs',
                //         ]),
                //         default => url()->current(),
                //     }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Seçiliyi Sil'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListServiceFaqs::route('/'),
            'create' => Pages\CreateServiceFaq::route('/create'),
            'edit'   => Pages\EditServiceFaq::route('/{record}/edit'),
        ];
    }
}
