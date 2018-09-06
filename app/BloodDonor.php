<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodDonor extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'blood_type',
        'available',
    ];
}
