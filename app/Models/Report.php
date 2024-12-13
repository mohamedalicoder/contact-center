<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'type',
        'start_date',
        'end_date',
        'generated_by',
        'data',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    /**
     * Get the user that generated the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
