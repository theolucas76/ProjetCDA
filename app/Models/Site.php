<?php

namespace App\Models;

use App\Models\Utils\Functions;
use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema (
 *     schema="Site",
 *     description="Site Model",
 * )
 */

class Site extends Model
{
    /**
     * @OA\Property
     * @var int
     */
    private int $site_id;

    /**
     * @OA\Property
     * @var int
     */
    private int $site_number_site;

    /**
     * @OA\Property
     * @var \DateTime
     */
    private \DateTime $site_date_start;

    /**
     * @OA\Property
     * @var \DateTime
     */
    private \DateTime $site_date_end;

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
        $this->setId(0);
        $this->setNumberSite(0);
        $this->setDateStart(new \DateTime());
        $this->setDateEnd(new \DateTime());
        $this->setCreated(new \DateTime());
        $this->setUpdated(null);
        $this->setDeleted(null);
    }

    /**
     * @OA\Schema(
     *     schema="SiteWithData",
     *     description="Site Model with data",
     *     allOf={@OA\Schema(ref="#/components/schemas/Site")},
     *      @OA\Property(
     *          property="data",
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/SiteData"),
     *          minItems=2
     *     )
     * )
     */

    /**
     * @param int $id
     * @return Site
     */
    public function setId(int $id): Site
    {
        $this->site_id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->site_id;
    }


    public function setNumberSite(int $number): Site
    {
        $this->site_number_site = $number;
        return $this;
    }

    public function getNumberSite(): int
    {
        return $this->site_number_site;
    }

    public function setDateStart(\DateTime $date): Site
    {
        $this->site_date_start = $date;
        return $this;
    }

    public function getDateStart(): \DateTime
    {
        return $this->site_date_start;
    }

    public function setDateEnd(\DateTime $date): Site
    {
        $this->site_date_end = $date;
        return $this;
    }

    public function getDateEnd(): \DateTime
    {
        return $this->site_date_end;
    }

    public function setCreated(\DateTime $created_at): Site
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreated(): \DateTime
    {
        return $this->created_at;
    }

    public function setUpdated(?\DateTime $updated_at): Site
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setDeleted(?\DateTime $deleted_at): Site
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
            Keys::DATABASE_SITE_ID => $this->getId(),
            Keys::DATABASE_SITE_NUMBER_SITE => $this->getNumberSite(),
            Keys::DATABASE_SITE_DATE_START => $this->getDateStart()->getTimestamp(),
            Keys::DATABASE_SITE_DATE_END => $this->getDateEnd()->getTimestamp(),
            Keys::DATABASE_CREATED_AT => $this->getCreated()->getTimestamp(),
            Keys::DATABASE_UPDATED_AT => ($this->getUpdated() !== null ? $this->getUpdated()->getTimestamp() : null),
            Keys::DATABASE_DELETED_AT => ($this->getDeleted() !== null ? $this->getDeleted()->getTimestamp() : null)
        );
    }

    public function fromDatabase(array $array): void
    {
        $this->setId($array[Keys::DATABASE_SITE_ID]);
        $this->setNumberSite($array[Keys::DATABASE_SITE_NUMBER_SITE]);
        $this->setDateStart(Functions::fromUnix($array[Keys::DATABASE_SITE_DATE_START]));
        $this->setDateEnd(Functions::fromUnix($array[Keys::DATABASE_SITE_DATE_END]));
        $this->setCreated(Functions::fromUnix($array[Keys::DATABASE_CREATED_AT]));
        $this->setUpdated(($array[Keys::DATABASE_UPDATED_AT] !== null ? Functions::fromUnix($array[Keys::DATABASE_UPDATED_AT]) : null));
        $this->setDeleted(($array[Keys::DATABASE_DELETED_AT] !== null ? Functions::fromUnix($array[Keys::DATABASE_DELETED_AT]) : null));
    }

    public static function getCurrentSites(): array
    {
        $mySites = [];
        $myResult = DB::select("SELECT * FROM hc_site WHERE deleted_at IS NULL");
        foreach ($myResult as $item) {
            $site = new Site();
            $site->fromDatabase(json_decode(json_encode($item), true));
            $mySites[] = $site;
        }
        return $mySites;
    }

    public static function getPreviousSites(): array
    {
        $mySites = [];
        $myResult = DB::select("SELECT * FROM hc_site WHERE deleted_at IS NOT NULL");
        foreach ($myResult as $item) {
            $site = new Site();
            $site->fromDatabase(json_decode(json_encode($item), true));
            $mySites[] = $site;
        }
        return $mySites;
    }

    public static function getSiteById(int $id): ?Site
    {
        $mySite = new Site();
        $myResult = DB::select("SELECT * FROM hc_site WHERE site_id = $id");
        if (count($myResult) > 0) {
            foreach ($myResult as $item) {
                $mySite->fromDatabase(json_decode(json_encode($item), true));
            }
            return $mySite;
        }
        return null;
    }

    public static function getSiteByNumberSite(int $numberSite): ?Site
    {
        $mySite = new Site();
        $myResult = DB::select("SELECT * FROM hc_site WHERE site_number_site = $numberSite");
        if (count($myResult) > 0 ) {
            foreach ($myResult as $item) {
                $mySite->fromDatabase(json_decode(json_encode($item), true));
            }
            return $mySite;
        }
        return null;
    }

    public static function getAllSites(): array
    {
        $mySites = [];
        $myResult = DB::select("SELECT * FROM hc_site");
        foreach ($myResult as $item) {
            $site = new Site();
            $site->fromDatabase(json_decode(json_encode($item), true));
            $mySites[] = $site;
        }
        return $mySites;
    }

    public static function getSiteByUser(string $userId): array
    {
        $mySites = [];
        $myResult = DB::select("SELECT s.* FROM hc_site s INNER JOIN hc_site_data d ON s.site_id = d.data_site_id
                                WHERE s.site_id = d.data_site_id AND d.data_column = $userId
                                AND (d.data_key = 'employee' OR d.data_key = 'customer' OR d.data_key = 'manager') ");
        foreach ($myResult as $item) {
            $site = new Site();
            $site->fromDatabase(json_decode(json_encode($item), true));
            $mySites[] = $site;
        }
        return $mySites;
    }

    public static function getSitesByYear(int $startYear, int $endYear): array
    {
        $mySites = [];
        $myResult = DB::select("SELECT * FROM hc_site s WHERE s.site_date_start BETWEEN $startYear AND $endYear");
        foreach ($myResult as $item) {
            $site = new Site();
            $site->fromDatabase( json_decode(json_encode($item), true) );
            $mySites[] = $site;
        }
        return $mySites;
    }

    /**
     * @OA\Schema(
     *     schema="PostSiteRequest",
     *     required={"site_number_site", "site_date_start", "site_date_end"},
     *     @OA\Property(
     *          property="site_number_site",
     *          type="integer",
     *          default=987654,
     *          description="Number of site"
     *     ),
     *     @OA\Property(
     *          property="site_date_start",
     *          type="integer",
     *          default=1648601639,
     *          description="Begin date of site"
     *     ),
     *     @OA\Property(
     *          property="site_date_end",
     *          type="integer",
     *          default=1649465639,
     *          description="End date of site"
     *     )
     * )
     *
     * @param Site $site
     * @return bool
     */

    public static function addSite(Site $site): bool
    {
        $id = DB::table('hc_site')->insertGetId($site->toArray());
        $site->setId($id);
        return $id !== 0;
    }


    /**
     * @OA\Schema(
     *     schema="UpdateSiteRequest",
     *     required={"site_id", "site_number_site", "site_date_start", "site_date_end"},
     *     @OA\Property(
     *          property="site_id",
     *          type="integer",
     *          default=2,
     *          description="Site Id"
     *     ),
     *     @OA\Property(
     *          property="site_number_site",
     *          type="integer",
     *          default=987654,
     *          description="Number of site"
     *     ),
     *     @OA\Property(
     *          property="site_date_start",
     *          type="integer",
     *          default=1648601639,
     *          description="Begin date of site"
     *     ),
     *     @OA\Property(
     *          property="site_date_end",
     *          type="integer",
     *          default=1649465639,
     *          description="End date of site"
     *     )
     * )
     *
     * @param Site $site
     * @return bool
     */
    public static function updateSite(Site $site): bool
    {
        $site->setUpdated(new \DateTime());
        return DB::table('hc_site')->where('site_id', $site->getId())->update($site->toArray());
    }

    public static function deleteSite(Site $site): bool
    {
        $site->setDeleted(new \DateTime());
        return DB::table('hc_site')->where('site_id', $site->getId())
            ->where('deleted_at', null)->update($site->toArray());
    }

}
