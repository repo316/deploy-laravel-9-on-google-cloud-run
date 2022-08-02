<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSettlement extends Model{
    use HasFactory;

    protected $fillable=[
        'fk_id_master',
        'fk_id_settlement',
        'status',
    ];

    public function settlement(){
        return $this->hasMany(Settlement::class, 'id', 'fk_id_settlement');
    }

    public function master(){
        return $this->hasMany(Master::class, 'id', 'fk_id_master');
    }
}
