<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class matere extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prix'
    ];
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function sections()
    {
        return $this->hasMany(section::class);
    }

    public function factures()
    {
        return $this->hasMany(facture::class);
    }
}
