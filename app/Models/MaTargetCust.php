<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static take(int $int)
 * @method static where(string $string, string $username)
 * @method static select(array $array)
 */
class MaTargetCust extends Model
{
    use HasFactory;
    protected $connection = 'db_kyc';

    protected $table = 'ma_target_cust';


    public function getCustName(): BelongsTo
    {
        return $this->belongsTo(MaCustomer::class, 'custid', 'custid');
    }
}
