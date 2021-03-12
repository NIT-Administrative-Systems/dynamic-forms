<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationSubmission extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function form_version()
    {
        return $this->belongsTo(FormVersion::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
