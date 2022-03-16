<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;

class SitesData extends Model
{
    private int $siteData_id;
    private int $siteData_siteId;
    private string $siteData_key;
    private string $siteData_column;

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
     * @return SitesData
     */
    public function setId(int $id): SitesData {
        $this->siteData_id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->siteData_id;
    }

    /**
     * @param int $siteId
     * @return SitesData
     */
    public function setSiteId(int $siteId): SitesData {
        $this->siteData_siteId = $siteId;
        return $this;
    }

    /**
     * @return int
     */
    public function getSiteId(): int {
        return $this->siteData_siteId;
    }

    /**
     * @param string $key
     * @return SitesData
     */
    public function setDataKey(string $key): SitesData {
        $this->siteData_key = $key;
        return $this;
    }

    /**
     * @return int
     */
    public function getDataKey(): int {
        return $this->siteData_key;
    }

    /**
     * @param string $column
     * @return SitesData
     */
    public function setDataColumn(string $column): SitesData {
        $this->siteData_column = $column;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataColumn(): string {
        return $this->siteData_column;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            Keys::DATABASE_SITE_DATA_ID => $this->getId(),
            Keys::DATABASE_SITE_DATA_SITE_ID => $this->getSiteId(),
            Keys::DATABASE_SITE_DATA_KEY => $this->getDataKey(),
            Keys::DATABASE_SITE_DATA_COLUMN => $this->getDataColumn()
        );
    }
}
