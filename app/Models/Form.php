<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use HasFactory, SoftDeletes;

    public function type()
    {
        return $this->belongsTo(FormType::class, 'form_type_id');
    }

    public function versions()
    {
        return $this->hasMany(FormVersion::class)
            ->orderBy('updated_at', 'DESC');
    }

    public function published_version()
    {
        return $this->hasOne(FormVersion::class)
            ->orderBy('published_at', 'DESC')
            ->limit(1);
    }
}
