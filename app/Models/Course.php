<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $appends = ['full_mark'];

    /**
     * Get all of the markEntries for the Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function markEntries(): HasMany
    {
        return $this->hasMany(MarkEntry::class);
    }

    /**
     * Get the grade that owns the Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function getFullMarkAttribute()
    {
        return $this->internal_mark + $this->external_mark;
    }
}
