<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;

class SitesData extends Model
{
    private int $data_id;
    private int $data_site_id;
    private string $data_key;
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
     * @return SitesData
     */
    public function setId(int $id): SitesData {
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
     * @return SitesData
     */
    public function setSiteId(int $siteId): SitesData {
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
     * @return SitesData
     */
    public function setDataKey(string $key): SitesData {
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
     * @return SitesData
     */
    public function setDataColumn(string $column): SitesData {
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
}
