<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'options', 'is_required'];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean'
    ];

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_custom_field_values')
            ->withPivot('value')
            ->withTimestamps();
    }
}
