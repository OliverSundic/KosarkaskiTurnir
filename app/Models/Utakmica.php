<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Utakmica extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tournament_id',
        'domaci_tim_id',
        'strani_tim_id',
        'referee_id',
        'mesto',
        'rezultat',
        'status',
        'user_id',
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
            'tournament_id' => 'integer',
            'domaci_tim_id' => 'integer',
            'strani_tim_id' => 'integer',
            'referee_id' => 'integer',
            'user_id' => 'integer',
        ];
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function domaciTim(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function straniTim(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function referee(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
