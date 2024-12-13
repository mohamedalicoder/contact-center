<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'user_id',
        'type',
        'action',
        'description',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
