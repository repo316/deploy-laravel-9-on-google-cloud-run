<?php

namespace App\Console\Commands;

use App\Models\FederalEntity;
use App\Models\Master;
use App\Models\MasterSettlement;
use App\Models\Municipality;
use App\Models\Settlement;
use App\Models\SettlementType;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ReadXlsx extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature='xlsx:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description='Command to read excel files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $collectionExcel=Excel::toCollection(collect([]), 'public/CPdescarga.xls');

        foreach($collectionExcel as $i=>$collects){
            if($i==0) continue;

            foreach($collects as $j=>$collect){
                if($j==0) continue;
                $federalEntityName = CleanValues($collect[4]);
                $federalEntity=FederalEntity::query()->where(function($query) use ($collect,$federalEntityName){
                    $query->where('key_data', '=', CleanValues($collect[7]));
                    $query->where('name', '=', $federalEntityName);
                    $query->where('code', '=', CleanValues($collect[9] ?? ''));
                })->first();

                if(!$federalEntity){
                    $idFederalEntity=FederalEntity::create([
                        'key_data'=>CleanValues($collect[7]),
                        'name'=>$federalEntityName,
                        'code'=>CleanValues($collect[9] ?? ''),
                    ])->id;
                }
                else{
                    $idFederalEntity=$federalEntity->id;
                }

                $municipality=Municipality::query()->where(function($query) use ($collect){
                    $query->where('key_data', '=', CleanValues($collect[11]));
                    $query->where('name', '=', CleanValues($collect[3]));
                    $query->where('status', '=', 'Active');
                })->first();

                if(!$municipality){
                    $idMunicipality=Municipality::create([
                        'key_data'=>CleanValues($collect[11]),
                        'name'=>CleanValues($collect[3]),
                        'status'=>'Active',
                    ])->id;
                }
                else{
                    $idMunicipality=$municipality->id;
                }

                $settlementType=SettlementType::query()->where(function($query) use ($collect){
                    $query->where('name', '=', CleanValues($collect[2]));
                    $query->where('status', '=', 'Active');
                })->first();

                if(!$settlementType){
                    $idSettlementType=SettlementType::create([
                        'name'=>CleanValues($collect[2]),
                        'status'=>'Active',
                    ])->id;
                }
                else{
                    $idSettlementType=$settlementType->id;
                }

                $settlements=Settlement::query()->where(function($query) use ($collect, $idSettlementType){
                    $query->where('key_data', '=', CleanValues($collect[12]));
                    $query->where('name', '=', CleanValues($collect[1]));
                    $query->where('zone_type', '=', CleanValues($collect[13]));
                    $query->where('fk_id_settlement_type', '=', $idSettlementType);
                })->first();

                if(!$settlements){
                    $idSettlements=Settlement::create([
                        'key_data'=>CleanValues($collect[12]),
                        'name'=>CleanValues($collect[1]),
                        'zone_type'=>CleanValues($collect[13]),
                        'fk_id_settlement_type'=>$idSettlementType,
                    ])->id;
                }
                else{
                    $idSettlements=$settlements->id;
                }

                $localityName = CleanValues($collect[5]);
                $master=Master::query()->where(function($query) use ($collect, $idFederalEntity, $idMunicipality,$localityName){
                    $query->where('zip_code', '=', CleanValues($collect[0]));
                    $query->where('locality', '=', $localityName);
                    $query->where('fk_id_federal_entity', '=', $idFederalEntity);
                    $query->where('fk_id_municipalities', '=', $idMunicipality);
                })->first();

                if(!$master){
                    $idMaster=Master::create([
                        'zip_code'=>$collect[0],
                        'locality'=>$localityName,
                        'fk_id_federal_entity'=>$idFederalEntity,
                        'fk_id_municipalities'=>$idMunicipality,
                    ])->id;
                }
                else{
                    $idMaster=$master->id;
                }

                $masterSettlement=MasterSettlement::query()->where(function($query) use ($idMaster, $idSettlements){
                    $query->where('fk_id_master', '=', $idMaster);
                    $query->where('fk_id_settlement', '=', $idSettlements);
                    $query->where('status', '=', 'Active');
                })->first();

                if(!$masterSettlement){
                    MasterSettlement::create([
                        'fk_id_master'=>$idMaster,
                        'fk_id_settlement'=>$idSettlements,
                        'status'=>'Active',
                    ]);
                }
            }

        }


        return 0;
    }

}
