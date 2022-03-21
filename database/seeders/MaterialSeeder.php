<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myMaterial = new Material();
        $myMaterial->setMaterialName('Tournevis');
        DB::table('hc_material')->insert($myMaterial->toArray());

        $myMaterialData = $this->generateMaterialData();

        foreach ($myMaterialData as $data) {
            DB::table('hc_material_data')->insert($data->toArray());
        }

    }

    public function generateMaterialData(): array {

        $myNumber = new MaterialData();
        $myNumber->setDataMaterialId(1);
        $myNumber->setDataKey('number');
        $myNumber->setDataColumn('300');

        $myCategory = new MaterialData();
        $myCategory->setDataMaterialId(1);
        $myCategory->setDataKey('category');
        $myCategory->setDataColumn('1');

        return array($myNumber, $myCategory);

    }
}
