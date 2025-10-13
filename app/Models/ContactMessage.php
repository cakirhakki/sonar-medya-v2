<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','email','message','phone','company',
        'page_url','ip','user_agent','consent_at','status',
    ];

    protected $casts = [
        'consent_at' => 'datetime',
    ];
}
