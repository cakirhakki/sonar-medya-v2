<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostCategoryResource\Pages;
use App\Models\PostCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostCategoryResource extends Resource
{
    protected static ?string $model = PostCategory::class;

    protected static ?string $navigationIcon  = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'İçerik';
    protected static ?int    $navigationSort  = 10;

    protected static ?string $modelLabel       = 'Makale Kategori';
    protected static ?string $pluralModelLabel = 'Makale Kategorileri';
    protected static ?string $navigationLabel  = 'Makale Kategorileri';
    protected static ?string $permissionsGroup = 'Blog • Makale Kategorileri';

    public static function form(Form $form): Form
    {
        $table = (new PostCategory)->getTable();

        return $form->schema([
            Forms\Components\Grid::make(12)->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Ad')
                    ->placeholder('Örn: Teknoloji')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                    ->columnSpan(6),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->placeholder('örn-teknoloji')
                    ->required()
                    ->unique(table: $table, column: 'slug', ignoreRecord: true)
                    ->helperText('Boş bırakırsanız üstteki “Ad” alanından otomatik üretilir.')
                    ->dehydrateStateUsing(fn ($state) => Str::slug((string) $state))
                    ->columnSpan(6),

                Forms\Components\Select::make('parent_id')
                    ->label('Üst Kategori')
                    ->placeholder('Yok')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->columnSpan(6),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
                    ->columnSpan(6),

                Forms\Components\FileUpload::make('image_path')
                    ->label('Görsel')
                    ->disk('public')
                    ->directory('post-categories')
                    ->image()
                    ->imageEditor() // kırpma düzenleyici
                    ->helperText('Önerilen: 1600×900 px, WebP/JPG, ≤ 300 KB. Kare alanlarda ortadan kırpılır.')
                    ->hint('16:9 kapak görseli')
                    ->columnSpan(6),

                Forms\Components\MarkdownEditor::make('description')
                    ->label('Açıklama')
                    ->placeholder("Bu kategori; teknoloji haberleri, cihaz incelemeleri ve ipuçlarını kapsar…")
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('post-categories/attachments')
                    ->fileAttachmentsVisibility('public')
                    ->columnSpan(12),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('')
                    ->disk('public')
                    ->square()
                    ->size(36),

                Tables\Columns\TextColumn::make('name')
                    ->label('Ad')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Üst')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncellendi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Aktif'),
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Üst Kategori')
                    ->relationship('parent', 'name'),
            ])
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
        return ['name', 'slug'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPostCategories::route('/'),
            'create' => Pages\CreatePostCategory::route('/create'),
            'edit'   => Pages\EditPostCategory::route('/{record}/edit'),
        ];
    }
}
