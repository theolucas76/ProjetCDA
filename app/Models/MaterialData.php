<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *     schema="MaterialData",
 *     description="MaterialData's Model"
 * )
 */

class MaterialData extends Model
{
    /**
     * @OA\Property
     * @var int
     */
    private int $data_id;

    /**
     * @OA\Property
     * @var int
     */
    private int $data_material_id;

    /**
     * @OA\Property
     * @var string
     */
    private string $data_key;

    /**
     * @OA\Property
     * @var string
     */
    private string $data_column;

    public function __construct()
    {
        parent::__construct();
        $this->setDataId(0);
        $this->setDataMaterialId(0);
        $this->setDataKey('');
        $this->setDataColumn('');
    }

    public function setDataId(int $id): MaterialData {
        $this->data_id = $id;
        return $this;
    }
    public function getDataId(): int {
        return $this->data_id;
    }

    public function setDataMaterialId(int $ticket_id): MaterialData {
        $this->data_material_id = $ticket_id;
        return $this;
    }
    public function getDataMaterialId(): int {
        return $this->data_material_id;
    }

    public function setDataKey(string $key): MaterialData {
        $this->data_key = $key;
        return $this;
    }
    public function getDataKey(): string {
        return $this->data_key;
    }
    public function setDataColumn(string $column): MaterialData {
        $this->data_column = $column;
        return $this;
    }
    public function getDataColumn(): string {
        return $this->data_column;
    }

    public function toArray(): array
    {
        return array(
            Keys::DATABASE_DATA_ID => $this->getDataId(),
            Keys::DATABASE_MATERIAL_DATA_MATERIAL_ID => $this->getDataMaterialId(),
            Keys::DATABASE_DATA_KEY => $this->getDataKey(),
            Keys::DATABASE_DATA_COLUMN => $this->getDataColumn()
        );
    }

    public function fromDatabase(array $array): void {
        $this->setDataId( $array[Keys::DATABASE_DATA_ID] );
        $this->setDataMaterialId( $array[Keys::DATABASE_MATERIAL_DATA_MATERIAL_ID] );
        $this->setDataKey( $array[Keys::DATABASE_DATA_KEY] );
        $this->setDataColumn( $array[Keys::DATABASE_DATA_COLUMN] );
    }

    public static function getMaterialDataById(int $data_id): ?MaterialData
    {
        $myMaterialData = new MaterialData();
        $myResult = DB::select("SELECT * FROM hc_material_data WHERE data_id = $data_id");
        if (count($myResult) > 0) {
            foreach ($myResult as $item) {
                $myMaterialData->fromDatabase(json_decode(json_encode($item), true));
            }
            return $myMaterialData;
        }
        return null;
    }

    public static function getMaterialDataByMaterial(int $data_material_id): array
    {
        $myMaterialDatas = [];
        $myResult = DB::select("SELECT * FROM hc_material_data WHERE data_material_id = $data_material_id");
        foreach ($myResult as $item) {
            $data = new MaterialData();
            $data->fromDatabase(json_decode(json_encode($item), true));
            $myMaterialDatas[] = $data;
        }
        return $myMaterialDatas;
    }

    public static function addMaterialData(MaterialData $data): bool
    {
        return DB::table('hc_material_data')->insert($data->toArray());
    }

    public static function updateMaterialData(MaterialData $data): bool
    {
        return DB::table('hc_material_data')->where('data_id', $data->getDataId())->update($data->toArray());
    }

    public static function deleteMaterialData(int $id): bool
    {
        return DB::delete("DELETE FROM hc_material_data WHERE data_id = $id");
    }
}
