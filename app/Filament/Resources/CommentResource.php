<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationGroup = 'İçerik';
    protected static ?int $navigationSort = 40;
    protected static ?string $modelLabel = 'Yorum';
    protected static ?string $pluralModelLabel = 'Yorumlar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Hangi içeriğe (Post) ait?
                // MorphToSelect v3 mevcut; yoksa aşağıdaki "yoksa" kısmını kullan.
                Forms\Components\MorphToSelect::make('commentable')
                    ->label('İçerik')
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(Post::class)
                            ->titleAttribute('title'),
                    ])
                    ->required(),

                Forms\Components\Textarea::make('content')
                    ->label('Yorum')
                    ->rows(6)
                    ->maxLength(3000)
                    ->required(),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending'  => 'Beklemede',
                                'approved' => 'Onaylı',
                                'spam'     => 'Spam',
                            ])
                            ->native(false)
                            ->required()
                            ->default('pending'),

                        Forms\Components\TextInput::make('author_name')
                            ->label('Misafir Adı')
                            ->maxLength(120)
                            ->placeholder('Giriş yapmamış kullanıcı adı'),

                        Forms\Components\TextInput::make('author_email')
                            ->label('Misafir E-posta')
                            ->email()
                            ->maxLength(150),

                        Forms\Components\Select::make('user_id')
                            ->label('Kullanıcı')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false),
                    ])
                    ->columns(2),

                Forms\Components\TextInput::make('parent_id')
                    ->label('Üst Yorum ID')
                    ->numeric()
                    ->helperText('Yanıt ise doldurulur. (Genelde boş bırakın)'),

                Forms\Components\TextInput::make('ip')
                    ->label('IP')
                    ->disabled(),

                Forms\Components\TextInput::make('user_agent')
                    ->label('User Agent')
                    ->disabled(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('commentable.title')
                    ->label('İçerik')
                    ->url(fn ($record) => $record->commentable instanceof Post
                        ? route('posts.show', $record->commentable)
                        : null
                    )
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('author_name')
                    ->label('Yazan')
                    ->formatStateUsing(fn ($state, Comment $record) =>
                        $record->user?->name ?: ($state ?: 'Misafir')
                    )
                    ->searchable(),

                Tables\Columns\TextColumn::make('content')
                    ->label('Yorum')
                    ->limit(60)
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'spam',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->since(), // “2 saat önce” gibi
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending'  => 'Beklemede',
                        'approved' => 'Onaylı',
                        'spam'     => 'Spam',
                    ]),

                Tables\Filters\Filter::make('onlyRoot')
                    ->label('Sadece kök yorumlar')
                    ->query(fn (Builder $q) => $q->whereNull('parent_id')),

                Tables\Filters\TrashedFilter::make(), // Soft delete için
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Onayla')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Comment $record) => $record->status !== 'approved')
                    ->action(fn (Comment $record) => $record->update(['status' => 'approved'])),

                Tables\Actions\Action::make('markSpam')
                    ->label('Spam')
                    ->icon('heroicon-o-shield-exclamation')
                    ->color('danger')
                    ->visible(fn (Comment $record) => $record->status !== 'spam')
                    ->action(fn (Comment $record) => $record->update(['status' => 'spam'])),

                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('bulkApprove')
                    ->label('Seçiliyi Onayla')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->action(fn ($records) => $records->each->update(['status' => 'approved'])),

                Tables\Actions\BulkAction::make('bulkSpam')
                    ->label('Seçiliyi Spam Yap')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->action(fn ($records) => $records->each->update(['status' => 'spam'])),

                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // İstersen PostResource içinde ilişki yöneticisi de ekleyeceğiz (aşağıda).
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit'   => Pages\EditComment::route('/{record}/edit'),
            // 'view'   => Pages\ViewComment::route('/{record}'),
        ];
    }

    // Global arama (Filament üst search)
    public static function getGloballySearchableAttributes(): array
    {
        return ['content', 'author_name'];
    }

    public static function getEloquentQuery(): Builder
    {
        // Soft deletes’i tabloda TrashedFilter ile yönetebilmek için:
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    
}
