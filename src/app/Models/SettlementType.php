<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementType extends Model{
    use HasFactory;

    protected $fillable=[
        'name',
        'status',
    ];

    public function settlement(){
        return $this->belongsTo(Settlement::class);
    }
}
