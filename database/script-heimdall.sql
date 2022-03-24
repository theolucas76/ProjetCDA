create table hc_material
(
    material_id   bigint unsigned auto_increment
        primary key,
    material_name varchar(191) not null,
    created_at    int          null,
    updated_at    int          null,
    deleted_at    int          null
)
    collate = utf8mb4_unicode_ci;

create table hc_material_data
(
    data_id          bigint unsigned auto_increment
        primary key,
    data_material_id int          not null,
    data_key         varchar(191) not null,
    data_column      varchar(191) not null
)
    collate = utf8mb4_unicode_ci;

create table hc_site
(
    site_id          bigint unsigned auto_increment
        primary key,
    site_number_site int not null,
    site_date_start  int not null,
    site_date_end    int not null,
    created_at       int null,
    updated_at       int null,
    deleted_at       int null
)
    collate = utf8mb4_unicode_ci;

create table hc_site_data
(
    data_id      bigint unsigned auto_increment
        primary key,
    data_site_id int          not null,
    data_key     varchar(191) not null,
    data_column  varchar(191) not null
)
    collate = utf8mb4_unicode_ci;

create table hc_task
(
    task_id         bigint unsigned auto_increment
        primary key,
    task_name       varchar(191) not null,
    task_date_start int          not null,
    task_date_end   int          not null,
    created_at      int          null,
    updated_at      int          null,
    deleted_at      int          null
)
    collate = utf8mb4_unicode_ci;

create table hc_task_data
(
    data_id      bigint unsigned auto_increment
        primary key,
    data_task_id int          not null,
    data_key     varchar(191) not null,
    data_column  varchar(191) not null
)
    collate = utf8mb4_unicode_ci;

create table hc_ticket
(
    ticket_id      bigint unsigned auto_increment
        primary key,
    ticket_subject varchar(191) not null,
    created_at     int          null,
    updated_at     int          null,
    deleted_at     int          null
)
    collate = utf8mb4_unicode_ci;

create table hc_ticket_data
(
    data_id        bigint unsigned auto_increment
        primary key,
    data_ticket_id int          not null,
    data_key       varchar(191) not null,
    data_column    varchar(191) not null
)
    collate = utf8mb4_unicode_ci;

create table hc_user_data
(
    data_id      bigint unsigned auto_increment
        primary key,
    data_user_id int          not null,
    data_key     varchar(191) not null,
    data_column  varchar(191) not null
)
    collate = utf8mb4_unicode_ci;

create table migrations
(
    id        int unsigned auto_increment
        primary key,
    migration varchar(255) not null,
    batch     int          not null
)
    collate = utf8mb4_unicode_ci;

create table users
(
    id         bigint unsigned auto_increment
        primary key,
    login      varchar(191) not null,
    password   varchar(191) not null,
    role       int          not null,
    job        int          not null,
    created_at int          null,
    updated_at int          null,
    deleted_at int          null,
    constraint users_login_unique
        unique (login)
)
    collate = utf8mb4_unicode_ci;

