<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Card
 * @package App\Models
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $customer_id
 * @property string $type
 * @property string $number
 * @property string $name
 * @property string $exp_date
 */
class Card extends Model
{
    use HasFactory;
    protected $fillable = [ 'customer_id', "type", "number", "name", "exp_date" ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
