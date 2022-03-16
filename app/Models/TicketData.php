<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;

class TicketData extends Model
{
    private int $data_id;
    private int $data_ticket_id;
    private string $data_key;
    private string $data_column;

    public function __construct()
    {
        parent::__construct();
        $this->setDataId(0);
        $this->setDataTicketId(0);
        $this->setDataKey('');
        $this->setDataColumn('');
    }

    public function setDataId(int $id): TicketData {
        $this->data_id = $id;
        return $this;
    }
    public function getDataId(): int {
        return $this->data_id;
    }
    public function setDataTicketId(int $ticket_id): TicketData {
        $this->data_ticket_id = $ticket_id;
        return $this;
    }
    public function getDataTicketId(): int {
        return $this->data_ticket_id;
    }
    public function setDataKey(string $key): TicketData {
        $this->data_key = $key;
        return $this;
    }
    public function getDataKey(): string {
        return $this->data_key;
    }
    public function setDataColumn(string $column): TicketData {
        $this->data_column = $column;
        return $this;
    }
    public function getDataColumn(): string {
        return $this->data_column;
    }

    public function toArray(): array
    {
        return array(
            Keys::DATABASE_TICKET_DATA_ID => $this->getDataId(),
            Keys::DATABASE_TICKET_DATA_TICKET_ID => $this->getDataTicketId(),
            Keys::DATABASE_TICKET_DATA_KEY => $this->getDataKey(),
            Keys::DATABASE_TICKET_DATA_COLUMN => $this->getDataColumn()
        );
    }
}
