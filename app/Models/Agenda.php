<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'agm_id',
        'item_number',
        'title',
        'description',
        'item_type',
        'voting_type',
        'is_active',
    ];

    /**
     * Relationships
     */
    public function agm()
    {
        return $this->belongsTo(Agm::class);
    }
}
