<?php

namespace App\Models;

use App\Models\Utils\Functions;
use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Cloner\Data;

class Task extends Model
{
    private int $task_id;
    private string $task_name;
    private \DateTime $task_date_start;
    private \DateTime $task_date_end;
    private \DateTime $created_at;
    private ?\DateTime $updated_at;
    private ?\DateTime $deleted_at;

    public function __construct()
    {
        parent::__construct();
        $this->setTaskId(0);
        $this->setTaskName('');
        $this->setDateStart(new \DateTime());
        $this->setDateEnd(new \DateTime());
        $this->setCreated(new \DateTime());
        $this->setUpdated(null);
        $this->setDeleted(null);
    }

    public function setTaskId(int $id): Task
    {
        $this->task_id = $id;
        return $this;
    }

    public function getTaskId(): int
    {
        return $this->task_id;
    }

    public function setTaskName(string $name): Task
    {
        $this->task_name = $name;
        return $this;
    }

    public function getTaskName(): string
    {
        return $this->task_name;
    }

    public function setDateStart(\DateTime $date): Task
    {
        $this->task_date_start = $date;
        return $this;
    }

    public function getDateStart(): \DateTime
    {
        return $this->task_date_start;
    }

    public function setDateEnd(\DateTime $date): Task
    {
        $this->task_date_end = $date;
        return $this;
    }

    public function getDateEnd(): \DateTime
    {
        return $this->task_date_end;
    }


    public function setCreated(\DateTime $created_at): Task
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreated(): \DateTime
    {
        return $this->created_at;
    }

    public function setUpdated(?\DateTime $updated_at): Task
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setDeleted(?\DateTime $deleted_at): Task
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
            Keys::DATABASE_TASK_ID => $this->getTaskId(),
            Keys::DATABASE_TASK_NAME => $this->getTaskName(),
            Keys::DATABASE_TASK_DATE_START => $this->getDateStart()->getTimestamp(),
            Keys::DATABASE_TASK_DATE_END => $this->getDateEnd()->getTimestamp(),
            Keys::DATABASE_CREATED_AT => $this->getCreated()->getTimestamp(),
            Keys::DATABASE_UPDATED_AT => ($this->getUpdated() !== null ? $this->getUpdated()->getTimestamp() : null),
            Keys::DATABASE_DELETED_AT => ($this->getDeleted() !== null ? $this->getDeleted()->getTimestamp() : null)
        );
    }

    public function fromDatabase(array $array): void
    {
        $this->setTaskId($array[Keys::DATABASE_TASK_ID]);
        $this->setTaskName($array[Keys::DATABASE_TASK_NAME]);
        $this->setDateStart(Functions::fromUnix($array[Keys::DATABASE_TASK_DATE_START]));
        $this->setDateEnd(Functions::fromUnix($array[Keys::DATABASE_TASK_DATE_END]));
        $this->setCreated(Functions::fromUnix($array[Keys::DATABASE_CREATED_AT]));
        $this->setUpdated(($array[Keys::DATABASE_UPDATED_AT] !== null ? Functions::fromUnix($array[Keys::DATABASE_UPDATED_AT]) : null));
        $this->setDeleted(($array[Keys::DATABASE_DELETED_AT] !== null ? Functions::fromUnix($array[Keys::DATABASE_DELETED_AT]) : null));
    }

    public static function getTaskById(int $task_id): ?Task
    {
        $myTask = new Task();
        $myResult = DB::select("SELECT * FROM hc_task WHERE task_id = $task_id");
        if (count($myResult) > 0) {
            foreach ($myResult as $item) {
                $myTask->fromDatabase(json_decode(json_encode($item), true));
            }
            return $myTask;
        }
        return null;
    }

    public static function getTaskByUser(string $user_id): array
    {
        $myTasks = [];
        $myResult = DB::select("SELECT t.* FROM hc_task t INNER JOIN hc_task_data d
                    ON t.task_id = d.data_task_id WHERE d.data_key = 'user' AND d.data_column = $user_id");
        foreach ($myResult as $item) {
            $task = new Task();
            $task->fromDatabase(json_decode(json_encode($item), true));
            $myTasks[] = $task;
        }
        return $myTasks;
    }

    public static function getTaskBySite(string $site_id): array
    {
        $myTasks = [];
        $myResult = DB::select("SELECT t.* FROM hc_task t INNER JOIN hc_task_data d
                    ON t.task_id = d.data_task_id WHERE d.data_key = 'site' AND d.data_column = $site_id");
        foreach ($myResult as $item) {
            $task = new Task();
            $task->fromDatabase(json_decode(json_encode($item), true));
            $myTasks[] = $task;
        }
        return $myTasks;
    }

    public static function addTask(Task $task): bool
    {
        $id = DB::table('hc_task')->insertGetId($task->toArray());
        $task->setTaskId($id);
        return $id !== 0;
    }

    public static function updateTask(Task $task): bool
    {
        $task->setUpdated(new \DateTime());
        return DB::table('hc_task')->where('task_id', $task->getTaskId())->update($task->toArray());
    }

    public static function deleteTask(Task $task): bool
    {
        $task->setDeleted( new \DateTime() );
        return DB::table('hc_task')->where('task_id', $task->getTaskId())
            ->where('deleted_at', null)->update($task->toArray());
    }

}
