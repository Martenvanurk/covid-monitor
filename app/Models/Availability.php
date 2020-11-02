<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Availability extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'availability';

    public function getAllAvailableUsersByDate(string $date): Collection
    {
        return $this->where('date_availability', $date)->get();
    }

    public function user(): HasOne
    {
        return $this->hasOne('App\Models\User', 'id');
    }
}
