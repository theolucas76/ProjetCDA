<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;

class MaterialData extends Model
{
    private int $data_id;
    private int $data_material_id;
    private string $data_key;
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

}
