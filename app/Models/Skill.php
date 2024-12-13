<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function agents()
    {
        return $this->belongsToMany(User::class, 'agent_skills')
            ->withPivot('proficiency_level')
            ->withTimestamps();
    }

    public function queues()
    {
        return $this->belongsToMany(Queue::class, 'queue_skills')
            ->withPivot('minimum_proficiency')
            ->withTimestamps();
    }

    public function getQualifiedAgents()
    {
        return $this->agents()
            ->wherePivot('proficiency_level', '>=', $this->pivot->minimum_proficiency ?? 1)
            ->get();
    }
}
