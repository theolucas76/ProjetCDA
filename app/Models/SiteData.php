<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *     schema="SiteData",
 *     description="SiteData Model"
 * )
 */

class SiteData extends Model
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
    private int $data_site_id;

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
        $this->setId(0);
        $this->setSiteId(0);
        $this->setDataKey('');
        $this->setDataColumn('');
    }

    /**
     * @param int $id
     * @return SiteData
     */
    public function setId(int $id): SiteData {
        $this->data_id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->data_id;
    }

    /**
     * @param int $siteId
     * @return SiteData
     */
    public function setSiteId(int $siteId): SiteData {
        $this->data_site_id = $siteId;
        return $this;
    }

    /**
     * @return int
     */
    public function getSiteId(): int {
        return $this->data_site_id;
    }

    /**
     * @param string $key
     * @return SiteData
     */
    public function setDataKey(string $key): SiteData {
        $this->data_key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataKey(): string {
        return $this->data_key;
    }

    /**
     * @param string $column
     * @return SiteData
     */
    public function setDataColumn(string $column): SiteData {
        $this->data_column = $column;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataColumn(): string {
        return $this->data_column;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            Keys::DATABASE_DATA_ID => $this->getId(),
            Keys::DATABASE_SITE_DATA_SITE_ID => $this->getSiteId(),
            Keys::DATABASE_DATA_KEY => $this->getDataKey(),
            Keys::DATABASE_DATA_COLUMN => $this->getDataColumn()
        );
    }

    public function fromDatabase(array $array): void {
        $this->setId( $array[Keys::DATABASE_DATA_ID] );
        $this->setSiteId( $array[Keys::DATABASE_SITE_DATA_SITE_ID] );
        $this->setDataKey( $array[Keys::DATABASE_DATA_KEY] );
        $this->setDataColumn( $array[Keys::DATABASE_DATA_COLUMN] );
    }

    public static function getSiteDataById(int $id): ?SiteData {
        $mySiteData = new SiteData();
        $myResult = DB::select("SELECT * FROM hc_site_data WHERE data_id = $id");
        if (count($myResult) > 0) {
            foreach ($myResult as $item) {
                $mySiteData->fromDatabase(json_decode(json_encode($item), true));
            }
            return $mySiteData;
        }
        return null;
    }

    public static function getSiteDataBySite(int $site_id): array {
        $mySiteDatas = [];
        $myResult = DB::select("SELECT * FROM hc_site_data WHERE data_site_id = $site_id");
        foreach ($myResult as $item) {
            $data = new SiteData();
            $data->fromDatabase(json_decode(json_encode($item), true));
            $mySiteDatas[] = $data;
        }
        return $mySiteDatas;
    }

    public static function addSiteData(SiteData $data): bool {
        $id = DB::table('hc_site_data')->insertGetId($data->toArray());
        $data->setId($id);
        return $id !== 0;
    }

    public static function updateSiteData(SiteData $data): bool {
        return DB::table('hc_site_data')->where('data_id', $data->getId())->update($data->toArray());
    }

    public static function deleteSiteData(int $id): bool {
        return DB::delete("DELETE FROM hc_site_data WHERE data_id = $id");
    }

}
