<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        // users
        $admin = User::firstOrCreate(
            ['email' => 'furina@example.com'],
            ['name' => 'Atmin', 'password' => 'furina']
        );
        $admin->syncRoles([$adminRole]);

        $student = User::firstOrCreate(
            ['email' => 'hutao@example.com'],
            ['name' => 'Hu Tao', 'password' => 'hutao']
        );
        $student->syncRoles([$studentRole]);

        // sample courses
        Course::firstOrCreate(['code' => 'CS101'], ['name' => 'Intro to CS', 'credits' => 3]);
        Course::firstOrCreate(['code' => 'MA201'], ['name' => 'Calculus II', 'credits' => 4]);
    }
}
