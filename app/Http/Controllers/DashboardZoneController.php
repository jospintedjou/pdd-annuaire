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

        // Pre-compute zone context IDs once to avoid repeated sub-queries
        $zoneContext = $this->resolveZoneContext($zone->id);

        // Load all zone-scoped activities once — shared by all stat methods (avoids N+1)
        $allZoneActivities = $this->loadAllZoneActivities($zone->id, $zoneContext);

        // Get all category activities
        $categorieActivitesArr = CategorieActivite::all();

        $categorieActivites = $this->buildAllCategoryAttendanceStats(
            $categorieActivitesArr,
            $allZoneActivities,
            $userIds,
            $nombreMembres
        );

        $categorieActivitesDetails = $this->getCategoryAttendanceDetails(
            $categorieActivitesArr,
            $userIds,
            $allZoneActivities,
            $anneeSpirituelle ? $anneeSpirituelle->id : null
        );

        $categorieActivitesGroupes = $this->getGroupsAttendanceStats(
            $categorieActivitesArr,
            $allZoneActivities,
            $zoneContext,
            $anneeSpirituelle ? $anneeSpirituelle->id : null
        );

        return view('dashboard.dashboard-zone', compact('categorieActivitesDetails', 'categorieActivites', 'categorieActivitesGroupes', 'zone', 'nombreMembres'));
    }

    /**
     * Resolve zone-related IDs once to avoid repeated sub-queries.
     *
     * @param int $zoneId
     * @return array{sousZoneIds: array, paysIds: array, groupeIds: array}
     */
    private function resolveZoneContext(int $zoneId): array
    {
        $sousZoneIds = SousZone::where('zone_id', $zoneId)->pluck('id')->toArray();
        $paysIds     = Pays::whereIn('sous_zone_id', $sousZoneIds)->pluck('id')->toArray();
        $groupeIds   = Groupe::whereIn('sous_zone_id', $sousZoneIds)->pluck('id')->toArray();

        return [
            'sousZoneIds' => $sousZoneIds,
            'paysIds'     => $paysIds,
            'groupeIds'   => $groupeIds,
        ];
    }

    /**
     * Load all activities in scope for the given zone in a single query.
     * The result is shared across all stat methods to avoid repeated queries.
     *
     * @param int $zoneId
     * @param array $zoneContext
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function loadAllZoneActivities(int $zoneId, array $zoneContext): \Illuminate\Database\Eloquent\Collection
    {
        return Activite::where(function ($query) use ($zoneId, $zoneContext) {
            $query->where('type_activite', Constantes::ACTIVITE_REGIONALE)
                ->orWhere(function ($q) use ($zoneId) {
                    $q->where('type_activite', Constantes::ACTIVITE_ZONALE)
                      ->where('zone_id', $zoneId);
                })
                ->orWhere(function ($q) use ($zoneContext) {
                    $q->where('type_activite', Constantes::ACTIVITE_SOUS_ZONALE)
                      ->whereIn('sous_zone_id', $zoneContext['sousZoneIds']);
                })
                ->orWhere(function ($q) use ($zoneContext) {
                    $q->where('type_activite', Constantes::ACTIVITE_PAYS)
                      ->whereIn('pays_id', $zoneContext['paysIds']);
                })
                ->orWhere(function ($q) use ($zoneContext) {
                    $q->where('type_activite', Constantes::ACTIVITE_GROUPE)
                      ->whereIn('groupe_id', $zoneContext['groupeIds']);
                });
        })->get();
    }

    /**
     * Build attendance summary stats for all categories using pre-loaded activity data.
     * Fires exactly 1 query for participation counts (aggregated), no per-category queries.
     *
     * @param \Illuminate\Database\Eloquent\Collection $categorieActivites
     * @param \Illuminate\Database\Eloquent\Collection $allZoneActivities
     * @param array $userIds
     * @param int $nombreMembres
     * @return array
     */
    private function buildAllCategoryAttendanceStats($categorieActivites, $allZoneActivities, $userIds, $nombreMembres): array
    {
        $allActivityIds = $allZoneActivities->pluck('id')->toArray();

        // One aggregated participation count query for all activities at once
        $participationCounts = [];
        if (!empty($allActivityIds) && !empty($userIds)) {
            DB::table('participations')
                ->whereIn('activite_id', $allActivityIds)
                ->whereIn('user_id', $userIds)
                ->whereNull('deleted_at')
                ->selectRaw('activite_id, COUNT(*) as count')
                ->groupBy('activite_id')
                ->get()
                ->each(function ($row) use (&$participationCounts) {
                    $participationCounts[$row->activite_id] = $row->count;
                });
        }

        $activitiesByCategory = $allZoneActivities->groupBy('categorie_activite_id');
        $result = [];

        foreach ($categorieActivites as $categorieActivite) {
            $catActivities = $activitiesByCategory->get($categorieActivite->id, collect());
            $totalActivites = $catActivities->count();

            if ($categorieActivite->periodicite == Constantes::PERIODE_ANNUELLE && $totalActivites > 0) {
                $totalActivites = 1;
            }

            $totalParticipations = $catActivities->sum(function ($activity) use ($participationCounts) {
                return $participationCounts[$activity->id] ?? 0;
            });

            $percentage = ($nombreMembres * $totalActivites) > 0
                ? round($totalParticipations * 100 / ($nombreMembres * $totalActivites), 2)
                : 0;

            $result[$categorieActivite->nom] = [
                'nombreActivite'      => $totalActivites,
                'nombreParticipation' => $totalParticipations,
                'stats'               => $percentage,
                'ratio'               => $totalParticipations . '/' . ($nombreMembres * $totalActivites),
            ];
        }

        return $result;
    }

    /**
     * Get detailed attendance statistics for all categories.
     * Uses pre-loaded activities and a single aggregated participation query.
     *
     * @param \Illuminate\Database\Eloquent\Collection $categorieActivites
     * @param array $userIds
     * @param \Illuminate\Database\Eloquent\Collection $allZoneActivities
     * @param int|null $anneeSpirituelleId
     * @return array
     */
    private function getCategoryAttendanceDetails($categorieActivites, $userIds, $allZoneActivities, $anneeSpirituelleId = null)
    {
        $details = [];
        $totalMembers   = count($userIds);
        $allActivityIds = $allZoneActivities->pluck('id')->toArray();

        // Load all participation counts in one aggregated query
        $participationCounts = [];
        if (!empty($allActivityIds) && !empty($userIds)) {
            DB::table('participations')
                ->whereIn('activite_id', $allActivityIds)
                ->whereIn('user_id', $userIds)
                ->whereNull('deleted_at')
                ->selectRaw('activite_id, COUNT(*) as count')
                ->groupBy('activite_id')
                ->get()
                ->each(function ($row) use (&$participationCounts) {
                    $participationCounts[$row->activite_id] = $row->count;
                });
        }

        $activitiesByCategory = $allZoneActivities->groupBy('categorie_activite_id');

        foreach ($categorieActivites as $categorieActivite) {
            if (!in_array($categorieActivite->type_activite, [
                Constantes::ACTIVITE_REGIONALE,
                Constantes::ACTIVITE_ZONALE,
                Constantes::ACTIVITE_SOUS_ZONALE,
                Constantes::ACTIVITE_PAYS,
                Constantes::ACTIVITE_GROUPE,
            ])) {
                $details[$categorieActivite->nom] = [
                    '' => [
                        'nombreParticipation' => 0,
                        'nombreActivite'      => 0,
                        'stats'               => 0,
                        'ratio'               => '0/' . $totalMembers,
                    ]
                ];
                continue;
            }

            $activities    = $activitiesByCategory->get($categorieActivite->id, collect());
            $categoryDetails = [];

            if ($activities->isEmpty()) {
                $categoryDetails[''] = [
                    'nombreParticipation' => 0,
                    'nombreActivite'      => 0,
                    'stats'               => 0,
                    'ratio'               => '0/' . $totalMembers,
                ];
            } else {
                foreach ($activities as $activity) {
                    $participationCount = $participationCounts[$activity->id] ?? 0;
                    $percentage = $totalMembers > 0
                        ? round($participationCount * 100 / $totalMembers, 2)
                        : 0;

                    $categoryDetails[$activity->nom] = [
                        'nombreParticipation' => $participationCount,
                        'nombreActivite'      => 1,
                        'stats'               => $percentage,
                        'ratio'               => $participationCount . '/' . $totalMembers,
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
     * Get attendance statistics by group for each activity category.
     * Uses pre-loaded activities and a single participation map query — no queries inside loops.
     *
     * @param \Illuminate\Database\Eloquent\Collection $categorieActivites
     * @param \Illuminate\Database\Eloquent\Collection $allZoneActivities
     * @param array $zoneContext
     * @param int|null $anneeSpirituelleId
     * @return array
     */
    private function getGroupsAttendanceStats($categorieActivites, $allZoneActivities, array $zoneContext, $anneeSpirituelleId = null)
    {
        $stats = [];

        // Load all groups with their active users (2 queries via eager loading)
        $groupes = Groupe::whereIn('sous_zone_id', $zoneContext['sousZoneIds'])
            ->with(['users' => function ($query) {
                $query->where('groupe_user.actif', Constantes::ETAT_ACTIF);
            }])
            ->orderBy('nom_groupe')
            ->get();

        // Collect all user IDs across all groups for a single participation query
        $allGroupUserIds = $groupes
            ->flatMap(fn ($g) => $g->users->pluck('id'))
            ->unique()
            ->values()
            ->toArray();

        $allActivityIds = $allZoneActivities->pluck('id')->toArray();

        // Load ALL participations in one query — build map: [activite_id][user_id] => true
        $participationMap = [];
        if (!empty($allActivityIds) && !empty($allGroupUserIds)) {
            DB::table('participations')
                ->whereIn('activite_id', $allActivityIds)
                ->whereIn('user_id', $allGroupUserIds)
                ->whereNull('deleted_at')
                ->select('activite_id', 'user_id')
                ->get()
                ->each(function ($row) use (&$participationMap) {
                    $participationMap[$row->activite_id][$row->user_id] = true;
                });
        }

        $activitiesByCategory = $allZoneActivities->groupBy('categorie_activite_id');

        foreach ($categorieActivites as $categorieActivite) {
            if (!in_array($categorieActivite->type_activite, [
                Constantes::ACTIVITE_REGIONALE,
                Constantes::ACTIVITE_ZONALE,
                Constantes::ACTIVITE_SOUS_ZONALE,
                Constantes::ACTIVITE_PAYS,
                Constantes::ACTIVITE_GROUPE,
            ])) {
                $stats[$categorieActivite->nom] = [];
                continue;
            }

            $activities    = $activitiesByCategory->get($categorieActivite->id, collect());
            $categoryStats = [];

            foreach ($groupes as $groupe) {
                $groupUserIds      = $groupe->users->pluck('id')->toArray();
                $groupMembersCount = count($groupUserIds);
                $groupUserIdSet    = array_flip($groupUserIds);

                $groupRow = [
                    'groupe_name'          => $groupe->nom_groupe,
                    'total_members'        => $groupMembersCount,
                    'total_participations' => 0,
                    'activities'           => [],
                ];

                if ($activities->isEmpty()) {
                    $groupRow['activities'][''] = [
                        'ratio'          => '0/' . $groupMembersCount,
                        'percentage'     => 0,
                        'participations' => 0,
                    ];
                } else {
                    foreach ($activities as $activity) {
                        // Count via PHP array intersect — no DB query
                        $activityParticipants = $participationMap[$activity->id] ?? [];
                        $participationCount   = count(array_intersect_key($activityParticipants, $groupUserIdSet));

                        $percentage = $groupMembersCount > 0
                            ? round($participationCount * 100 / $groupMembersCount, 2)
                            : 0;

                        $groupRow['activities'][$activity->nom] = [
                            'ratio'          => $participationCount . '/' . $groupMembersCount,
                            'percentage'     => $percentage,
                            'participations' => $participationCount,
                        ];

                        $groupRow['total_participations'] += $participationCount;
                    }
                }

                $categoryStats[] = $groupRow;
            }

            usort($categoryStats, function ($a, $b) {
                return $b['total_participations'] - $a['total_participations'];
            });

            $stats[$categorieActivite->nom] = $categoryStats;
        }

        return $stats;
    }
}
