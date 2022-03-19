<?php

namespace App\Models;

use App\Models\Utils\Functions;
use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Material extends Model
{
    private int $material_id;
    private string $material_name;
    private \DateTime $created_at;
    private ?\DateTime $updated_at;
    private ?\DateTime $deleted_at;

    public function __construct()
    {
        parent::__construct();
        $this->setMaterialId(0);
        $this->setMaterialName('');
        $this->setCreated(new \DateTime());
        $this->setUpdated(null);
        $this->setDeleted(null);
    }

    public function setMaterialId(int $id): Material
    {
        $this->material_id = $id;
        return $this;
    }

    public function getMaterialId(): int
    {
        return $this->material_id;
    }

    public function setMaterialName(string $name): Material
    {
        $this->material_name = $name;
        return $this;
    }

    public function getMaterialName(): string
    {
        return $this->material_name;
    }

    public function setCreated(\DateTime $created_at): Material
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreated(): \DateTime
    {
        return $this->created_at;
    }

    public function setUpdated(?\DateTime $updated_at): Material
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setDeleted(?\DateTime $deleted_at): Material
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }

    public function getDeleted(): ?\DateTime
    {
        return $this->deleted_at;
    }

    public function toArray(): array
    {
        return array(
            Keys::DATABASE_MATERIAL_ID => $this->getMaterialId(),
            Keys::DATABASE_MATERIAL_NAME => $this->getMaterialName(),
            Keys::DATABASE_CREATED_AT => $this->getCreated()->getTimestamp(),
            Keys::DATABASE_UPDATED_AT => ($this->getUpdated() !== null ? $this->getUpdated()->getTimestamp() : null),
            Keys::DATABASE_DELETED_AT => ($this->getDeleted() !== null ? $this->getDeleted()->getTimestamp() : null)
        );
    }

    public function fromDatabase(array $array): void
    {
        $this->setMaterialId($array[Keys::DATABASE_MATERIAL_ID]);
        $this->setMaterialName($array[Keys::DATABASE_MATERIAL_NAME]);
        $this->setCreated(Functions::fromUnix($array[Keys::DATABASE_CREATED_AT]));
        $this->setUpdated(($array[Keys::DATABASE_UPDATED_AT] !== null ? Functions::fromUnix($array[Keys::DATABASE_UPDATED_AT]) : null));
        $this->setDeleted(($array[Keys::DATABASE_DELETED_AT] !== null ? Functions::fromUnix($array[Keys::DATABASE_DELETED_AT]) : null));
    }

    public static function getMaterialById(int $id): ?Material
    {
        $myMaterial = new Material();
        $myResult = DB::select("SELECT * FROM hc_material WHERE material_id = $id");
        if (count($myResult) > 0) {
            foreach ($myResult as $item) {
                $myMaterial->fromDatabase(json_decode(json_encode($item), true));
            }
            return $myMaterial;
        }
        return null;
    }

    public static function addMaterial(Material $material): bool
    {
        return DB::table('hc_material')->insert($material->toArray());
    }

    public static function updateMaterial(Material $material): bool
    {
        $material->setUpdated(new \DateTime());
        return DB::table('hc_material')->where('material_id', $material->getMaterialId())->update($material->toArray());
    }

    public static function deleteMaterial(Material $material): bool
    {
        $material->setDeleted(new \DateTime());
        return DB::table('hc_material')->where('material_id', $material->getMaterialId())->update($material->toArray());
    }

}
