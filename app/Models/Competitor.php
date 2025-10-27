<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'website',
        'logo_url',
        'description',
        'market_share',
        'rating',
        'total_reviews',
        'price_range_min',
        'price_range_max',
        'fleet_size',
        'cities_covered',
        'special_features',
        'strengths',
        'weaknesses',
        'commission_rate',
        'is_active'
    ];

    protected $casts = [
        'market_share' => 'decimal:2',
        'rating' => 'decimal:2',
        'price_range_min' => 'decimal:2',
        'price_range_max' => 'decimal:2',
        'fleet_size' => 'integer',
        'cities_covered' => 'array',
        'special_features' => 'array',
        'strengths' => 'array',
        'weaknesses' => 'array',
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Données des principaux concurrents au Maroc
     */
    public static function getMoroccoCompetitors()
    {
        return [
            [
                'name' => 'Hertz Maroc',
                'website' => 'https://www.hertz.ma',
                'logo_url' => '/images/competitors/hertz.png',
                'description' => 'Leader mondial de la location de véhicules avec une forte présence au Maroc',
                'market_share' => 25.5,
                'rating' => 4.2,
                'total_reviews' => 2847,
                'price_range_min' => 180.00,
                'price_range_max' => 850.00,
                'fleet_size' => 2500,
                'cities_covered' => ['Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Agadir', 'Tanger'],
                'special_features' => [
                    'Service 24h/24',
                    'Assurance complète incluse',
                    'Livraison à domicile',
                    'Fleet premium'
                ],
                'strengths' => [
                    'Reconnaissance mondiale',
                    'Service client premium',
                    'Fleet diversifiée',
                    'Couverture nationale'
                ],
                'weaknesses' => [
                    'Prix élevés',
                    'Processus de réservation complexe',
                    'Frais cachés'
                ],
                'commission_rate' => 12.0
            ],
            [
                'name' => 'Avis Maroc',
                'website' => 'https://www.avis.ma',
                'logo_url' => '/images/competitors/avis.png',
                'description' => 'Agence internationale avec une forte implantation locale',
                'market_share' => 22.3,
                'rating' => 4.1,
                'total_reviews' => 2156,
                'price_range_min' => 160.00,
                'price_range_max' => 780.00,
                'fleet_size' => 2200,
                'cities_covered' => ['Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Agadir', 'Meknès'],
                'special_features' => [
                    'Programme de fidélité',
                    'Véhicules récents',
                    'Service client multilingue',
                    'Options GPS'
                ],
                'strengths' => [
                    'Prix compétitifs',
                    'Service fiable',
                    'Fleet bien entretenue',
                    'Réservation en ligne simple'
                ],
                'weaknesses' => [
                    'Disponibilité limitée en haute saison',
                    'Frais d\'annulation élevés',
                    'Service client parfois lent'
                ],
                'commission_rate' => 10.5
            ],
            [
                'name' => 'Europcar Maroc',
                'website' => 'https://www.europcar.ma',
                'logo_url' => '/images/competitors/europcar.png',
                'description' => 'Agence européenne avec focus sur le marché marocain',
                'market_share' => 18.7,
                'rating' => 4.0,
                'total_reviews' => 1892,
                'price_range_min' => 140.00,
                'price_range_max' => 720.00,
                'fleet_size' => 1800,
                'cities_covered' => ['Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Agadir', 'Tanger', 'Oujda'],
                'special_features' => [
                    'Économie de carburant',
                    'Véhicules écologiques',
                    'Service express',
                    'Options famille'
                ],
                'strengths' => [
                    'Prix attractifs',
                    'Véhicules économiques',
                    'Couverture étendue',
                    'Options flexibles'
                ],
                'weaknesses' => [
                    'Fleet limitée en premium',
                    'Service client basique',
                    'Disponibilité variable'
                ],
                'commission_rate' => 8.5
            ],
            [
                'name' => 'Budget Maroc',
                'website' => 'https://www.budget.ma',
                'logo_url' => '/images/competitors/budget.png',
                'description' => 'Spécialiste des prix bas avec une approche économique',
                'market_share' => 15.2,
                'rating' => 3.8,
                'total_reviews' => 1456,
                'price_range_min' => 120.00,
                'price_range_max' => 650.00,
                'fleet_size' => 1500,
                'cities_covered' => ['Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Agadir'],
                'special_features' => [
                    'Prix bas garantis',
                    'Options économiques',
                    'Réservation simple',
                    'Service express'
                ],
                'strengths' => [
                    'Prix très compétitifs',
                    'Processus simple',
                    'Options basiques fiables',
                    'Pas de frais cachés'
                ],
                'weaknesses' => [
                    'Fleet limitée',
                    'Service client minimal',
                    'Véhicules parfois anciens',
                    'Couverture limitée'
                ],
                'commission_rate' => 6.0
            ],
            [
                'name' => 'Sixt Maroc',
                'website' => 'https://www.sixt.ma',
                'logo_url' => '/images/competitors/sixt.png',
                'description' => 'Agence premium allemande avec focus sur la qualité',
                'market_share' => 12.8,
                'rating' => 4.3,
                'total_reviews' => 987,
                'price_range_min' => 200.00,
                'price_range_max' => 1200.00,
                'fleet_size' => 1200,
                'cities_covered' => ['Casablanca', 'Rabat', 'Marrakech', 'Fès', 'Agadir', 'Tanger'],
                'special_features' => [
                    'Fleet premium',
                    'Service haut de gamme',
                    'Véhicules de luxe',
                    'Concierge service'
                ],
                'strengths' => [
                    'Qualité premium',
                    'Service exceptionnel',
                    'Véhicules de luxe',
                    'Expérience client premium'
                ],
                'weaknesses' => [
                    'Prix très élevés',
                    'Fleet limitée',
                    'Disponibilité restreinte',
                    'Marché niche'
                ],
                'commission_rate' => 15.0
            ],
            [
                'name' => 'Local Car Rental',
                'website' => 'https://www.localcarrental.ma',
                'logo_url' => '/images/competitors/local.png',
                'description' => 'Agence locale avec focus sur le marché marocain',
                'market_share' => 5.5,
                'rating' => 3.9,
                'total_reviews' => 623,
                'price_range_min' => 100.00,
                'price_range_max' => 500.00,
                'fleet_size' => 800,
                'cities_covered' => ['Casablanca', 'Rabat', 'Marrakech', 'Fès'],
                'special_features' => [
                    'Prix locaux',
                    'Service personnalisé',
                    'Flexibilité',
                    'Connaissance locale'
                ],
                'strengths' => [
                    'Prix très compétitifs',
                    'Service personnalisé',
                    'Flexibilité locale',
                    'Connaissance du marché'
                ],
                'weaknesses' => [
                    'Fleet limitée',
                    'Service client basique',
                    'Couverture restreinte',
                    'Processus manuel'
                ],
                'commission_rate' => 4.0
            ]
        ];
    }

    /**
     * Compare avec ToubCar
     */
    public function compareWithToubCar()
    {
        return [
            'price_advantage' => $this->calculatePriceAdvantage(),
            'service_advantage' => $this->calculateServiceAdvantage(),
            'feature_advantage' => $this->calculateFeatureAdvantage(),
            'overall_score' => $this->calculateOverallScore(),
            'recommendations' => $this->getRecommendations()
        ];
    }

    private function calculatePriceAdvantage()
    {
        // ToubCar prix moyen estimé
        $toubcar_avg_price = 300; // MAD par jour
        $competitor_avg_price = ($this->price_range_min + $this->price_range_max) / 2;
        
        $advantage = (($competitor_avg_price - $toubcar_avg_price) / $toubcar_avg_price) * 100;
        
        return [
            'percentage' => round($advantage, 2),
            'direction' => $advantage > 0 ? 'higher' : 'lower',
            'toubcar_price' => $toubcar_avg_price,
            'competitor_price' => round($competitor_avg_price, 2)
        ];
    }

    private function calculateServiceAdvantage()
    {
        $toubcar_rating = 4.5; // Rating estimé ToubCar
        $rating_diff = $this->rating - $toubcar_rating;
        
        return [
            'rating_difference' => round($rating_diff, 2),
            'toubcar_rating' => $toubcar_rating,
            'competitor_rating' => $this->rating,
            'advantage' => $rating_diff > 0 ? 'competitor' : 'toubcar'
        ];
    }

    private function calculateFeatureAdvantage()
    {
        $toubcar_features = [
            'Service 24h/24',
            'Assurance incluse',
            'Livraison gratuite',
            'Réservation en ligne',
            'Support multilingue',
            'Fleet diversifiée',
            'Prix transparents'
        ];
        
        $common_features = array_intersect($toubcar_features, $this->special_features);
        $feature_coverage = (count($common_features) / count($toubcar_features)) * 100;
        
        return [
            'coverage_percentage' => round($feature_coverage, 2),
            'common_features' => $common_features,
            'unique_toubcar_features' => array_diff($toubcar_features, $this->special_features),
            'unique_competitor_features' => array_diff($this->special_features, $toubcar_features)
        ];
    }

    private function calculateOverallScore()
    {
        $price_score = $this->calculatePriceAdvantage();
        $service_score = $this->calculateServiceAdvantage();
        $feature_score = $this->calculateFeatureAdvantage();
        
        // Scoring algorithm
        $overall_score = 50; // Base score
        
        // Price impact (40% weight)
        if ($price_score['direction'] === 'lower') {
            $overall_score += 20; // Advantage for ToubCar
        } else {
            $overall_score -= min(20, $price_score['percentage'] / 10); // Penalty for higher prices
        }
        
        // Service impact (35% weight)
        if ($service_score['advantage'] === 'toubcar') {
            $overall_score += 15;
        } else {
            $overall_score -= min(15, abs($service_score['rating_difference']) * 10);
        }
        
        // Feature impact (25% weight)
        $overall_score += ($feature_score['coverage_percentage'] - 50) / 10;
        
        return [
            'score' => max(0, min(100, round($overall_score, 2))),
            'grade' => $this->getGrade($overall_score),
            'breakdown' => [
                'price_impact' => $price_score,
                'service_impact' => $service_score,
                'feature_impact' => $feature_score
            ]
        ];
    }

    private function getGrade($score)
    {
        if ($score >= 90) return 'A+';
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B+';
        if ($score >= 60) return 'B';
        if ($score >= 50) return 'C+';
        if ($score >= 40) return 'C';
        return 'D';
    }

    private function getRecommendations()
    {
        // Avoid recursion by using the underlying calculators directly
        $priceAdvantage = $this->calculatePriceAdvantage();
        $serviceAdvantage = $this->calculateServiceAdvantage();
        $featureAdvantage = $this->calculateFeatureAdvantage();

        $recommendations = [];

        // Price recommendations
        if ($priceAdvantage['direction'] === 'higher') {
            $recommendations[] = [
                'type' => 'price',
                'priority' => 'high',
                'message' => "Avantage prix: ToubCar est " . abs($priceAdvantage['percentage']) . "% moins cher",
                'action' => 'Mettre en avant nos prix compétitifs'
            ];
        }

        // Service recommendations
        if ($serviceAdvantage['advantage'] === 'toubcar') {
            $recommendations[] = [
                'type' => 'service',
                'priority' => 'medium',
                'message' => "Avantage service: Rating supérieur de " . abs($serviceAdvantage['rating_difference']) . " points",
                'action' => 'Communiquer sur la qualité de notre service'
            ];
        }

        // Feature recommendations
        if ($featureAdvantage['coverage_percentage'] < 70) {
            $recommendations[] = [
                'type' => 'features',
                'priority' => 'high',
                'message' => "Améliorer les fonctionnalités pour concurrencer",
                'action' => 'Ajouter les fonctionnalités manquantes: ' . implode(', ', $featureAdvantage['unique_competitor_features'])
            ];
        }

        return $recommendations;
    }
}
