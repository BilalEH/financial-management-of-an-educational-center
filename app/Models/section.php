<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class section extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'matere_id',
        'niveau_id',
        'professor_id',
    ];
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function professor()
    {
        return $this->belongsTo(professor::class);
    }

    public function matere()
    {
        return $this->belongsTo(matere::class);
    }
    public function niveau()
    {
        return $this->belongsTo(niveau::class);
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, "section_etudiants")->withPivot('date_inscription', 'date_paye');
    }
}
