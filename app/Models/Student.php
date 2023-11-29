<?php

namespace App\Models;

use App\Models\Grade;
use App\Models\MarkEntry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    /**
     * Get all of the markEntries for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function markEntries(): HasMany
    {
        return $this->hasMany(MarkEntry::class);
    }

    /**
     * Get the grade that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }
}
