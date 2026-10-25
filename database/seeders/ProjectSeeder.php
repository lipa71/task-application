<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            'Task application',
            'Job board',
            'API application'
        ];

        $statuses = ['in_progress', 'done', 'canceled'];

        foreach ($projects as $projectName) {
            $project = Project::create(['name' => $projectName]);

            for ($i = 1; $i <= 10; $i++) {
                Task::create([
                    'project_id' => $project->id,
                    'name' => 'Task ' . $i . ' for ' . $projectName,
                    'description' => 'Detailed description for task number ' . $i . ' inside ' . $projectName . '.',
                    'status' => $statuses[array_rand($statuses)],
                    'priority' => $i,
                    'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                ]);
            }
        }
    }
}
