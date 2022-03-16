<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    protected $fillable = [
        'userData_id', 'userData_userId', 'userData_key', 'userData_column'
    ];


    private int $userData_id;
    private int $userData_userId;
    private string $userData_key;
    private string $userData_column;

    public function __construct()
    {
        parent::__construct();
        $this->setId(0);
        $this->setUserId(0);
        $this->setUserDataKey('');
        $this->setUserDataColumn('');
    }

    /**
     * @param int $id
     * @return UserData
     */
    public function setId(int $id): UserData {
        $this->userData_id = $id;
        return $this;
    }
    /**
     * @return int
     */
    public function getId(): int {
        return $this->userData_id;
    }

    /**
     * @param int $userId
     * @return UserData
     */
    public function setUserId(int $userId): UserData {
        $this->userData_userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int {
        return $this->userData_userId;
    }

    /**
     * @param string $key
     * @return UserData
     */
    public function setUserDataKey(string $key): UserData {
        $this->userData_key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserDataKey(): string {
        return $this->userData_key;
    }

    /**
     * @param string $column
     * @return UserData
     */
    public function setUserDataColumn(string $column): UserData {
        $this->userData_column = $column;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserDataColumn(): string {
        return $this->userData_column;
    }

    public function toArray(): array
    {
        return array(
            Keys::DATABASE_USER_DATA_ID => $this->getId(),
            Keys::DATABASE_USER_DATA_USER_ID => $this->getUserId(),
            Keys::DATABASE_USER_DATA_KEY => $this->getUserDataKey(),
            Keys::DATABASE_USER_DATA_COLUMN => $this->getUserDataColumn()
        );
    }

}
