<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    protected static ?string $heading = 'Takvim';

    // Şimdilik tamamen kapalı
    public static function canView(): bool
    {
        return false;
    }

    // Uyarı çıkmaması için boş dön
    public function fetchEvents(array $info = []): array
    {
        return [];
    }
}
