<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'name',
        'phone',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function shareholders()
    {
        return $this->hasMany(Shareholder::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, Shareholder::class)
            ->withPivot(['shares_owned', 'share_certificate_number', 'acquired_date', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Get shares for a specific company
     */
    public function sharesForCompany(int $companyId): ?Shareholder
    {
        return $this->shareholders()->where('company_id', $companyId)->first();
    }
}
