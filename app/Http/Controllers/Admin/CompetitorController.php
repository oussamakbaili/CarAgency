<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competitor;
use App\Models\Car;
use App\Models\Rental;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CompetitorController extends Controller
{
    /**
     * Dashboard de comparaison avec la concurrence
     */
    public function index()
    {
        $competitors = Competitor::getMoroccoCompetitors();
        
        // Statistiques ToubCar (avec valeurs par défaut sûres pour éviter les erreurs 500)
        $toubcarStats = [
            'total_cars' => (int) (Car::count() ?? 0),
            'total_agencies' => (int) (\App\Models\Agency::where('status', 'approved')->count() ?? 0),
            'total_rentals' => (int) (Rental::whereIn('status', ['active', 'completed'])->count() ?? 0),
            'monthly_revenue' => (float) (Rental::whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price') ?? 0),
            'average_rating' => 4.5, // À calculer depuis les reviews
            'price_range' => [
                'min' => (float) (Car::min('price_per_day') ?? 0),
                'max' => (float) (Car::max('price_per_day') ?? 0),
                'avg' => (float) (Car::avg('price_per_day') ?? 0)
            ]
        ];

        // Comparaisons détaillées
        $comparisons = [];
        foreach ($competitors as $competitor) {
            $comp = new Competitor();
            $comp->fill($competitor);
            $comparisons[] = [
                'competitor' => $competitor,
                'analysis' => $comp->compareWithToubCar()
            ];
        }

        // Analyse de marché
        $marketAnalysis = $this->getMarketAnalysis($competitors, $toubcarStats);

        return view('admin.competitors.index', compact(
            'competitors', 
            'toubcarStats', 
            'comparisons', 
            'marketAnalysis'
        ));
    }

    /**
     * Analyse détaillée d'un concurrent
     */
    public function show($competitorName)
    {
        $competitors = Competitor::getMoroccoCompetitors();
        $competitor = collect($competitors)->firstWhere('name', $competitorName);
        
        if (!$competitor) {
            abort(404, 'Concurrent non trouvé');
        }

        $comp = new Competitor();
        $comp->fill($competitor);
        $analysis = $comp->compareWithToubCar();

        // Données historiques (simulées)
        $historicalData = $this->getHistoricalData($competitorName);

        return view('admin.competitors.show', compact('competitor', 'analysis', 'historicalData'));
    }

    /**
     * Rapport de positionnement concurrentiel
     */
    public function report()
    {
        $competitors = Competitor::getMoroccoCompetitors();
        $toubcarStats = $this->getToubCarStats();
        
        $report = [
            'market_position' => $this->calculateMarketPosition($competitors),
            'competitive_advantages' => $this->identifyAdvantages($competitors),
            'threats' => $this->identifyThreats($competitors),
            'opportunities' => $this->identifyOpportunities($competitors),
            'recommendations' => $this->generateRecommendations($competitors)
        ];

        return view('admin.competitors.report', compact('report', 'toubcarStats'));
    }

    /**
     * Analyse SWOT
     */
    public function swot()
    {
        $competitors = Competitor::getMoroccoCompetitors();
        
        $swot = [
            'strengths' => [
                'Prix compétitifs',
                'Service client réactif',
                'Plateforme moderne',
                'Couverture nationale',
                'Transparence des prix',
                'Processus de réservation simple'
            ],
            'weaknesses' => [
                'Marque moins connue',
                'Fleet plus petite que les leaders',
                'Moins d\'expérience internationale',
                'Budget marketing limité'
            ],
            'opportunities' => [
                'Croissance du marché marocain',
                'Digitalisation du secteur',
                'Demande pour des prix transparents',
                'Expansion vers de nouvelles villes',
                'Partenariats avec des hôtels/agences de voyage'
            ],
            'threats' => [
                'Arrivée de nouveaux concurrents',
                'Concentration du marché',
                'Instabilité économique',
                'Changements réglementaires',
                'Innovation technologique des concurrents'
            ]
        ];

        return view('admin.competitors.swot', compact('swot', 'competitors'));
    }

    /**
     * Benchmarking des prix
     */
    public function pricingBenchmark()
    {
        $competitors = Competitor::getMoroccoCompetitors();
        
        // Prix par catégorie de véhicule
        $categories = \App\Models\Category::with('cars')->get();
        $pricingBenchmark = [];
        
        foreach ($categories as $category) {
            $toubcarAvgPrice = $category->cars()->avg('price_per_day');
            
            $categoryBenchmark = [
                'category' => $category->name,
                'toubcar_price' => round($toubcarAvgPrice, 2),
                'competitors' => []
            ];
            
            foreach ($competitors as $competitor) {
                // Estimation basée sur la fourchette de prix du concurrent
                $estimatedPrice = ($competitor['price_range_min'] + $competitor['price_range_max']) / 2;
                $priceDifference = (($estimatedPrice - $toubcarAvgPrice) / $toubcarAvgPrice) * 100;
                
                $categoryBenchmark['competitors'][] = [
                    'name' => $competitor['name'],
                    'price' => round($estimatedPrice, 2),
                    'difference_percentage' => round($priceDifference, 2),
                    'advantage' => $priceDifference < 0 ? 'toubcar' : 'competitor'
                ];
            }
            
            $pricingBenchmark[] = $categoryBenchmark;
        }

        return view('admin.competitors.pricing', compact('pricingBenchmark', 'competitors'));
    }

    private function getMarketAnalysis($competitors, $toubcarStats)
    {
        $totalMarketShare = collect($competitors)->sum('market_share');
        $toubcarEstimatedShare = max(0, 100 - $totalMarketShare);
        
        return [
            'total_market_size' => 1500000000, // Estimation en MAD
            'toubcar_share' => $toubcarEstimatedShare,
            'top_3_competitors' => collect($competitors)->sortByDesc('market_share')->take(3),
            'market_concentration' => $this->calculateConcentration($competitors),
            'growth_opportunities' => $this->identifyGrowthOpportunities($competitors)
        ];
    }

    private function calculateMarketPosition($competitors)
    {
        $sortedCompetitors = collect($competitors)->sortByDesc('market_share');
        $toubcarPosition = 4; // Position estimée
        
        return [
            'current_position' => $toubcarPosition,
            'target_position' => 2,
            'gap_to_target' => $sortedCompetitors->slice(1, 1)->first(),
            'movement_potential' => 'upward'
        ];
    }

    private function identifyAdvantages($competitors)
    {
        return [
            [
                'type' => 'price',
                'description' => 'Prix 15-25% moins chers que la concurrence',
                'impact' => 'high',
                'evidence' => 'Comparaison des tarifs moyens'
            ],
            [
                'type' => 'technology',
                'description' => 'Plateforme moderne et intuitive',
                'impact' => 'medium',
                'evidence' => 'Interface utilisateur avancée'
            ],
            [
                'type' => 'transparency',
                'description' => 'Prix transparents sans frais cachés',
                'impact' => 'high',
                'evidence' => 'Feedback client positif'
            ]
        ];
    }

    private function identifyThreats($competitors)
    {
        return [
            [
                'type' => 'market_share',
                'description' => 'Hertz et Avis contrôlent 47.8% du marché',
                'severity' => 'high',
                'mitigation' => 'Cibler les niches sous-servies'
            ],
            [
                'type' => 'pricing',
                'description' => 'Budget et Local Car Rental proposent des prix très bas',
                'severity' => 'medium',
                'mitigation' => 'Se différencier par la qualité de service'
            ],
            [
                'type' => 'brand',
                'description' => 'Marques internationales mieux reconnues',
                'severity' => 'medium',
                'mitigation' => 'Investir dans le marketing et la réputation'
            ]
        ];
    }

    private function identifyOpportunities($competitors)
    {
        return [
            [
                'type' => 'digital_gap',
                'description' => 'Opportunité dans la digitalisation du secteur',
                'potential' => 'high',
                'action' => 'Développer des fonctionnalités digitales avancées'
            ],
            [
                'type' => 'price_transparency',
                'description' => 'Marché demande plus de transparence',
                'potential' => 'high',
                'action' => 'Communiquer sur nos prix transparents'
            ],
            [
                'type' => 'service_quality',
                'description' => 'Améliorer la qualité de service vs concurrence',
                'potential' => 'medium',
                'action' => 'Formation équipes et processus'
            ]
        ];
    }

    private function generateRecommendations($competitors)
    {
        return [
            [
                'category' => 'Pricing',
                'priority' => 'high',
                'recommendation' => 'Maintenir notre avantage prix de 15-25%',
                'action' => 'Optimiser les coûts opérationnels',
                'timeline' => '3 mois'
            ],
            [
                'category' => 'Marketing',
                'priority' => 'high',
                'recommendation' => 'Renforcer la notoriété de marque',
                'action' => 'Campagne marketing ciblée',
                'timeline' => '6 mois'
            ],
            [
                'category' => 'Technology',
                'priority' => 'medium',
                'recommendation' => 'Développer des fonctionnalités uniques',
                'action' => 'Innovation dans l\'expérience utilisateur',
                'timeline' => '12 mois'
            ],
            [
                'category' => 'Service',
                'priority' => 'medium',
                'recommendation' => 'Améliorer la qualité de service',
                'action' => 'Formation équipes et processus',
                'timeline' => '6 mois'
            ]
        ];
    }

    private function calculateConcentration($competitors)
    {
        $top3Share = collect($competitors)->sortByDesc('market_share')->take(3)->sum('market_share');
        return [
            'top3_concentration' => $top3Share,
            'level' => $top3Share > 60 ? 'high' : ($top3Share > 40 ? 'medium' : 'low')
        ];
    }

    private function identifyGrowthOpportunities($competitors)
    {
        return [
            'underserved_segments' => ['Véhicules électriques', 'Location longue durée', 'Véhicules premium'],
            'geographic_expansion' => ['Nouvelles villes', 'Zones rurales'],
            'digital_opportunities' => ['App mobile', 'IA pour recommandations', 'Blockchain pour contrats']
        ];
    }

    private function getToubCarStats()
    {
        return [
            'market_share' => 2.0, // Estimation
            'monthly_revenue' => Rental::whereIn('status', ['active', 'completed'])
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('total_price'),
            'growth_rate' => 25.0, // Estimation
            'customer_satisfaction' => 4.5
        ];
    }

    private function getHistoricalData($competitorName)
    {
        // Données simulées - dans un vrai système, cela viendrait d'une base de données
        return [
            'market_share_evolution' => [
                ['year' => 2022, 'share' => 24.0],
                ['year' => 2023, 'share' => 25.2],
                ['year' => 2024, 'share' => 25.5]
            ],
            'pricing_evolution' => [
                ['year' => 2022, 'avg_price' => 320],
                ['year' => 2023, 'avg_price' => 340],
                ['year' => 2024, 'avg_price' => 350]
            ]
        ];
    }
}
