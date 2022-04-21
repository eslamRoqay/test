<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            // inboxes permissions
            [
                'path' => 'inboxes',
                'name' => 'read-inboxes',
                'display_name' => 'read inboxes',
                'description' => 'عرض الرسائل',
            ],
            [
                'path' => 'inboxes',
                'name' => 'delete-inboxes',
                'display_name' => 'delete inboxes',
                'description' => 'حذف الرسائل',
            ],

            // users permissions
            [
                'path' => 'users',
                'name' => 'read-users',
                'display_name' => 'read users',
                'description' => 'عرض العملاء',
            ],
            [
                'path' => 'users',
                'name' => 'update-users',
                'display_name' => 'update users',
                'description' => 'تعديل العملاء',
            ],
            [
                'path' => 'users',
                'name' => 'create-users',
                'display_name' => 'create users',
                'description' => 'إضافة العملاء',
            ],
            [
                'path' => 'users',
                'name' => 'delete-users',
                'display_name' => 'delete users',
                'description' => 'حذف العملاء',
            ],
            // settings permissions
            [
                'path' => 'settings',
                'name' => 'read-settings',
                'display_name' => 'read settings',
                'description' => 'عرض الاعدادت',
            ],
            [
                'path' => 'settings',
                'name' => 'update-settings',
                'display_name' => 'update settings',
                'description' => 'تعديل الاعدادت',
            ],

            // roles permissions
            [
                'path' => 'roles',
                'name' => 'read-roles',
                'display_name' => 'read roles',
                'description' => 'عرض الصلاحيات',
            ],
            [
                'path' => 'roles',
                'name' => 'update-roles',
                'display_name' => 'update roles',
                'description' => 'تعديل الصلاحيات',
            ],
            [
                'path' => 'roles',
                'name' => 'create-roles',
                'display_name' => 'create roles',
                'description' => 'إضافة الصلاحيات',
            ],
            [
                'path' => 'roles',
                'name' => 'delete-roles',
                'display_name' => 'delete roles',
                'description' => 'حذف الصلاحيات',
            ],
            // admins permissions
            [
                'path' => 'admins',
                'name' => 'read-admins',
                'display_name' => 'read admins',
                'description' => 'عرض المديرين',
            ],
            [
                'path' => 'admins',
                'name' => 'update-admins',
                'display_name' => 'update admins',
                'description' => 'تعديل المديرين',
            ],
            [
                'path' => 'admins',
                'name' => 'create-admins',
                'display_name' => 'create admins',
                'description' => 'إضافة المديرين',
            ],
            [
                'path' => 'admins',
                'name' => 'delete-admins',
                'display_name' => 'delete admins',
                'description' => 'حذف المديرين',
            ],


        ];
        foreach ($data as $get) {
            Permission::updateOrCreate($get);
        }
    }
}
