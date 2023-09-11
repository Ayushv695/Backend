<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\City;
class State extends Model
{
    use HasFactory;
    protected $table = "states";
    protected $primaryKey = "stateID";

    protected $fillable = [
        'stateName',
    ];

    public function city():HasMany
    {
        return $this->hasMany(City::class,'stateID');
    }
}
