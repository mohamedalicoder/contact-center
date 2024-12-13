<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes'
    ];

    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function customFields()
    {
        return $this->belongsToMany(CustomField::class, 'contact_custom_field_values')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function activities()
    {
        return $this->hasMany(ContactActivity::class);
    }

    public function recordActivity($type, $action, $description = null, $metadata = null)
    {
        return $this->activities()->create([
            'user_id' => auth()->id(),
            'type' => $type,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata
        ]);
    }

    public function getCustomFieldValue($fieldName)
    {
        $field = $this->customFields()->whereHas('customFields', function ($query) use ($fieldName) {
            $query->where('name', $fieldName);
        })->first();

        return $field ? $field->pivot->value : null;
    }

    public function setCustomFieldValue($fieldName, $value)
    {
        $field = CustomField::where('name', $fieldName)->first();
        
        if (!$field) {
            return false;
        }

        $this->customFields()->syncWithoutDetaching([
            $field->id => ['value' => $value]
        ]);

        return true;
    }
}
