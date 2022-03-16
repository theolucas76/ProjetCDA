<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myTicket = new Ticket();
        $myTicket->setTicketSubject('Fuite d\'eau');
        DB::table('hc_ticket')->insert($myTicket->toArray());

        $myTicketData = $this->generateTicketData();
        foreach ($myTicketData as $data) {
            DB::table('hc_ticket_data')->insert($data->toArray());
        }
    }

    public function generateTicketData(): array {

        $myEmployee = new TicketData();
        $myEmployee->setDataTicketId(1);
        $myEmployee->setDataKey('employee');
        $myEmployee->setDataColumn('3');

        $myDescription = new TicketData();
        $myDescription->setDataTicketId(1);
        $myDescription->setDataKey('description');
        $myDescription->setDataColumn('Fuite d\'eau dans le lavabo de la salle du haut');

        $mySite = new TicketData();
        $mySite->setDataTicketId(1);
        $mySite->setDataKey('site');
        $mySite->setDataColumn('1');

        $myRef = new TicketData();
        $myRef->setDataTicketId(1);
        $myRef->setDataKey('reference');
        $myRef->setDataColumn('123456-01');

        return array(
            $myEmployee,
            $myRef,
            $mySite,
            $myDescription
        );
    }
}
