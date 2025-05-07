<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etudiant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'pourcentage'
    ];
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function sections()
    {
        return $this->belongsToMany(section::class, 'section_etudiants')->withPivot('date_inscription', 'date_paye');
    }
    public function factures()
    {
        return $this->hasMany(facture::class);
    }
}
