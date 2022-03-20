<?php

namespace Database\Seeders;

use App\Models\Enums\Job;
use App\Models\Enums\Role;
use App\Models\UserData;
use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myDirector = $this->generateUsers(new Role(Role::DIRECTOR));
        DB::table('users')->insert($myDirector->toArray());

        $myDirectorData = $this->generateUsersData(new Role(Role::DIRECTOR));
        foreach ($myDirectorData as $data) {
            DB::table('hc_user_data')->insert($data->toArray());
        }

        $myManager = $this->generateUsers(new Role(Role::MANAGER));
        DB::table('users')->insert($myManager->toArray());

        $myManagerData = $this->generateUsersData(new Role(Role::MANAGER));
        foreach ($myManagerData as $data) {
            DB::table('hc_user_data')->insert($data->toArray());
        }

        $myEmployee = $this->generateUsers(new Role(Role::EMPLOYEE));
        DB::table('users')->insert($myEmployee->toArray());

        $myEmployeeData = $this->generateUsersData(new Role(Role::EMPLOYEE));
        foreach ($myEmployeeData as $data) {
            DB::table('hc_user_data')->insert($data->toArray());
        }

        $myCustomer = $this->generateUsers(new Role(Role::CUSTOMER));
        DB::table('users')->insert($myCustomer->toArray());

        $myCustomerData = $this->generateUsersData(new Role(Role::CUSTOMER));
        foreach ($myCustomerData as $data) {
            DB::table('hc_user_data')->insert($data->toArray());
        }
    }

    public function generateUsers(Role $role): Users
    {
        $myUser = new Users();

        switch ($role->__toInt()) {
            case Role::DIRECTOR:
                $myUser->setLogin('director');
                $myUser->setPassword(Hash::make('director'));
                $myUser->setRole($role);
                $myUser->setJob(new Job(Job::UNDEFINED));
                break;
            case Role::MANAGER:
                $myUser->setLogin('manager');
                $myUser->setPassword(Hash::make('manager'));
                $myUser->setRole($role);
                $myUser->setJob(new Job(Job::UNDEFINED));
                break;
            case Role::EMPLOYEE:
                $myUser->setLogin('employee');
                $myUser->setPassword(Hash::make('employee'));
                $myUser->setRole($role);
                $myUser->setJob(new Job(Job::PLUMBER));
                break;
            case Role::CUSTOMER:
                $myUser->setLogin('customer');
                $myUser->setPassword(Hash::make('customer'));
                $myUser->setRole($role);
                $myUser->setJob(new Job(Job::UNDEFINED));
                break;
        }
        return $myUser;
    }

    public function generateUsersData(Role $role): array {

        $myUserDataName = new UserData();
        $myUserDataFirstname = new UserData();
        $myArray = array();
        switch ($role->__toInt()) {
            case Role::DIRECTOR:
                $myUserDataName->setUserId(1);
                $myUserDataName->setDataKey('Nom');
                $myUserDataName->setDataColumn('Director');
                $myUserDataFirstname->setUserId(1);
                $myUserDataFirstname->setDataKey('Prénom');
                $myUserDataFirstname->setDataColumn('Super');
                $myArray = [$myUserDataName, $myUserDataFirstname];
                break;
            case Role::MANAGER:
                $myUserDataName->setUserId(2);
                $myUserDataName->setDataKey('Nom');
                $myUserDataName->setDataColumn('Manager');
                $myUserDataFirstname->setUserId(2);
                $myUserDataFirstname->setDataKey('Prénom');
                $myUserDataFirstname->setDataColumn('Admin');
                $myArray = [$myUserDataName, $myUserDataFirstname];
                break;
            case Role::EMPLOYEE:
                $myUserDataName->setUserId(3);
                $myUserDataName->setDataKey('Nom');
                $myUserDataName->setDataColumn('Employee');
                $myUserDataFirstname->setUserId(3);
                $myUserDataFirstname->setDataKey('Prénom');
                $myUserDataFirstname->setDataColumn('Writer');
                $myArray = [$myUserDataName, $myUserDataFirstname];
                break;
            case Role::CUSTOMER:
                $myUserDataName->setUserId(4);
                $myUserDataName->setDataKey('Nom');
                $myUserDataName->setDataColumn('Customer');
                $myUserDataFirstname->setUserId(4);
                $myUserDataFirstname->setDataKey('Prénom');
                $myUserDataFirstname->setDataColumn('Viewer');
                $myArray = [$myUserDataName, $myUserDataFirstname];
                break;
        }
        return $myArray;
    }
}
