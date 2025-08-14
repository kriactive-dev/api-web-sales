<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Course;
use App\Models\Question;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class ChatBotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $main = Question::create([
            'title' => 'Menu Principal',
            'message' => 'Olá! Sou o assistente virtual da UCM. Em que posso ajudar?'
        ]);

        $cursos = Question::create([
            'title' => 'Informações sobre Cursos',
            'message' => 'O que deseja saber sobre os cursos?'
        ]);

        Answer::create([
            'question_id' => $main->id,
            'label' => '1️⃣ Informações sobre Cursos',
            'next_question_id' => $cursos->id
        ]);

        Answer::insert([
            [
                'question_id' => $cursos->id,
                'label' => 'Lista de cursos disponíveis',
                'final_message' => 'Veja a lista de cursos no site oficial da UCM: https://ucm.ac.mz'
            ],
            [
                'question_id' => $cursos->id,
                'label' => 'Mudança de curso',
                'final_message' => 'A mudança de curso deve ser solicitada na secretaria com justificativa.'
            ]
        ]);

        $courses = [
            ['name' => 'Engenharia Civil', 'code' => 'ENGCIV', 'department' => 'Engenharia'],
            ['name' => 'Engenharia Elétrica', 'code' => 'ENGELET', 'department' => 'Engenharia'],
            ['name' => 'Engenharia Mecânica', 'code' => 'ENGMEC', 'department' => 'Engenharia'],
            ['name' => 'Engenharia de Produção', 'code' => 'ENGPROD', 'department' => 'Engenharia'],
            ['name' => 'Ciência da Computação', 'code' => 'CCOMP', 'department' => 'Tecnologia'],
            ['name' => 'Sistemas de Informação', 'code' => 'SINFO', 'department' => 'Tecnologia'],
            ['name' => 'Engenharia de Software', 'code' => 'ENGSW', 'department' => 'Tecnologia'],
            ['name' => 'Matemática', 'code' => 'MAT', 'department' => 'Exatas'],
            ['name' => 'Física', 'code' => 'FIS', 'department' => 'Exatas'],
            ['name' => 'Administração', 'code' => 'ADM', 'department' => 'Negócios'],
            ['name' => 'Economia', 'code' => 'ECO', 'department' => 'Negócios'],
            ['name' => 'Ciências Contábeis', 'code' => 'CONT', 'department' => 'Negócios'],
            ['name' => 'Direito', 'code' => 'DIR', 'department' => 'Direito'],
            ['name' => 'Jornalismo', 'code' => 'JOR', 'department' => 'Comunicação'],
            ['name' => 'Publicidade e Propaganda', 'code' => 'PUB', 'department' => 'Comunicação'],
            ['name' => 'Relações Internacionais', 'code' => 'RELINT', 'department' => 'Humanas'],
            ['name' => 'Medicina', 'code' => 'MED', 'department' => 'Saúde'],
            ['name' => 'Enfermagem', 'code' => 'ENF', 'department' => 'Saúde'],
            ['name' => 'Farmácia', 'code' => 'FARM', 'department' => 'Saúde'],
            ['name' => 'Odontologia', 'code' => 'ODONTO', 'department' => 'Saúde'],
            ['name' => 'Fisioterapia', 'code' => 'FISIO', 'department' => 'Saúde'],
            ['name' => 'Psicologia', 'code' => 'PSI', 'department' => 'Saúde'],
            ['name' => 'Nutrição', 'code' => 'NUT', 'department' => 'Saúde'],
            ['name' => 'História', 'code' => 'HIS', 'department' => 'Humanas'],
            ['name' => 'Geografia', 'code' => 'GEO', 'department' => 'Humanas'],
            ['name' => 'Filosofia', 'code' => 'FIL', 'department' => 'Humanas'],
            ['name' => 'Sociologia', 'code' => 'SOC', 'department' => 'Humanas'],
            ['name' => 'Pedagogia', 'code' => 'PED', 'department' => 'Educação'],
            ['name' => 'Letras – Português', 'code' => 'LETPOR', 'department' => 'Linguagens'],
            ['name' => 'Letras – Inglês', 'code' => 'LETING', 'department' => 'Linguagens'],
            ['name' => 'Arquitetura e Urbanismo', 'code' => 'ARQURB', 'department' => 'Artes e Arquitetura'],
            ['name' => 'Design Gráfico', 'code' => 'DESGRA', 'department' => 'Artes'],
            ['name' => 'Educação Física', 'code' => 'EDFIS', 'department' => 'Educação'],
            ['name' => 'Biomedicina', 'code' => 'BIOMED', 'department' => 'Saúde'],
            ['name' => 'Análise e Desenvolvimento de Sistemas', 'code' => 'ADS', 'department' => 'Tecnologia']
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        $faker = Faker::create('pt_PT');

        $academicStatuses = ['Regular', 'Trancado', 'Formado', 'Cancelado'];
        $financialStatuses = ['Regular', 'Em atraso', 'Isento'];

        for ($i = 0; $i < 20; $i++) {
            Student::create([
                'name' => $faker->name,
                'student_number' => '9012025' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'academic_status' => $faker->randomElement($academicStatuses),
                'financial_status' => $faker->randomElement($financialStatuses),
            ]);
        }
    }
}
