<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'json'
    ];

    /**
     * Get setting by key
     *
     * @param string $key
     * @return mixed
     */
    public static function getByKey($key)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : null;
    }

    /**
     * Get settings by group
     *
     * @param string $group
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get();
    }
}
