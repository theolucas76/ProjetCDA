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

    // SITE
    public const DATABASE_SITE_ID = 'site_Id';
    public const DATABASE_SITE_NUMBER_SITE = 'site_numberSite';
    public const DATABASE_SITE_DATE_START = 'site_dateStart';
    public const DATABASE_SITE_DATE_END = 'site_dateEnd';

    // SITE DATA
    public const DATABASE_SITE_DATA_ID = 'siteData_id';
    public const DATABASE_SITE_DATA_SITE_ID = 'siteData_siteId';
    public const DATABASE_SITE_DATA_KEY = 'siteData_key';
    public const DATABASE_SITE_DATA_COLUMN = 'siteData_column';

    // TICKET
    public const DATABASE_TICKET_ID = 'ticket_id';
    public const DATABASE_TICKET_SUBJECT = 'ticket_subject';

    // TICKET DATA
    public const DATABASE_TICKET_DATA_ID = 'data_id';
    public const DATABASE_TICKET_DATA_TICKET_ID = 'data_ticket_id';
    public const DATABASE_TICKET_DATA_KEY = 'data_key';
    public const DATABASE_TICKET_DATA_COLUMN = 'data_column';

}
