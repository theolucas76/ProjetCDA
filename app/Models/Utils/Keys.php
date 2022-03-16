<?php

namespace App\Models\Utils;

class Keys
{
    // COMMON
    public const DATABASE_ID = 'id';
    public const DATABASE_CREATED_AT = 'created_at';
    public const DATABASE_UPDATED_AT = 'updated_at';
    public const DATABASE_DELETED_AT = 'deleted_at';

    // USER
    public const DATABASE_LOGIN = 'login';
    public const DATABASE_PASSWORD = 'password';
    public const DATABASE_ROLE = 'role';

    // USER DATA
    public const DATABASE_USER_DATA_ID = 'userData_id';
    public const DATABASE_USER_DATA_USER_ID = 'userData_userId';
    public const DATABASE_USER_DATA_KEY = 'userData_key';
    public const DATABASE_USER_DATA_COLUMN = 'userData_column';

}
