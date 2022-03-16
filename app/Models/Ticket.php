<?php

namespace App\Models;

use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    private int $ticket_id;
    private string $ticket_subject;
    private \DateTime $created_at;
    private ?\DateTime $updated_at;
    private ?\DateTime $deleted_at;

    public function __construct()
    {
        parent::__construct();
        $this->setTicketId(0);
        $this->setTicketSubject('');
        $this->setCreated(new \DateTime());
        $this->setUpdated(null);
        $this->setDeleted(null);
    }

    public function setTicketId(int $id): Ticket
    {
        $this->ticket_id = $id;
        return $this;
    }

    public function getTicketId(): int
    {
        return $this->ticket_id;
    }

    public function setTicketSubject(string $subject): Ticket
    {
        $this->ticket_subject = $subject;
        return $this;
    }

    public function getTicketSubject(): string
    {
        return $this->ticket_subject;
    }

    public function setCreated(\DateTime $created_at): Ticket
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreated(): \DateTime
    {
        return $this->created_at;
    }

    public function setUpdated(?\DateTime $updated_at): Ticket
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setDeleted(?\DateTime $deleted_at): Ticket
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
            Keys::DATABASE_TICKET_ID => $this->getTicketId(),
            Keys::DATABASE_TICKET_SUBJECT => $this->getTicketSubject(),
            Keys::DATABASE_CREATED_AT => $this->getCreated()->getTimestamp(),
            Keys::DATABASE_UPDATED_AT => ($this->getUpdated() !== null ? $this->getUpdated()->getTimestamp() : null),
            Keys::DATABASE_DELETED_AT => ($this->getDeleted() !== null ? $this->getDeleted()->getTimestamp() : null)
        );
    }

}
