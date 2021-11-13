<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bierstand extends Model
{
    use HasFactory;

    protected $table = 'bierstand';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'Heer',
        'Bier',
        'TotaalOnzichtbaar',
    ];
}
