<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['content', 'status', 'author_name', 'author_email', 'user_id', 'customer_id', 'parent_id', 'ip', 'user_agent'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /* Relations */
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /* Scopes */
    public function scopeApproved($q)
    {
        return $q->where('status', 'approved');
    }

    /* Defaults */
    protected static function booted(): void
    {
        static::creating(function (Comment $c) {
            if (blank($c->status)) {
                $c->status = 'pending'; // moderasyon varsayılanı
            }
            $c->ip = request()->ip();
            $c->user_agent = substr((string) request()->userAgent(), 0, 255);
            // Misafir e-postayı normalize etmek istersen:
            if ($c->author_email) {
                $c->author_email = mb_strtolower($c->author_email);
            }
        });
    }
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }
}
