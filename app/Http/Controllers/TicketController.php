<?php

namespace App\Http\Controllers;

use App\Models\Enums\Role;
use App\Models\Ticket;
use App\Models\TicketData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketController extends Controller
{
    /**
     *@OA\Get(
     *     path="/tickets/{ticketId}",
     *     summary="Get Ticket By Id",
     *     tags={"Tickets"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="ticketId",
     *          description="Ticket id",
     *          required=true,
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/TicketWithData")
     *          )
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized Response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     * @param Request $request
     * @param Response $response
     * @param string $ticketId
     * @return Response
     */
    public function getAction(Request $request, Response $response, string $ticketId): Response
    {
        $myTicket = Ticket::getTicketById($ticketId);
        if ($myTicket === null) {
            return $this->notFoundResponse($response, 'ticket');
        }
        $myTicketArray = $myTicket->toArray();
        $myTicketArray['data'] = array_map(static function (TicketData $data): array {
            return $data->toArray();
        }, TicketData::getTicketDataByTicket($myTicket->getTicketId()));
        return $this->okResponse($response, $myTicketArray );
    }

    /**
     * @OA\Get(
     *     path="/tickets",
     *     summary="Get All Tickets",
     *     tags={"Admin", "Tickets"},
     *     security={{ "apiAuth": {} }},
     *     description="Get all tickets and ticket's data, only for director",
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="tickets",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/TicketWithData"),
     *                  minItems=2
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getsAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            return $this->okResponse($response, array('tickets' => array_map(static function(Ticket $ticket): array {
                $myTicketArray = $ticket->toArray();
                $myTicketData = TicketData::getTicketDataByTicket($ticket->getTicketId());
                $myTicketArray['data'] = $myTicketData;
                return $myTicketArray;
            }, Ticket::getAllTickets())));
        }
        return $this->unauthorizedResponse($response, 'only director');
    }
}
