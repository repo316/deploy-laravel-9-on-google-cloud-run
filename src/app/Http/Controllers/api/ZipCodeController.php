<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\FederalEntity;
use App\Models\Master;
use App\Models\MasterSettlement;
use App\Models\Municipality;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class ZipCodeController extends Controller{
    public function fetch($zipCode){
        $zipCode=trim(ltrim($zipCode, '0'));
        $result=[
            'code'=>'00',
            'message'=>'404 error'
        ];
        try{
            $master=Master::query()->where('zip_code', '=', $zipCode)->firstOrFail([
                'id',
                'zip_code',
                'locality',
                'fk_id_federal_entity',
                'fk_id_municipalities',
            ]);
            if($master){
                $result['zip_code']=Str::padLeft($master->zip_code, 5,'0');
                $result['locality']=Str::upper($master->locality);

                $federalEntity=FederalEntity::query()->where('id', '=', $master->fk_id_federal_entity)->first([
                    'key_data as key',
                    'name',
                    'code',
                ]);

                $result['federal_entity']=[];
                if($federalEntity){
                    $result['federal_entity']=[
                        "key"=>$federalEntity->key,
                        "name"=>Str::upper($federalEntity->name),
                        "code"=>!empty($federalEntity->code)?$federalEntity->code:null
                    ];
                }

                $settlements=MasterSettlement::query()->join('masters as m', 'fk_id_master', '=', 'm.id')->where('fk_id_master', '=', $master->id)->join('settlements as s', 'fk_id_settlement', '=', 's.id')->join('settlement_types as st', 's.fk_id_settlement_type', '=', 'st.id')->get([
                    's.key_data as key',
                    's.name',
                    's.zone_type',
                    'st.name as settlement_type'
                ]);

                $result['settlements']=[];
                if($settlements){
                    foreach($settlements as $settlement){
                        $settlement->zone_type=Str::upper($settlement->zone_type);
                        $name=$settlement->settlement_type;
                        $settlement->name=Str::upper($settlement->name);
                        $settlement->settlement_type=new \stdClass();
                        $settlement->settlement_type->name=$name;
                    }
                    $result['settlements']=$settlements;
                }

                $municipality=Municipality::query()->where('id', '=', $master->fk_id_municipalities)->first([
                    'key_data as key',
                    'name',
                ]);

                $result['municipality']=[];
                if($federalEntity){
                    $result['municipality']=[
                        "key"=>$municipality->key,
                        "name"=>Str::upper($municipality->name)
                    ];
                }

            }
            $status=200;
            unset($result['code']);
            unset($result['message']);
        }catch(\Illuminate\Database\QueryException $e){
            $status=401;
        }catch(ModelNotFoundException $e){
            $status=404;
        }

        return response()->json($result,$status);
    }
}
