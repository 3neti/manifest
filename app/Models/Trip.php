<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Bavix\Wallet\Interfaces\ProductInterface;
use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Interfaces\Customer;
use Brick\Money\Money;

/**
 * Class Trip.
 *
 * @property int         $id
 * @property string      $code
 * @property string      $name
 * @property int         $amount
 *
 * @method int getKey()
 */
class Trip extends Model implements ProductInterface
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasWalletFloat;
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'amount'
    ];

    public function getAmountProduct(Customer $customer): int|string
    {
        return $this->getRawOriginal('amount');
    }

    public function getMetaProduct(): ?array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
        ];
    }

    protected function Amount(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $currency = 'PHP';

                return Money::ofMinor($value, $currency);
            },
            set: function ($value, $attributes) {
                $currency = 'PHP';

                return $value instanceof Money
                    ? $value->getMinorAmount()->toInt()  // Extract minor units if already Money
                    : Money::of($value, $currency)->getMinorAmount()->toInt(); // Convert before storing
            }
        );
    }
}
