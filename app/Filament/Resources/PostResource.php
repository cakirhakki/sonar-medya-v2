<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\{
    Grid, Group, Section, Fieldset, Tabs,
    TextInput, Textarea, Select, DatePicker, FileUpload,
    Toggle, Repeater, RichEditor, View
};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, ImageColumn};
use Filament\Tables\Filters\{SelectFilter, Filter, TernaryFilter};
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Str;
use Spatie\Tags\Tag;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Placeholder;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'İçerik';
    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?int    $navigationSort  = 10;

    protected static ?string $modelLabel       = 'Makale';
    protected static ?string $pluralModelLabel = 'Makaleler';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(12)->schema([

                // SOL SÜTUN
                Group::make()->columnSpan(8)->schema([

                    Section::make('Temel')->schema([
                        TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->unique(table: (new Post)->getTable(), column: 'slug', ignoreRecord: true)
                            ->required()
                            ->helperText('Boş bırakırsanız başlıktan otomatik üretilir.'),

                        Textarea::make('excerpt')
                            ->label('Kısa Özet')
                            ->rows(3)
                            ->maxLength(600),
                    ])->columns(2),

                    // === İçerik: 3 sekmeli modern UX ===
                    Section::make('İçerik')
                        ->description('Makale gövdesini zengin metin olarak düzenleyin, gerekirse HTML kaynağına geçin ve canlı önizleyin.')
                        ->schema([
                            Tabs::make('contentTabs')
                                ->tabs([
                                    Tabs\Tab::make('Görsel Editör')->schema([
                                        RichEditor::make('content')
                                            ->label('İçerik')
                                            ->toolbarButtons([
                                                'heading','blockquote','bold','italic','strike',
                                                'link','orderedList','bulletList',
                                                'codeBlock','hr','undo','redo',
                                            ])
                                            ->fileAttachmentsDisk('public')
                                            ->fileAttachmentsDirectory('posts/content')
                                            ->placeholder('Yazmaya başlayın…')
                                            ->live(debounce: 400)   // canlı state
                                            ->columnSpanFull()
                                            ->required(),
                                    ]),
                                    Tabs\Tab::make('HTML Kaynak')->schema([
                                        Textarea::make('content')
                                            ->rows(28)
                                            ->autosize(false)
                                            ->extraAttributes(['class' => 'font-mono text-sm'])
                                            ->hint('HTML kaynağını düzenliyorsunuz')
                                            ->live(debounce: 300)   // canlı state
                                            ->columnSpanFull(),
                                    ]),
                                    Tabs\Tab::make('Önizleme')->schema([
                                        Placeholder::make('preview')
                                            ->label('')
                                            ->content(function (Get $get) {
                                                $html = (string) ($get('content') ?? '');
                                                if (trim(strip_tags($html)) === '') {
                                                    return new HtmlString('<div class="text-sm text-gray-500 dark:text-gray-400">Önizleme için içerik girin (Görsel                     Editör veya HTML Kaynak).</div>');
                                                }
                                                // .prose yoksa sınıfı kaldırabilirsin
                                                return new HtmlString('<div class="prose dark:prose-invert max-w-none">'.$html.'</div>');
                                            })
                                            ->columnSpanFull(),
                                    ]),
                                ])
                                ->columnSpanFull(),
                        ]),



                    // === Medya ===
                    Section::make('Medya')->schema([
                        FileUpload::make('featured_image_path')
                            ->label('Kapak Görseli')
                            ->disk('public')
                            ->directory('posts/featured')
                            ->image()
                            ->imageEditor()
                            ->helperText('Öneri: 1600×900 (16:9), JPG/WEBP')
                            ->nullable(),

                        Fieldset::make('Görsel Metaverileri (Açıklama & Telif)')
                            ->columns(2)
                            ->schema([
                                TextInput::make('featured_image_alt')
                                    ->label('Alt Metin (erişilebilirlik)')
                                    ->placeholder('Örn. Üretim hattından bir kare')
                                    ->maxLength(255),
                                TextInput::make('featured_image_caption')
                                    ->label('Caption')
                                    ->placeholder('Görsel altında küçük açıklama')
                                    ->maxLength(255),
                                TextInput::make('featured_image_credit_text')
                                    ->label('Kaynak / Telif (Metin)')
                                    ->placeholder('Örn. Foto: Jane Doe / Unsplash')
                                    ->maxLength(255),
                                TextInput::make('featured_image_credit_url')
                                    ->label('Kaynak (URL)')
                                    ->prefixIcon('heroicon-m-link')
                                    ->url()
                                    ->maxLength(512),
                            ]),
                    ])->columns(1),

                    // === SEO ===
                    Section::make('SEO')->schema([
                        TextInput::make('meta_title')->label('Meta Title')->maxLength(255),
                        Textarea::make('meta_description')->label('Meta Description')->rows(2)->maxLength(300),
                        FileUpload::make('meta_image')
                            ->label('Meta Görseli')
                            ->disk('public')
                            ->directory('posts/seo')
                            ->image()
                            ->nullable(),
                    ])->columns(2),

                    // === Etiketler ===
                    Section::make('Etiketler')->schema([
                        // Spatie Tags - öneri + yeni oluşturma akışı
                        \Filament\Forms\Components\Select::make('tags')
                            ->label('Etiketler')
                            ->placeholder('Etiket yaz, öneriden seç; sonuç yoksa yeni etiket oluştur')
                            ->helperText('Örnek: laravel, php, filament')
                            ->multiple()
                            ->searchable()
                            ->preload(false)
                            ->getSearchResultsUsing(function (string $search): array {
                                $q = trim($search);
                                if ($q === '') return [];
                                $locale = app()->getLocale() ?? 'tr';

                                return Tag::query()
                                    ->where("name->{$locale}", 'like', "%{$q}%")
                                    ->limit(10)
                                    ->get()
                                    ->mapWithKeys(fn (Tag $t) => [
                                        $t->getKey() => $t->getTranslation('name', $locale),
                                    ])
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                $tag = Tag::find($value);
                                return $tag?->getTranslation('name', app()->getLocale() ?? 'tr');
                            })
                            ->createOptionForm([
                                TextInput::make('name')->label('Yeni etiket')->required(),
                            ])
                            ->createOptionUsing(function (array $data) {
                                $locale = app()->getLocale() ?? 'tr';
                                $tag = Tag::findOrCreate($data['name'], $locale);
                                return $tag->getKey();
                            })
                            ->relationship(name: 'tags', titleAttribute: 'name'),
                    ]),
                ]),

                // SAĞ SÜTUN
                Group::make()->columnSpan(4)->schema([

                    Section::make('İlişkiler')->schema([
                        \Filament\Forms\Components\Select::make('primary_post_category_id')
                            ->label('Kategori')
                            ->relationship('primaryCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        \Filament\Forms\Components\Select::make('author_id')
                            ->label('Yazar')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => Filament::auth()->id())
                            ->required(),
                    ]),

                    Section::make('Yayınlama')->schema([
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'draft'     => 'Taslak',
                                'scheduled' => 'Planlı',
                                'published' => 'Yayında',
                            ])
                            ->default('published')
                            ->required()
                            ->live(),

                        DatePicker::make('published_at')
                            ->label('Yayın Tarihi')
                            ->helperText('Planlı / Yayında için tarih gir.')
                            ->displayFormat('d.m.Y')
                            ->default(fn () => now()->startOfDay())
                            ->required(fn (Get $get) => in_array($get('status'), ['scheduled', 'published'])),
                    ]),

                    Section::make('İstatistik')->schema([
                        TextInput::make('reading_time')->label('Okuma (dk)')->disabled()->dehydrated(false),
                        TextInput::make('views')->label('Görüntülenme')->disabled()->dehydrated(false),
                    ]),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image_path')->label('')->disk('public')->square()->height(48),
                TextColumn::make('title')->label('Başlık')->searchable()->limit(40)->wrap(),
                TextColumn::make('primaryCategory.name')->label('Kategori')->badge(),
                TextColumn::make('tags.name')->label('Etiketler')->badge()->wrap()->separator(', '),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft' => 'Taslak', 'scheduled' => 'Planlı', 'published' => 'Yayında',
                        default => (string) $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'draft' => 'warning', 'scheduled' => 'info', 'published' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('published_at')->label('Yayın')->date('d.m.Y')->sortable(),
                TextColumn::make('views')->label('Görünüm')->sortable(),
                TextColumn::make('author.name')->label('Yazar')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Güncellendi')->dateTime('d.m.Y H:i')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->label('Durum')->options([
                    'draft' => 'Taslak', 'scheduled' => 'Planlı', 'published' => 'Yayında',
                ]),
                SelectFilter::make('primary_post_category_id')->label('Kategori')->relationship('primaryCategory', 'name'),
                TernaryFilter::make('published_only')
                    ->label('Sadece Yayında')
                    ->placeholder('Tümü')
                    ->trueLabel('Yayında')->falseLabel('Taslak / Planlı')
                    ->queries(
                        true: fn (EloquentBuilder $q) => $q->published(),
                        false: fn (EloquentBuilder $q) => $q->where(fn ($qq) => $qq->draft()->orWhere(fn ($qq2) => $qq2->scheduled()))
                    ),
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('from')->label('Başlangıç'),
                        DatePicker::make('until')->label('Bitiş'),
                    ])
                    ->query(fn (EloquentBuilder $q, array $data) => $q
                        ->when($data['from'] ?? null, fn ($qq, $from) => $qq->whereDate('published_at', '>=', $from))
                        ->when($data['until'] ?? null, fn ($qq, $until) => $qq->whereDate('published_at', '<=', $until))
                    ),
            ])
            ->defaultSort('published_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
