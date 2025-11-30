<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\Activite;
use App\Models\AnneeSpirituelle;
use App\Models\CategorieActivite;
use App\Models\Groupe;
use App\Models\Pays;
use App\Models\SousZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardZoneController extends DashboardController
{

    public function index(Request $request)
    {
        $zones = Zone::get();
        $nombreMembres = User::where('role', '!=', Constantes::ROLE_ADMIN)->get()->count();

        return view('dashboard.dashboard-zone-index', compact('zones', 'nombreMembres'));
    }

    public function dashboard(Request $request)
    {
        if (!$request->zone) {
            abort(404);
        }

        $zone = Zone::find($request->zone);

        if (!$zone) {
            abort(404);
        }

        // Get current spiritual year
        $anneeSpirituelle = AnneeSpirituelle::where('etat', Constantes::ETAT_ACTIF)->first();
        
        if (!$anneeSpirituelle) {
            // Fallback to most recent if no active year
            $anneeSpirituelle = AnneeSpirituelle::orderBy('date_debut', 'desc')->first();
        }

        $users = $zone->getMembres();
        $nombreMembres = $users->count();

        // Get user IDs for filtering participations
        $userIds = $users->pluck('id')->toArray();

        // Get all category activities
        $categorieActivitesArr = CategorieActivite::all();
        $categorieActivites = [];

        foreach ($categorieActivitesArr as $categorieActivite) {
            $stats = $this->getCategoryAttendanceStats(
                $categorieActivite, 
                $zone->id, 
                $userIds, 
                $nombreMembres,
                $anneeSpirituelle ? $anneeSpirituelle->id : null
            );
            
            $categorieActivites[$categorieActivite->nom] = [
                "nombreActivite" => $stats['totalActivities'],
                "nombreParticipation" => $stats['totalParticipations'],
                "stats" => $stats['percentage'],
                "ratio" => $stats['totalParticipations'] . '/' . ($nombreMembres * $stats['totalActivities'])
            ];
        }

        $categorieActivitesDetails = $this->getCategoryAttendanceDetails(
            $categorieActivitesArr, 
            $zone->id, 
            $userIds,
            $anneeSpirituelle ? $anneeSpirituelle->id : null
        );

        $categorieActivitesGroupes = $this->getGroupAttendanceStats(
            $categorieActivitesArr,
            $zone->id,
            $anneeSpirituelle ? $anneeSpirituelle->id : null
        );

        return view('dashboard.dashboard-zone', compact('categorieActivitesDetails', 'categorieActivites', 'categorieActivitesGroupes', 'zone', 'nombreMembres'));
    }

    /**
     * Get attendance statistics for a category of activities
     * 
     * @param CategorieActivite $categorieActivite
     * @param int $zoneId
     * @param array $userIds
     * @param int $nombreMembres
     * @param int|null $anneeSpirituelleId
     * @return array
     */
    private function getCategoryAttendanceStats($categorieActivite, $zoneId, $userIds, $nombreMembres, $anneeSpirituelleId = null)
    {
        // Get activities for this category filtered by zone
        $activitiesQuery = Activite::where('categorie_activite_id', $categorieActivite->id);

        // TODO: Filter by spiritual year when annee_spirituelle column is added to activites table
        // if ($anneeSpirituelleId) {
        //     $activitiesQuery->where('annee_spirituelle', $anneeSpirituelleId);
        // }

        // Filter activities based on type and zone
        switch ($categorieActivite->type_activite) {
            case Constantes::ACTIVITE_REGIONALE:
                $activitiesQuery->where('type_activite', Constantes::ACTIVITE_REGIONALE);
                break;
            
            case Constantes::ACTIVITE_ZONALE:
                $activitiesQuery->where('type_activite', Constantes::ACTIVITE_ZONALE)
                    ->where('zone_id', $zoneId);
                break;
            
            case Constantes::ACTIVITE_SOUS_ZONALE:
                // Get all sous-zones in this zone
                $sousZoneIds = SousZone::where('zone_id', $zoneId)->pluck('id')->toArray();
                $activitiesQuery->where('type_activite', Constantes::ACTIVITE_SOUS_ZONALE)
                    ->whereIn('sous_zone_id', $sousZoneIds);
                break;
            
            case Constantes::ACTIVITE_PAYS:
                // Get all pays in this zone through sous-zones
                $sousZoneIds = SousZone::where('zone_id', $zoneId)->pluck('id')->toArray();
                $paysIds = Pays::whereIn('sous_zone_id', $sousZoneIds)->pluck('id')->toArray();
                $activitiesQuery->where('type_activite', Constantes::ACTIVITE_PAYS)
                    ->whereIn('pays_id', $paysIds);
                break;
            
            case Constantes::ACTIVITE_GROUPE:
                // Get all groupes in this zone through sous-zones
                $sousZoneIds = SousZone::where('zone_id', $zoneId)->pluck('id')->toArray();
                $groupeIds = Groupe::whereIn('sous_zone_id', $sousZoneIds)->pluck('id')->toArray();
                $activitiesQuery->where('type_activite', Constantes::ACTIVITE_GROUPE)
                    ->whereIn('groupe_id', $groupeIds);
                break;
            
            default:
                // No activities for unknown types
                return [
                    'totalActivities' => 0,
                    'totalParticipations' => 0,
                    'percentage' => 0
                ];
        }
        
        Log::info('activitiesQuery', [$activitiesQuery]);
        $activities = $activitiesQuery->get();
        $totalActivites = $activities->count();

        // Handle annual periodicity - count as 1 activity if any exist
        if ($categorieActivite->periodicite == Constantes::PERIODE_ANNUELLE && $totalActivites > 0) {
            $totalActivites = 1;
        }

        // Get total participations for these activities by zone users
        $activityIds = $activities->pluck('id')->toArray();
        Log::info('activites', [$activities]);
        $nombreParticipation = 0;
        if (!empty($activityIds) && !empty($userIds)) {
            $nombreParticipation = DB::table('participations')
                ->whereIn('activite_id', $activityIds)
                ->whereIn('user_id', $userIds)
                ->whereNull('deleted_at')
                ->count();
        }

        // Calculate percentage
        $percentage = ($nombreMembres * $totalActivites) > 0 
            ? round($nombreParticipation * 100 / ($nombreMembres * $totalActivites), 2) 
            : 0;

        return [
            'totalActivities' => $totalActivites,
            'totalParticipations' => $nombreParticipation,
            'percentage' => $percentage
        ];
    }

    /**
     * Get detailed attendance statistics for all categories
     * 
     * @param \Illuminate\Database\Eloquent\Collection $categorieActivites
     * @param int $zoneId
     * @param array $userIds
     * @param int|null $anneeSpirituelleId
     * @return array
     */
    private function getCategoryAttendanceDetails($categorieActivites, $zoneId, $userIds, $anneeSpirituelleId = null)
    {
        $details = [];

        foreach ($categorieActivites as $categorieActivite) {
            // Get activities for this category filtered by zone
            $activitiesQuery = Activite::where('categorie_activite_id', $categorieActivite->id)
                ->with(['participations' => function ($query) use ($userIds) {
                    $query->whereIn('user_id', $userIds);
                }]);

            // TODO: Filter by spiritual year when annee_spirituelle column is added to activites table
            // if ($anneeSpirituelleId) {
            //     $activitiesQuery->where('annee_spirituelle', $anneeSpirituelleId);
            // }

            // Filter activities based on type and zone (same logic as stats function)
            switch ($categorieActivite->type_activite) {
                case Constantes::ACTIVITE_REGIONALE:
                    $activitiesQuery->where('type_activite', Constantes::ACTIVITE_REGIONALE);
                    break;
                
                case Constantes::ACTIVITE_ZONALE:
                    $activitiesQuery->where('type_activite', Constantes::ACTIVITE_ZONALE)
                        ->where('zone_id', $zoneId);
                    break;
                
                case Constantes::ACTIVITE_SOUS_ZONALE:
                    $sousZoneIds = SousZone::where('zone_id', $zoneId)->pluck('id')->toArray();
                    $activitiesQuery->where('type_activite', Constantes::ACTIVITE_SOUS_ZONALE)
                        ->whereIn('sous_zone_id', $sousZoneIds);
                    break;
                
                case Constantes::ACTIVITE_PAYS:
                    $sousZoneIds = SousZone::where('zone_id', $zoneId)->pluck('id')->toArray();
                    $paysIds = Pays::whereIn('sous_zone_id', $sousZoneIds)->pluck('id')->toArray();
                    $activitiesQuery->where('type_activite', Constantes::ACTIVITE_PAYS)
                        ->whereIn('pays_id', $paysIds);
                    break;
                
                case Constantes::ACTIVITE_GROUPE:
                    $sousZoneIds = SousZone::where('zone_id', $zoneId)->pluck('id')->toArray();
                    $groupeIds = Groupe::whereIn('sous_zone_id', $sousZoneIds)->pluck('id')->toArray();
                    $activitiesQuery->where('type_activite', Constantes::ACTIVITE_GROUPE)
                        ->whereIn('groupe_id', $groupeIds);
                    break;
                
                default:
                    // For categories without a specific type, show empty data
                    $details[$categorieActivite->nom] = [
                        '' => [
                            'nombreParticipation' => 0,
                            'nombreActivite' => 0,
                            'stats' => 0,
                            'ratio' => '0/' . count($userIds)
                        ]
                    ];
                    continue 2;
            }

            $activities = $activitiesQuery->get();

            // Build detailed stats per activity
            $categoryDetails = [];
            
            // If no activities exist for this category, show empty entry
            if ($activities->isEmpty()) {
                $categoryDetails[''] = [
                    'nombreParticipation' => 0,
                    'nombreActivite' => 0,
                    'stats' => 0,
                    'ratio' => '0/' . count($userIds)
                ];
            } else {
                foreach ($activities as $activity) {
                    $participationCount = $activity->participations->count();
                    $totalMembers = count($userIds);
                    $percentage = $totalMembers > 0 
                        ? round($participationCount * 100 / $totalMembers, 2) 
                        : 0;
                    
                    $categoryDetails[$activity->nom] = [
                        'nombreParticipation' => $participationCount,
                        'nombreActivite' => 1, // Each activity is counted as 1
                        'stats' => $percentage,
                        'ratio' => $participationCount . '/' . $totalMembers
                    ];
                }
            }

            $details[$categorieActivite->nom] = $categoryDetails;
        }

        return $details;
    }

    /**
     * Calculate activity attendance percentage
     * 
     * @param int $nombreActivite
     * @param int $nombreParticipation
     * @param int $nombreMembres
     * @return float
     */
    protected function getPourcentageActivite($nombreActivite, $nombreParticipation, $nombreMembres)
    {
        $res = $nombreActivite > 0 ? $nombreParticipation * 100 / ($nombreMembres * $nombreActivite) : 0;

        return $res;
    }

    /**
     * Get attendance statistics by group for each activity category
     * 
     * @param \Illuminate\Database\Eloquent\Collection $categorieActivites
     * @param int $zoneId
     * @param int|null $anneeSpirituelleId
     * @return array
     */
    private function getGroupAttendanceStats($categorieActivites, $zoneId, $anneeSpirituelleId = null)
    {
        $stats = [];

        // Get all groups in this zone through sous-zones
        $sousZoneIds = SousZone::where('zone_id', $zoneId)->pluck('id')->toArray();
        $groupes = Groupe::whereIn('sous_zone_id', $sousZoneIds)
            ->with(['users' => function($query) {
                $query->where('groupe_user.actif', Constantes::ETAT_ACTIF);
            }])
            ->orderBy('nom_groupe')
            ->get();

        foreach ($categorieActivites as $categorieActivite) {
            // Get activities for this category filtered by zone
            $activitiesQuery = Activite::where('categorie_activite_id', $categorieActivite->id);

            // TODO: Filter by spiritual year when annee_spirituelle column is added to activites table
            // if ($anneeSpirituelleId) {
            //     $activitiesQuery->where('annee_spirituelle', $anneeSpirituelleId);
            // }

            // Filter activities based on type and zone (same logic as other methods)
            switch ($categorieActivite->type_activite) {
                case Constantes::ACTIVITE_REGIONALE:
                    $activitiesQuery->where('type_activite', Constantes::ACTIVITE_REGIONALE);
                    break;
                
                case Constantes::ACTIVITE_ZONALE:
                    $activitiesQuery->where('type_activite', Constantes::ACTIVITE_ZONALE)
                        ->where('zone_id', $zoneId);
                    break;
                
                case Constantes::ACTIVITE_SOUS_ZONALE:
                    $activitiesQuery->where('type_activite', Constantes::ACTIVITE_SOUS_ZONALE)
                        ->whereIn('sous_zone_id', $sousZoneIds);
                    break;
                
                case Constantes::ACTIVITE_PAYS:
                    $paysIds = Pays::whereIn('sous_zone_id', $sousZoneIds)->pluck('id')->toArray();
                    $activitiesQuery->where('type_activite', Constantes::ACTIVITE_PAYS)
                        ->whereIn('pays_id', $paysIds);
                    break;
                
                case Constantes::ACTIVITE_GROUPE:
                    $groupeIds = $groupes->pluck('id')->toArray();
                    $activitiesQuery->where('type_activite', Constantes::ACTIVITE_GROUPE)
                        ->whereIn('groupe_id', $groupeIds);
                    break;
                
                default:
                    // For categories without a specific type, skip
                    $stats[$categorieActivite->nom] = [];
                    continue 2;
            }

            $activities = $activitiesQuery->get();

            // Build matrix: groups vs activities
            $categoryStats = [];
            
            foreach ($groupes as $groupe) {
                $groupUserIds = $groupe->users->pluck('id')->toArray();
                $groupMembersCount = count($groupUserIds);
                
                $groupRow = [
                    'groupe_name' => $groupe->nom_groupe,
                    'total_members' => $groupMembersCount,
                    'total_participations' => 0, // Track total for sorting
                    'activities' => []
                ];

                if ($activities->isEmpty()) {
                    $groupRow['activities'][''] = [
                        'ratio' => '0/' . $groupMembersCount,
                        'percentage' => 0,
                        'participations' => 0
                    ];
                } else {
                    foreach ($activities as $activity) {
                        // Count participations for this group in this activity
                        $participationCount = 0;
                        if (!empty($groupUserIds)) {
                            $participationCount = DB::table('participations')
                                ->where('activite_id', $activity->id)
                                ->whereIn('user_id', $groupUserIds)
                                ->whereNull('deleted_at')
                                ->count();
                        }

                        $percentage = $groupMembersCount > 0 
                            ? round($participationCount * 100 / $groupMembersCount, 2) 
                            : 0;

                        $groupRow['activities'][$activity->nom] = [
                            'ratio' => $participationCount . '/' . $groupMembersCount,
                            'percentage' => $percentage,
                            'participations' => $participationCount
                        ];
                        
                        // Add to total participations
                        $groupRow['total_participations'] += $participationCount;
                    }
                }

                $categoryStats[] = $groupRow;
            }

            // Sort groups by total participations (descending - highest first)
            usort($categoryStats, function($a, $b) {
                return $b['total_participations'] - $a['total_participations'];
            });

            $stats[$categorieActivite->nom] = $categoryStats;
        }

        return $stats;
    }
}
