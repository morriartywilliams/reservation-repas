<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Repas extends Model
{
    use HasFactory;

    protected $table = 'repas';

    protected $fillable = [
        'menu_id',
        'date',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    /**
     * Keep the persisted value date-only on every supported database driver.
     * MySQL normalizes DATE values itself, whereas SQLite retains a datetime
     * string when Eloquent receives a Carbon instance.
     */
    public function setDateAttribute(mixed $value): void
    {
        $this->attributes['date'] = Carbon::parse($value)->toDateString();
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
