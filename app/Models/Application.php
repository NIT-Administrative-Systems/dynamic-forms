<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_user_id');
    }

    public function cycle()
    {
        return $this->belongsTo(ProgramCycle::class, 'program_cycle_id');
    }

    public function submissions()
    {
        return $this->hasMany(ApplicationSubmission::class);
    }
}
