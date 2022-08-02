<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FederalEntity extends Model{
    use HasFactory;

    protected $fillable=[
        'key_data',
        'name',
        'code',
        'status',
    ];


    public function master(){
        return $this->belongsTo(Master::class,'fk_id_federal_entity');
    }
}
