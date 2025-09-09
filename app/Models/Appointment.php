<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Service;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_id',
        'user_id',
        'start_at',
        'end_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service(): BelongsTo
    {
        // ilişki metodu nullable olmaz; nullable olan foreign key’dir.
        // service_id NULL ise ilişki zaten boş döner. İstersen withDefault() ekleyebilirsin.
        return $this->belongsTo(Service::class)->withDefault();
    }
}
