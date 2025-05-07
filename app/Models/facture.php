<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class facture extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'matere_id',
        'etudiant_id',
        'date_paye',
        'prix',
        'mois_facture'
    ];
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function matere()
    {
        return $this->BelongsTo(matere::class);
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }
}
