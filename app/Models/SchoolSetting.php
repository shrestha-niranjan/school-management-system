<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolSetting extends Model
{
    use HasFactory;

    /**
     * Get the grade associated with the SchoolSetting
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function grade(): HasOne
    {
        return $this->hasOne(Grade::class);
    }
}
