<?php

namespace App\Models;

use App\Models\Utils\Functions;
use App\Models\Utils\Keys;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


/**
 *  @OA\Schema (
 *     schema="Ticket",
 *     description="Ticket Model",
 * )
 */
class Ticket extends Model
{
    /**
     * @OA\Property
     * @var int
     */
    private int $ticket_id;
    /**
     * @OA\Property
     * @var string
     */
    private string $ticket_subject;
    /**
     * @OA\Property
     * @var \DateTime
     */
    private \DateTime $created_at;
    /**
     * @OA\Property
     * @var \DateTime|null
     */
    private ?\DateTime $updated_at;
    /**
     * @OA\Property
     * @var \DateTime|null
     */
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


    /**
     * @OA\Schema(
     *     schema="TicketWithData",
     *     description="Ticket Model with data",
     *     allOf={@OA\Schema(ref="#/components/schemas/Ticket")},
     *      @OA\Property(
     *          property="data",
     *          type="array",
     *          @OA\Items(ref="#/components/schemas/TicketData"),
     *          minItems=2
     *     )
     * )
     */

    /**
     * @param int $id
     * @return $this
     */
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

    public function fromDatabase(array $array): void {
        $this->setTicketId( $array[Keys::DATABASE_TICKET_ID] );
        $this->setTicketSubject( $array[Keys::DATABASE_TICKET_SUBJECT] );
        $this->setCreated(Functions::fromUnix($array[Keys::DATABASE_CREATED_AT]));
        $this->setUpdated(($array[Keys::DATABASE_UPDATED_AT] !== null ? Functions::fromUnix($array[Keys::DATABASE_UPDATED_AT]) : null));
        $this->setDeleted(($array[Keys::DATABASE_DELETED_AT] !== null ? Functions::fromUnix($array[Keys::DATABASE_DELETED_AT]) : null));
    }

    public static function getAllTickets(): array
    {
        $myTickets = [];
        $myResult = DB::select("SELECT * FROM hc_ticket");
        foreach ($myResult as $item) {
            $ticket = new Ticket();
            $ticket->fromDatabase(json_decode(json_encode($item), true));
            $myTickets[] = $ticket;
        }
        return $myTickets;
    }

    public static function getTicketById(int $ticket_id): ?Ticket
    {
        $myTicket = new Ticket();
        $myResult = DB::select("SELECT * FROM hc_ticket WHERE ticket_id = $ticket_id");
        if (count($myResult) > 0) {
            foreach ($myResult as $item) {
                $myTicket->fromDatabase(json_decode(json_encode($item), true));
            }
            return $myTicket;
        }
        return null;
    }

    public static function getTicketBySite(string $siteId): array {
        $myTickets = [];
        $myResult = DB::select("SELECT t.* FROM hc_ticket t INNER JOIN hc_ticket_data d ON t.ticket_id = d.data_ticket_id
                                WHERE d.data_ticket_id = t.ticket_id AND d.data_key = 'site' AND d.data_column = $siteId");
        foreach ($myResult as $item) {
            $ticket = new Ticket();
            $ticket->fromDatabase(json_decode(json_encode($item), true));
            $myTickets[] = $ticket;
        }
        return $myTickets;
    }

    public static function getTicketByEmployee(string $userId): array {
        $myTickets = [];
        $myResult = DB::select("SELECT t.* FROM hc_ticket t INNER JOIN hc_ticket_data d ON t.ticket_id = d.data_ticket_id
                                WHERE d.data_ticket_id = t.ticket_id AND d.data_key = 'employee' AND d.data_column = $userId");
        foreach ($myResult as $item) {
            $ticket = new Ticket();
            $ticket->fromDatabase(json_decode(json_encode($item), true));
            $myTickets[] = $ticket;
        }
        return $myTickets;
    }

    public static function addTicket(Ticket $ticket): bool {
        return DB::table('hc_ticket')->insert($ticket->toArray());
    }

    public static function updateTicket(Ticket $ticket): bool {
        $ticket->setUpdated(new \DateTime());
        return DB::table('hc_ticket')->where('ticket_id', $ticket->getTicketId())->update($ticket->toArray());
    }

    public static function deleteTicket(Ticket $ticket): bool {
        $ticket->setDeleted(new \DateTime());
        return DB::table('hc_ticket')->where('ticket_id', $ticket->getTicketId())
            ->where('deleted_at', null)->update($ticket->toArray());
    }
}
