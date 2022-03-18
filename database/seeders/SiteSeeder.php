<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\SiteData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mySite = new Site();
        $mySite->setNumberSite(123456);
        $mySite->setDateStart($mySite->getDateStart()->add(new \DateInterval('P10D')));
        $mySite->setDateEnd($mySite->getDateEnd()->add(new \DateInterval('P20D')));
        DB::table('hc_site')->insert($mySite->toArray());

        $mySitesData = $this->generateSitesData();
        foreach ($mySitesData as $data) {
            DB::table('hc_site_data')->insert($data->toArray());
        }
    }

    public function generateSitesData(): array {

        $address = new SiteData();
        $address->setSiteId(1);
        $address->setDataKey('address');
        $address->setDataColumn('14 Rue du Beauvallon 76290 Montivilliers');

        $customer = new SiteData();
        $customer->setSiteId(1);
        $customer->setDataKey('customer');
        $customer->setDataColumn('4');

        $manager = new SiteData();
        $manager->setSiteId(1);
        $manager->setDataKey('manager');
        $manager->setDataColumn('2');

        $employee = new SiteData();
        $employee->setSiteId(1);
        $employee->setDataKey('employee');
        $employee->setDataColumn('3');

        $material = new SiteData();
        $material->setSiteId(1);
        $material->setDataKey('mat-1');
        $material->setDataColumn('10');

        return array(
            $address, $customer, $manager, $employee, $material
        );
    }
}
