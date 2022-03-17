<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myTask = new Task();
        $myTask->setTaskName('Intervention plomberie');
        $myTask->setDateStart($myTask->getDateStart()->add(new \DateInterval('P10D')));
        $myTask->setDateEnd($myTask->getDateEnd()->add(new \DateInterval('P20D')));
        DB::table('hc_task')->insert($myTask->toArray());

        $myTaskData = $this->generateTaskData();
        foreach ($myTaskData as $data) {
            DB::table('hc_task_data')->insert($data->toArray());
        }
    }

    public function generateTaskData(): array {

        $myUser = new TaskData();
        $myUser->setDataTaskId(1);
        $myUser->setDataKey('user');
        $myUser->setDataColumn('3');

        $mySite = new TaskData();
        $mySite->setDataTaskId(1);
        $mySite->setDataKey('site');
        $mySite->setDataColumn('1');

        $myDescription = new TaskData();
        $myDescription->setDataTaskId(1);
        $myDescription->setDataKey('description');
        $myDescription->setDataColumn('Remplaces le tuyau du lavabo là');

        return array($myUser, $mySite, $myDescription);
    }
}
