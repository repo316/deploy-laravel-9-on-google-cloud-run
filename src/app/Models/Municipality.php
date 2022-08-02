<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model{
    use HasFactory;

    protected $fillable=[
        'key_data',
        'name',
        'status',
    ];

    public function master(){
        return $this->belongsTo(Master::class);
    }
}
