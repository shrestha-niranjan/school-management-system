<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    /**
     * Get all of the markEntries for the Course
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function markEntries(): HasMany
    {
        return $this->hasMany(MarkEntry::class);
    }
}
