<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name','email','profile','phone','company_id'];


    public function getProfilePath(){
        return '/storage/' . ($this->profile);
    }

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
