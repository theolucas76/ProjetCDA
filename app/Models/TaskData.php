<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;

class TaskData extends Model
{

    private int $data_id;
    private int $data_task_id;
    private string $data_key;
    private string $data_column;

    public function __construct()
    {
        parent::__construct();
        $this->setDataId(0);
        $this->setDataTaskId(0);
        $this->setDataKey('');
        $this->setDataColumn('');
    }

    public function setDataId(int $id): TaskData {
        $this->data_id = $id;
        return $this;
    }
    public function getDataId(): int {
        return $this->data_id;
    }
    public function setDataTaskId(int $ticket_id): TaskData {
        $this->data_task_id = $ticket_id;
        return $this;
    }
    public function getDataTaskId(): int {
        return $this->data_task_id;
    }
    public function setDataKey(string $key): TaskData {
        $this->data_key = $key;
        return $this;
    }
    public function getDataKey(): string {
        return $this->data_key;
    }
    public function setDataColumn(string $column): TaskData {
        $this->data_column = $column;
        return $this;
    }
    public function getDataColumn(): string {
        return $this->data_column;
    }

    public function toArray(): array
    {
        return array(
            Keys::DATABASE_DATA_ID => $this->getDataId(),
            Keys::DATABASE_TASK_DATA_TASK_ID => $this->getDataTaskId(),
            Keys::DATABASE_DATA_KEY => $this->getDataKey(),
            Keys::DATABASE_DATA_COLUMN => $this->getDataColumn()
        );
    }
}
