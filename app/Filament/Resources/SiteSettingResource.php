<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Ayarlar';
    protected static ?string $navigationLabel = 'Site Ayarları';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Ayarlar')
                ->tabs([
                    // GENEL
                    Forms\Components\Tabs\Tab::make('Genel')
                        ->schema([Forms\Components\TextInput::make('site_title')->label('Site Başlığı')->maxLength(60)->helperText('Önerilen en fazla 60 karakter.'), Forms\Components\TextInput::make('meta_title')->label('Varsayılan Meta Title')->maxLength(60), Forms\Components\Textarea::make('meta_description')->label('Varsayılan Meta Description')->rows(3)->maxLength(160)])
                        ->columns(2),

                    // İLETİŞİM
                    Forms\Components\Tabs\Tab::make('İletişim')
                        ->schema([
                            Forms\Components\TextInput::make('company_name')->label('Firma Adı')->maxLength(150),
                            Forms\Components\TextInput::make('tax_office')->label('Vergi Dairesi')->maxLength(120),
                            Forms\Components\TextInput::make('tax_number')->label('Vergi No / TCKN')->maxLength(25),
                            Forms\Components\TextInput::make('email')->email()->label('E-posta'),
                            Forms\Components\TextInput::make('phone')
                                ->label('Telefon')
                                ->placeholder('+90 212 909 57 99')
                                ->extraAttributes(['inputmode' => 'tel', 'autocomplete' => 'tel'])
                                ->helperText('Serbest format. Örn: +90 212 909 57 99, 0212 909 57 99'),
                            Forms\Components\TextInput::make('mobile')
                                ->label('Mobil')
                                ->placeholder('+90 530 000 00 00')
                                ->extraAttributes(['inputmode' => 'tel', 'autocomplete' => 'tel'])
                                ->helperText('Serbest format. Örn: +90 530 000 00 00, 0530 000 00 00'),
                            Forms\Components\Textarea::make('address')->label('Adres')->rows(2),
                            Forms\Components\TextInput::make('google_map_url')->label('Google Harita URL'),

                            Forms\Components\Toggle::make('show_whatsapp_fab')->label('WhatsApp butonu göster')->live(),

                            Forms\Components\TextInput::make('whatsapp')
                                ->label('WhatsApp Numarası')
                                ->placeholder('+90 530 000 00 00')
                                ->suffixIcon('heroicon-o-chat-bubble-left-right')
                                ->extraAttributes(['inputmode' => 'tel', 'autocomplete' => 'tel'])
                                ->helperText('Serbest format. Örnekler: +90 530 000 00 00 • 0530 000 00 00 • 905300000000')
                                ->visible(fn(Get $get) => (bool) $get('show_whatsapp_fab'))
                                ->dehydrated(fn(Get $get) => (bool) $get('show_whatsapp_fab'))
                                ->rules(['nullable', 'string', 'max:32']),
                        ])
                        ->columns(2),

                    // SOSYAL
                    Forms\Components\Tabs\Tab::make('Sosyal')
                        ->schema([Forms\Components\TextInput::make('facebook')->label('Facebook'), Forms\Components\TextInput::make('instagram')->label('Instagram'), Forms\Components\TextInput::make('twitter')->label('X / Twitter'), Forms\Components\TextInput::make('linkedin')->label('LinkedIn'), Forms\Components\TextInput::make('youtube')->label('YouTube')])
                        ->columns(2),

                    // GÖRSELLER
                    Forms\Components\Tabs\Tab::make('Görseller')
                        ->schema([
                            Forms\Components\FileUpload::make('logo_path')
                                ->label('Logo')
                                ->image()
                                ->imageEditor()
                                ->imageEditorAspectRatios(['4:1', '3:1', '1:1'])
                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                                ->directory('site')
                                ->disk('public')
                                ->visibility('public'),
                            Forms\Components\FileUpload::make('favicon_path')
                                ->label('Favicon (PNG/ICO)')
                                ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/vnd.microsoft.icon'])
                                ->directory('site')
                                ->disk('public')
                                ->visibility('public'),
                            Forms\Components\FileUpload::make('meta_image_path')
                                ->label('Sosyal Paylaşım Görseli (OG)')
                                ->image()
                                ->imageEditor()
                                ->imageEditorAspectRatios(['1200:630', '16:9'])
                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                                ->directory('site')
                                ->disk('public')
                                ->visibility('public'),
                        ])
                        ->columns(3),

                    // HAKKIMIZDA GALERİSİ
                    Forms\Components\Tabs\Tab::make('Hakkımızda Galerisi')
                        ->schema([
                            Forms\Components\Toggle::make('about_gallery_enabled')->label('Galeriyi etkinleştir')->default(true)->live(),

                            Forms\Components\TextInput::make('about_gallery_max_items')->label('Maks. görsel')->numeric()->default(12)->minValue(1)->maxValue(24)->helperText('Seeder ve panel bu sınırı dikkate alır.'),

                            Forms\Components\TextInput::make('about_gallery_aspect_ratio')->label('Önerilen oran')->placeholder('16:9'),

                            Forms\Components\Textarea::make('about_gallery_note')->label('Not')->rows(2)->placeholder('Örn: 1200×675 px önerilir.'),

                            // Alt metin alanı yok: v3'te per-file custom properties editörü bulunmuyor.
                            // Alt metin blade'de dosya adından türetilir.
                            SpatieMediaLibraryFileUpload::make('about_gallery')
                                ->label('Hakkımızda Galerisi (Slider)')
                                ->collection('about_gallery')
                                ->multiple()
                                ->reorderable()
                                ->image()
                                ->imageEditor()
                                ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxFiles(12)
                                ->panelLayout('grid')
                                ->helperText('Sürükle-bırak ile sırayı değiştirin. Alt metin dosya adından türetilir.')
                                ->visible(fn(Get $get) => (bool) $get('about_gallery_enabled'))
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    // FOOTER
                    Forms\Components\Tabs\Tab::make('Footer')
                        ->schema([Forms\Components\Textarea::make('footer_description')->label('Footer Açıklaması')->rows(4)->maxLength(1000)->columnSpanFull(), Forms\Components\Textarea::make('copyright_text')->label('Copyright / Alt Bilgi Metni')->rows(2)->columnSpanFull()])
                        ->columns(2),

                    // BANKA
                    Forms\Components\Tabs\Tab::make('Banka Bilgileri')
                        ->schema([
                            Forms\Components\Repeater::make('bank_accounts')
                                ->label('Banka Hesapları')
                                ->schema([
                                    Forms\Components\TextInput::make('bank')->label('Banka Adı')->required()->maxLength(120),
                                    Forms\Components\TextInput::make('name')->label('Hesap Adı/Unvan')->maxLength(120),
                                    Forms\Components\TextInput::make('branch')->label('Şube')->maxLength(120),
                                    Forms\Components\TextInput::make('iban')->label('IBAN')->maxLength(34),
                                    Forms\Components\Select::make('currency')
                                        ->label('Para Birimi')
                                        ->options(['TRY' => 'TRY', 'USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP'])
                                        ->default('TRY'),
                                ])
                                ->reorderable()
                                ->collapsible()
                                ->grid(2)
                                ->addActionLabel('Hesap Ekle'),
                        ])
                        ->columns(2),

                    // ANALİTİK / KOD
                    Forms\Components\Tabs\Tab::make('Analitik / Kod')
                        ->schema([Forms\Components\TextInput::make('ga_measurement_id')->label('GA4 Measurement ID (G-XXXX)'), Forms\Components\TextInput::make('gtm_id')->label('GTM ID (GTM-XXXX)'), Forms\Components\TextInput::make('meta_pixel_id')->label('Meta Pixel ID'), Forms\Components\Textarea::make('head_scripts')->label('<head> içine ek kod')->rows(4), Forms\Components\Textarea::make('body_scripts')->label('</body> öncesi ek kod')->rows(4)])
                        ->columns(2),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canDeleteAny(): bool
    {
        return false;
    }
    public static function canDelete($record): bool
    {
        return false;
    }
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSiteSettings::route('/'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }

    public static function getNavigationUrl(): string
    {
        $id = SiteSetting::query()->value('id') ?? 1;
        return static::getUrl('edit', ['record' => $id]);
    }
}
