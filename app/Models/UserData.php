<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 *
 * @OA\Schema(
 *     schema="UserData",
 *     description="UserData Model"
 * )
 *
 */

class UserData extends Model
{
    protected $fillable = [
        'data_id', 'data_user_id', 'data_key', 'data_column'
    ];

    /**
     * @OA\Property
     * @var int
     */
    private int $data_id;

    /**
     * @OA\Property
     * @var int
     */
    private int $data_user_id;

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
        $this->setUserId(0);
        $this->setDataKey('');
        $this->setDataColumn('');
    }

    /**
     * @param int $id
     * @return UserData
     */
    public function setId(int $id): UserData
    {
        $this->data_id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->data_id;
    }

    /**
     * @param int $userId
     * @return UserData
     */
    public function setUserId(int $userId): UserData
    {
        $this->data_user_id = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->data_user_id;
    }

    /**
     * @param string $key
     * @return UserData
     */
    public function setDataKey(string $key): UserData
    {
        $this->data_key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataKey(): string
    {
        return $this->data_key;
    }

    /**
     * @param string $column
     * @return UserData
     */
    public function setDataColumn(string $column): UserData
    {
        $this->data_column = $column;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataColumn(): string
    {
        return $this->data_column;
    }

    public function toArray(): array
    {
        return array(
            Keys::DATABASE_DATA_ID => $this->getId(),
            Keys::DATABASE_USER_DATA_USER_ID => $this->getUserId(),
            Keys::DATABASE_DATA_KEY => $this->getDataKey(),
            Keys::DATABASE_DATA_COLUMN => $this->getDataColumn()
        );
    }

    public function fromDatabase(array $array): void
    {
        $this->setId($array[Keys::DATABASE_DATA_ID]);
        $this->setUserId($array[Keys::DATABASE_USER_DATA_USER_ID]);
        $this->setDataKey($array[Keys::DATABASE_DATA_KEY]);
        $this->setDataColumn($array[Keys::DATABASE_DATA_COLUMN]);
    }

    public static function getAllUserData(): array
    {
        $myUserDatas = [];
        $myResult = DB::select("SELECT * FROM hc_user_data");
        foreach ($myResult as $item) {
            $userData = new UserData();
            $userData->fromDatabase(json_decode(json_encode($item), true));
            $myUserDatas[] = $userData;
        }
        return $myUserDatas;
    }

    public static function getDataById(int $id): ?UserData
    {
        $myUserData = new UserData();
        $myResult = DB::select("SELECT * FROM hc_user_data WHERE data_id = $id");
        if (count($myResult) > 0) {
            foreach ($myResult as $item) {
                $myUserData->fromDatabase(json_decode(json_encode($item), true));
            }
            return $myUserData;
        }
        return null;
    }

    public static function getDataByUser(int $user): array
    {
        $myUserDatas = [];
        $myResult = DB::select("SELECT * FROM hc_user_data WHERE data_user_id = $user");
        foreach ($myResult as $item) {
            $userData = new UserData();
            $userData->fromDatabase(json_decode(json_encode($item), true));
            $myUserDatas[] = $userData;
        }
        return $myUserDatas;
    }

    /**
     * @OA\Schema(
     *     schema="PostUserDataRequest",
     *     required={"data_user_id", "data_key", "data_column"},
     *     @OA\Property(
     *          property="data_user_id",
     *          type="integer",
     *          default=1,
     *          description="User id"
     *     ),
     *     @OA\Property(
     *          property="data_key",
     *          type="string",
     *          default="key",
     *          description="Key of the column value"
     *     ),
     *     @OA\Property(
     *          property="data_column",
     *          type="string",
     *          default="column",
     *          description="Value of the key"
     *     )
     * )
     *
     * @param UserData $data
     * @return bool
     */
    public static function addUserData(UserData $data): bool
    {
        $id = DB::table('hc_user_data')->insertGetId($data->toArray());
        $data->setId($id);
        return $id !== 0;
    }

    /**
     * @OA\Schema(
     *     schema="UpdateUserDataRequest",
     *     required={"data_id", "data_key", "data_column"},
     *     @OA\Property(
     *          property="data_id",
     *          type="integer",
     *          default=1,
     *          description="UserData id"
     *     ),
     *     @OA\Property(
     *          property="data_key",
     *          type="string",
     *          default="key",
     *          description="Key of the column value"
     *     ),
     *     @OA\Property(
     *          property="data_column",
     *          type="string",
     *          default="column",
     *          description="Value of the key"
     *     )
     * )
     * @param UserData $data
     * @return bool
     */
    public static function updateUserData(UserData $data): bool
    {
        return DB::table('hc_user_data')->where(Keys::DATABASE_DATA_ID, $data->getId())
            ->update($data->toArray());
    }

    public static function deleteUserData(int $id): bool
    {
        return DB::delete("DELETE FROM hc_user_data WHERE data_id = $id");
    }

}
