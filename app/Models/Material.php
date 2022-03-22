<?php

namespace App\Models;

use App\Models\Utils\Functions;
use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *     schema="Material",
 *     description="Material's Model"
 * )
 */

class Material extends Model
{
    /**
     * @OA\Property
     * @var int
     */
    private int $material_id;

    /**
     * @OA\Property
     * @var string
     */
    private string $material_name;

    /**
     * @OA\Property
     * @var \DateTime
     */
    private \DateTime $created_at;

    /**
     * @OA\Property
     * @var \DateTime|null
     */
    private ?\DateTime $updated_at;

    /**
     * @OA\Property
     * @var \DateTime|null
     */
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

    /**
     * @OA\Schema(
     *     schema="MaterialWithData",
     *     description="Material Model with data",
     *     allOf={@OA\Schema(ref="#/components/schemas/Material")},
     *     @OA\Property(
     *          property="data",
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/MaterialData"),
     *          minItems=2
     *     )
     * )
     */

    /**
     * @param int $id
     * @return $this
     */
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

    public static function getAllMaterials(): array
    {
        $myMaterials = [];
        $myResult = DB::select("SELECT * FROM hc_material");
        foreach ($myResult as $item) {
            $material = new Material();
            $material->fromDatabase(json_decode(json_encode($item), true));
            $myMaterials[] = $material;
        }
        return $myMaterials;
    }

    public static function getMaterialByCategory(string $category): array
    {
        $myMaterials = [];
        $myResult = DB::select("SELECT m.* FROM hc_material m INNER JOIN hc_material_data d ON m.material_id = d.data_material_id
                                    WHERE m.material_id = d.data_material_id AND d.data_key = 'category' AND d.data_column = $category");
        foreach ($myResult as $item) {
            $material = new Material();
            $material->fromDatabase(json_decode(json_encode($item), true));
            $myMaterials[] = $material;
        }
        return $myMaterials;
    }


    /**
     * @OA\Schema(
     *     schema="PostMaterialRequest",
     *     required={"material_name"},
     *     @OA\Property(
     *          property="material_name",
     *          type="string",
     *          default="Marteau-piqueur",
     *          description="Name of the material"
     *     )
     * )
     *
     * @param Material $material
     * @return bool
     */
    public static function addMaterial(Material $material): bool
    {
        $id = DB::table('hc_material')->insertGetId($material->toArray());
        $material->setMaterialId($id);
        return $id !== 0;
    }

    /**
     * * @OA\Schema(
     *     schema="UpdateMaterialRequest",
     *     required={"material_id", "material_name"},
     *     @OA\Property(
     *          property="material_id",
     *          type="integer",
     *          default=2,
     *          description="Material Id"
     *     ),
     *     @OA\Property(
     *          property="material_name",
     *          type="string",
     *          default="Marteau",
     *          description="Name of the material"
     *     ),
     * )
     *
     * @param Material $material
     * @return bool
     */
    public static function updateMaterial(Material $material): bool
    {
        $material->setUpdated(new \DateTime());
        return DB::table('hc_material')->where('material_id', $material->getMaterialId())->update($material->toArray());
    }

    public static function deleteMaterial(Material $material): bool
    {
        $material->setDeleted(new \DateTime());
        return DB::table('hc_material')->where('material_id', $material->getMaterialId())
            ->where('deleted_at', null)->update($material->toArray());
    }

}
