<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competitor;

class CompetitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competitors = Competitor::getMoroccoCompetitors();
        
        foreach ($competitors as $competitorData) {
            Competitor::create($competitorData);
        }
    }
}