<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'agm_id',
        'agenda_uuid',
        'item_number',
        'title',
        'description',
        'item_type',
        'voting_type',
        'is_active',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->agenda_uuid)) {
                $model->agenda_uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationships
     */
    public function agm()
    {
        return $this->belongsTo(Agm::class);
    }
}