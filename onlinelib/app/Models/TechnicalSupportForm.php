<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalSupportForm extends Model
{
    use HasFactory;

    protected $table = 'technical_support_form';

    protected $fillable = [
        'email',
        'nickname',
        'subject',
        'problem',
    ];

}
