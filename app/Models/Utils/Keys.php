<?php

namespace App\Models\Utils;

class Keys
{
    // COMMON
    public const DATABASE_ID = 'id';
    public const DATABASE_CREATED_AT = 'created_at';
    public const DATABASE_UPDATED_AT = 'updated_at';
    public const DATABASE_DELETED_AT = 'deleted_at';

    public const DATABASE_DATA_ID = 'data_id';
    public const DATABASE_DATA_KEY = 'data_key';
    public const DATABASE_DATA_COLUMN = 'data_column';


    // USER
    public const DATABASE_LOGIN = 'login';
    public const DATABASE_PASSWORD = 'password';
    public const DATABASE_ROLE = 'role';
    public const DATABASE_JOB = 'job';

    // USER DATA
    public const DATABASE_USER_DATA_USER_ID = 'data_user_id';

    // SITE
    public const DATABASE_SITE_ID = 'site_id';
    public const DATABASE_SITE_NUMBER_SITE = 'site_number_site';
    public const DATABASE_SITE_DATE_START = 'site_date_start';
    public const DATABASE_SITE_DATE_END = 'site_date_end';

    // SITE DATA
    public const DATABASE_SITE_DATA_SITE_ID = 'data_site_id';

    // TICKET
    public const DATABASE_TICKET_ID = 'ticket_id';
    public const DATABASE_TICKET_SUBJECT = 'ticket_subject';

    // TICKET DATA
    public const DATABASE_TICKET_DATA_TICKET_ID = 'data_ticket_id';

    // MATERIAL
    public const DATABASE_MATERIAL_ID = 'material_id';
    public const DATABASE_MATERIAL_NAME = 'material_name';

    // MATERIAL DATA
    public const DATABASE_MATERIAL_DATA_MATERIAL_ID = 'data_material_id';
}
