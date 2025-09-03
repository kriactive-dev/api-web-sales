<?php

namespace Database\Seeders;

use App\Models\StudentUcm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create('pt_PT');

        $academicStatuses = ['Regular', 'Trancado', 'Formado', 'Cancelado'];
        $financialStatuses = ['Regular', 'Em atraso', 'Isento'];

        for ($i = 0; $i < 50; $i++) {
            StudentUcm::create([
                'name' => $faker->name,
                'code' => '2025' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'situacao_academica' => $faker->randomElement($academicStatuses),
                'situacao_financeira' => $faker->randomElement($financialStatuses),
            ]);
        }
    }
}
