<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model{
    use HasFactory;

    protected $fillable=[
        'zip_code',
        'locality',
        'fk_id_federal_entity',
        'fk_id_municipalities',
    ];


    public function federalEntity(){
        return $this->hasOne(FederalEntity::class,'id');
    }

    public function municipality(){
        return $this->hasOne(Municipality::class);
    }
}
