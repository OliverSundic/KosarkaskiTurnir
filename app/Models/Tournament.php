<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'naziv',
        'datum_pocetka',
        'datum_zavrsetka',
        'broj_timova',
        'lokacija',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'integer',
            'datum_pocetka' => 'datetime',
            'datum_zavrsetka' => 'datetime',
        ];
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function utakmicas(): HasMany
    {
        return $this->hasMany(Utakmica::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
