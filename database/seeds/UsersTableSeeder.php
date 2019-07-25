<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */



    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');
        $roles = array(
            'Admin',
            'Counsel',
            'Office Clerk'
        );

        $models = array(
            array('Profile', array(
                'Browse',
                'Edit'
            )),
            array('Client', array(
                'Browse',
                'Read',
                'Edit',
                'Add',
                'Delete',
                'Add Client Trust Fund'
            )),
            array('Counsel', array(
                'Browse',
                'Read',
                'Edit',
                'Add',
                'Delete'
            )),
            array('Contract', array(
                'Browse',
                'Read',
                'Edit',
                'Add',
                'Delete',
                'Add Case',
                'Edit Case',
                'Delete Case',
                'Add Fee',
                'Delete Fee'
            )),
            array('Case Tracker', array(
                'Browse',
                'Read',
                'Add activity',
                'Edit activity',
                'Delete activity'
            )),
            array('Supply Management', array(
                'Browse',
                'Edit',
                'Add',
                'Update'
            )),
            array('Charge Slip', array(
                'Browse',
                'Read',
                'Edit',
                'Add',
                'Delete'
            )),
            array('Cash Receipt', array(
                'Browse',
                'Read',
                'Edit',
                'Add',
            )),
            array('Activity Report Sheet', array(
                'Browse',
                'Read',
                'Edit',
                'Add',
                'Delete',
            )),
            array('Report', array(
                'Browse',
                'Cash Receipt',
                'Counsel Service Report',
                'Trust Fund Ledger',
            )),
            array('Service Report', array(
                'Browse',
                'Read',
                'Edit',
                'Add',
                'Delete',
                'Add Chargeable Expense',
                'Delete Chargeable Expense'
            )),
            array('Counsel Service Report', array(
                'Browse',
                'Read',
                'Edit',
                'Add',
                'Delete',
                'Add Chargeable Expense',
                'Delete Chargeable Expense'
            )),
            array('Billing', array(
                'Browse',
                'Read',
                'Edit',
                'Add'
            )),
            array('Paralegal Assignment Sheet', array(
                'Browse'
            )),
        );

        foreach ($models as $model){
            foreach ($model[1] as $permission){
                $string = strtolower($model[0]); // Replaces all spaces with hyphens.
                $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
                $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
                $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

                $string2 = strtolower($permission); // Replaces all spaces with hyphens.
                $string2 = str_replace(' ', '-', $string2); // Replaces all spaces with hyphens.
                $string2 = preg_replace('/[^A-Za-z0-9\-]/', '', $string2); // Removes special chars.
                $string2 = preg_replace('/-+/', '-', $string2); // Replaces multiple hyphens with single one.

                Permission::create(['name' => $string2.'-'.$string, 'display_name' => $permission, 'table_name' => $string, 'table_display_name' => $model[0]]);
            }
        }

        foreach ($roles as $role){
            $string = strtolower($role); // Replaces all spaces with hyphens.
            $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
            $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
            $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

            $type = Role::create(['name' => $string, 'display_name' => $role]);
            if($string === 'admin'){
                $type->givePermissionTo(Permission::all());
            }
        }

        $userList = array(
            array('Admin', 'admin@gmail.com', 'pacific'),
            array('Office Clerk', 'clerk@gmail.com', 'pacific'),
        );

        foreach($userList as $data){
            $user = new User();
            $user->name = $data[0];
            $user->email = $data[1];
            $user->password = bcrypt($data[2]);
            if($user->save()){
                switch ($user->email) {
                    case 'clerk@gmail.com':
                        $user->assignRole('office-clerk');
                        break;
                    default:
                        $user->assignRole('admin');
                }
            }
        }

    }
}
