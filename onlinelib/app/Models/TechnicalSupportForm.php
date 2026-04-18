<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Attēlo lietotāja jautāuma informāciju
class TechnicalSupportForm extends Model
{
    use HasFactory;

    // Datubāzes tabulas nosaukums
    protected $table = 'technical_support_form';

    // Masveidā aizpildāmie lauki
    protected $fillable = [
        'email',
        'nickname',
        'subject',
        'problem',
    ];

}
