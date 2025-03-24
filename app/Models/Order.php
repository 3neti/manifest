<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

/**
 * Class Order.
 *
 * @property int         $id
 * @property User        $user
 * @property Project     $project
 * @property Trip        $trip
 * @property DateTime    $requested_on
 * @property string      $remarks
 * @property string      $signature
 * @property DateTime    $signed_at
 *
 * @method int getKey()
 */
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'ordered_on',
        'remarks',
        'signature',
        'signed_at'
    ];

    protected function casts(): array
    {
        return [
            'ordered_on' => 'datetime',
            'signed_at' => 'datetime',
        ];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function trip(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItems::class);
    }
}
