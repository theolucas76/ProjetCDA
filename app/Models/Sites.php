<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;

class Sites extends Model
{

    private int $site_id;
    private int $site_number_site;
    private \DateTime $site_date_start;
    private \DateTime $site_date_end;
    private \DateTime $created_at;
    private ?\DateTime $updated_at;
    private ?\DateTime $deleted_at;

    public function __construct()
    {
        parent::__construct();
        $this->setId(0);
        $this->setNumberSite(0);
        $this->setDateStart(new \DateTime());
        $this->setDateEnd(new \DateTime());
        $this->setCreated(new \DateTime());
        $this->setUpdated(null);
        $this->setDeleted(null);
    }

    /**
     * @param int $id
     * @return Sites
     */
    public function setId(int $id): Sites {
        $this->site_id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->site_id;
    }

    /**
     * @param int $number
     * @return Sites
     */
    public function setNumberSite(int $number): Sites {
        $this->site_number_site = $number;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberSite(): int {
        return $this->site_number_site;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDateStart(\DateTime $date): Sites {
        $this->site_date_start = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart(): \DateTime {
        return $this->site_date_start;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDateEnd(\DateTime $date): Sites {
        $this->site_date_end = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd(): \DateTime {
        return $this->site_date_end;
    }

    public function setCreated(\DateTime $created_at): Sites
    {
        $this->created_at = $created_at;
        return $this;
    }
    public function getCreated(): \DateTime {
        return $this->created_at;
    }

    public function setUpdated(?\DateTime $updated_at): Sites
    {
        $this->updated_at = $updated_at;
        return $this;
    }
    public function getUpdated(): ?\DateTime {
        return $this->updated_at;
    }

    public function setDeleted(?\DateTime $deleted_at): Sites
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }
    public function getDeleted(): ?\DateTime {
        return $this->deleted_at;
    }

    public function toArray(): array
    {
        return array(
            Keys::DATABASE_SITE_ID => $this->getId(),
            Keys::DATABASE_SITE_NUMBER_SITE => $this->getNumberSite(),
            Keys::DATABASE_SITE_DATE_START => $this->getDateStart()->getTimestamp(),
            Keys::DATABASE_SITE_DATE_END => $this->getDateEnd()->getTimestamp(),
            Keys::DATABASE_CREATED_AT => $this->getCreated()->getTimestamp(),
            Keys::DATABASE_UPDATED_AT => ($this->getUpdated() !== null ? $this->getUpdated()->getTimestamp() : null) ,
            Keys::DATABASE_DELETED_AT => ($this->getDeleted() !== null ? $this->getDeleted()->getTimestamp() : null)
        );
    }

}
