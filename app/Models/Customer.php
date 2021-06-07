<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * @package App\Models
 * @property string $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $name
 * @property string $address
 * @property string $checked
 * @property string $description
 * @property string $interest
 * @property string $date_of_birth
 * @property string $email
 * @property string $account
 */
class Customer extends Model
{
    use HasFactory;
    protected $fillable = ["name" ,"address", "checked", "description", "interest", "date_of_birth", "email", "account"];


    public function cards(){
        return $this->hasMany(Card::class);
    }
}
