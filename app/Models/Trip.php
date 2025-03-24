<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Bavix\Wallet\Interfaces\ProductInterface;
use Illuminate\Database\Eloquent\Model;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Interfaces\Customer;

/**
 * Class Trip.
 *
 * @property int         $id
 * @property string      $code
 * @property string      $name
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
        'name'
    ];

    public function getAmountProduct(Customer $customer): int|string
    {
        return 1000 * 100;
    }

    public function getMetaProduct(): ?array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
        ];
    }
}
