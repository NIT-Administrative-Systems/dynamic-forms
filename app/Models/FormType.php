<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormType extends Model
{
    use HasFactory;

    const APPLICATION = 'application';
    const PRE_SURVEY = 'pre-survey';
    const ENDORSEMENT = 'endorsement';
    const REVIEW = 'review';
    const REPORT_ENDORSEMENT = 'report-endorsement';
    const POST_SURVEY = 'post-survey';
}
