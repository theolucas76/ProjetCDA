<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;

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

    public function setTaskId(int $id): Task {
        $this->task_id = $id;
        return $this;
    }
    public function getTaskId(): int {
        return $this->task_id;
    }

    public function setTaskName(string $name): Task {
        $this->task_name = $name;
        return $this;
    }
    public function getTaskName(): string {
        return $this->task_name;
    }

    public function setDateStart(\DateTime $date): Task {
        $this->task_date_start = $date;
        return $this;
    }
    public function getDateStart(): \DateTime {
        return $this->task_date_start;
    }

    public function setDateEnd(\DateTime $date): Task {
        $this->task_date_end = $date;
        return $this;
    }
    public function getDateEnd(): \DateTime {
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
}
