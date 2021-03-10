<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function cycles()
    {
        return $this->hasMany(ProgramCycle::class);
    }

    public function forms()
    {
        return $this->hasMany(Form::class);
    }
}
