<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function fromDatabase(array $array): void
    {
        $this->setDataId( $array[Keys::DATABASE_DATA_ID] );
        $this->setDataTaskId( $array[Keys::DATABASE_TASK_DATA_TASK_ID] );
        $this->setDataKey( $array[Keys::DATABASE_DATA_KEY] );
        $this->setDataColumn( $array[Keys::DATABASE_DATA_COLUMN] );
    }

    public static function getTaskDataById(int $data_id): ?TaskData
    {
        $myTaskData = new TaskData();
        $myResult = DB::select("SELECT * FROM hc_task_data WHERE data_id $data_id");
        if (count($myResult) > 0) {
            foreach ($myResult as $item) {
                $myTaskData->fromDatabase( json_decode(json_encode( $item ), true) );
            }
            return $myTaskData;
        }
        return null;
    }

    public static function getTaskDataByTask(int $task_id): array
    {
        $myTaskDatas = [];
        $myResult = DB::select("SELECT * FROM hc_task_data WHERE data_task_id = $task_id");
        foreach ($myResult as $item) {
            $data = new TaskData();
            $data->fromDatabase( json_decode(json_encode( $item ), true) );
            $myTaskDatas[] = $data;
        }
        return $myTaskDatas;
    }
    public static function addTaskData(TaskData $data): bool
    {
        return DB::table('hc_task_data')->insert($data->toArray());
    }
    public static function updateTaskData(TaskData $data): bool
    {
        return DB::table('hc_task_data')->where('data_id', $data->getDataId())->update($data->toArray());
    }
    public static function deleteTaskData(int $data_id): bool
    {
        return DB::delete("DELETE FROM hc_task_data WHERE data_id = $data_id");
    }
}
