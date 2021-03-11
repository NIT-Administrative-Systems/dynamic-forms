<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ProgramCycle extends Model
{
    use HasFactory;

    protected $appends = ['is_open'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Attribute form always uses the current time.
     */
    public function getIsOpenAttribute(): bool
    {
        return $this->isOpen(Carbon::now());
    }

    /**
     * Useful if you need to check against a past date.
     */
    public function isOpen(DateTimeInterface $now = null)
    {
        if (! $now) {
            $now = Carbon::now();
        }

        return $now >= $this->ends_at;
    }
}
