<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use App\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PageSeeder;
use Database\Seeders\PermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(ToothSeeder::class);
        $this->call(StatusSeeder::class);

        // User::factory(10)->create();

        // $user = User::factory()->create([
        //     'name' => 'admin User',
        //     'email' => 'admin@example.com',
        // ]);

        // $user = User::get()->first();

        // $this->call(PageSeeder::class);
        // $this->call(PermissionSeeder::class);

        // $page = Page::find(1);
        // $permissions = Permission::all();
        // $page->permissions()->attach($permissions);

        // $user->pages()->attach(2, ['permission_id' => 1]);
        // $user->pages()->attach(2, ['permission_id' => 2]);
        // $user->pages()->attach(2, ['permission_id' => 3]);
        // $user->pages()->attach(2, ['permission_id' => 4]);

        // $user->pages()->syncWithoutDetaching([1 => ['permission_id' => 1]]);
        // $user->pages()->syncWithoutDetaching([1 => ['permission_id' => 2]]);
        // $user->pages()->syncWithoutDetaching([1 => ['permission_id' => 4]]);
    }
}
