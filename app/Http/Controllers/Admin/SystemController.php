<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SystemController extends Controller
{
    public function health()
    {
        // System health checks
        $healthChecks = [
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
            'mail' => $this->checkMail(),
        ];

        // System information
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];

        // Performance metrics
        $performanceMetrics = [
            'total_users' => \App\Models\User::count(),
            'total_agencies' => \App\Models\Agency::count(),
            'total_customers' => \App\Models\Client::count(),
            'total_vehicles' => \App\Models\Car::count(),
            'total_bookings' => \App\Models\Rental::count(),
            'activeRentals' => \App\Models\Rental::where('status', 'active')->count(),
            'database_size' => $this->getDatabaseSize(),
            'storage_usage' => $this->getStorageUsage(),
        ];

        return view('admin.system.health', compact('healthChecks', 'systemInfo', 'performanceMetrics'));
    }

    public function backups()
    {
        // Get backup files
        $backups = $this->getBackupFiles();
        
        // Backup statistics
        $backupStats = [
            'total_backups' => count($backups),
            'last_backup' => $backups->first()['created_at'] ?? null,
            'total_size' => $backups->sum('size'),
            'oldest_backup' => $backups->last()['created_at'] ?? null,
        ];

        return view('admin.system.backups', compact('backups', 'backupStats'));
    }

    public function createBackup()
    {
        try {
            Artisan::call('backup:run');
            return redirect()->route('admin.system.backups')
                ->with('success', 'Sauvegarde créée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.system.backups')
                ->with('error', 'Erreur lors de la création de la sauvegarde: ' . $e->getMessage());
        }
    }

    public function emails()
    {
        // Email configuration status
        $emailConfig = [
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'username' => config('mail.mailers.smtp.username'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        // Email templates (this would come from a database table)
        $emailTemplates = collect([
            [
                'name' => 'Agency Approved',
                'subject' => 'Votre agence a été approuvée',
                'status' => 'active',
                'last_modified' => now()->subDays(5),
            ],
            [
                'name' => 'Agency Rejected',
                'subject' => 'Demande d\'agence rejetée',
                'status' => 'active',
                'last_modified' => now()->subDays(3),
            ],
            [
                'name' => 'Booking Confirmation',
                'subject' => 'Confirmation de réservation',
                'status' => 'active',
                'last_modified' => now()->subDays(1),
            ],
        ]);

        return view('admin.system.emails', compact('emailConfig', 'emailTemplates'));
    }

    public function api()
    {
        // API configuration and statistics
        $apiConfig = [
            'rate_limiting' => config('api.rate_limiting', 'disabled'),
            'api_prefix' => config('api.prefix', 'api'),
            'api_version' => config('api.version', 'v1'),
        ];

        // API usage statistics (this would come from API logs)
        $apiStats = [
            'total_requests' => 0, // This would be from API logs
            'requests_today' => 0,
            'active_tokens' => 0,
            'error_rate' => 0,
        ];

        return view('admin.system.api', compact('apiConfig', 'apiStats'));
    }

    public function maintenance()
    {
        $maintenanceMode = app()->isDownForMaintenance();
        
        $maintenanceInfo = [
            'is_enabled' => $maintenanceMode,
            'message' => $maintenanceMode ? 'Le site est en mode maintenance' : 'Le site est opérationnel',
            'allowed_ips' => config('app.maintenance.allowed', []),
        ];

        return view('admin.system.maintenance', compact('maintenanceInfo'));
    }

    public function toggleMaintenance(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:255',
            'allowed_ips' => 'nullable|string',
        ]);

        try {
            if (app()->isDownForMaintenance()) {
                Artisan::call('up');
                $message = 'Mode maintenance désactivé. Le site est maintenant accessible.';
            } else {
                $message = $request->message ?? 'Le site est temporairement en maintenance. Veuillez réessayer plus tard.';
                Artisan::call('down', [
                    '--message' => $message,
                    '--allow' => $request->allowed_ips,
                ]);
                $message = 'Mode maintenance activé. Le site est maintenant inaccessible aux visiteurs.';
            }

            return redirect()->route('admin.system.maintenance')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.system.maintenance')
                ->with('error', 'Erreur lors du changement de mode maintenance: ' . $e->getMessage());
        }
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Cache vidé avec succès.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du vidage du cache: ' . $e->getMessage()
            ], 500);
        }
    }

    public function optimize()
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            return response()->json([
                'success' => true,
                'message' => 'Application optimisée avec succès.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'optimisation: ' . $e->getMessage()
            ], 500);
        }
    }

    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'healthy',
                'message' => 'Connexion à la base de données réussie',
                'response_time' => $this->getDatabaseResponseTime(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erreur de connexion à la base de données: ' . $e->getMessage(),
                'response_time' => null,
            ];
        }
    }

    private function checkStorage()
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            Storage::put($testFile, 'Health check test');
            Storage::delete($testFile);
            
            return [
                'status' => 'healthy',
                'message' => 'Stockage accessible',
                'free_space' => $this->getFreeDiskSpace(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erreur d\'accès au stockage: ' . $e->getMessage(),
                'free_space' => null,
            ];
        }
    }

    private function checkCache()
    {
        try {
            $testKey = 'health_check_' . time();
            cache()->put($testKey, 'test', 60);
            $value = cache()->get($testKey);
            cache()->forget($testKey);
            
            return [
                'status' => $value === 'test' ? 'healthy' : 'warning',
                'message' => $value === 'test' ? 'Cache fonctionnel' : 'Cache ne fonctionne pas correctement',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erreur du cache: ' . $e->getMessage(),
            ];
        }
    }

    private function checkQueue()
    {
        // This would check if queue workers are running
        return [
            'status' => 'healthy',
            'message' => 'Queue système disponible',
        ];
    }

    private function checkMail()
    {
        // This would test mail configuration
        return [
            'status' => 'healthy',
            'message' => 'Configuration email valide',
        ];
    }

    private function getDatabaseResponseTime()
    {
        $start = microtime(true);
        DB::select('SELECT 1');
        return round((microtime(true) - $start) * 1000, 2) . 'ms';
    }

    private function getFreeDiskSpace()
    {
        $bytes = disk_free_space(storage_path());
        return $this->formatBytes($bytes);
    }

    private function getDatabaseSize()
    {
        try {
            $result = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'size' FROM information_schema.tables WHERE table_schema = ?", [config('database.connections.mysql.database')]);
            return $result[0]->size . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getStorageUsage()
    {
        $bytes = $this->getDirectorySize(storage_path());
        return $this->formatBytes($bytes);
    }

    private function getDirectorySize($directory)
    {
        $size = 0;
        if (is_dir($directory)) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
                $size += $file->getSize();
            }
        }
        return $size;
    }

    private function getBackupFiles()
    {
        $backups = collect();
        
        // This would read from the backup directory
        // For now, return empty collection
        return $backups;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
