<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\Activite;
use App\Models\AnneeSpirituelle;
use App\Models\CategorieActivite;
use App\Models\Groupe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardGroupeController extends DashboardController
{

    public function index(Request $request)
    {
        $groupes = Groupe::get();
        $nombreMembres = User::where('role', '!=', Constantes::ROLE_ADMIN)->get()->count();

        return view('dashboard.dashboard-groupe-index', compact('groupes', 'nombreMembres'));
    }

    public function dashboard(Request $request)
    {
        if (!$request->groupe) abort(404);

        $groupe = Groupe::with(['sousZone.zone'])->find($request->groupe);
        if (!$groupe) abort(404);

        $anneeSpirituelle = AnneeSpirituelle::where('etat', Constantes::ETAT_ACTIF)->first()
            ?? AnneeSpirituelle::orderBy('date_debut', 'desc')->first();

        // Single query for active members
        $users = $groupe->getMembres();
        $nombreMembres = $users->count();
        $userIds = $users->pluck('id')->toArray();

        // Resolve zone / sous-zone context
        $groupeContext = $this->resolveGroupeContext($groupe);

        // Load all in-scope activities in one query
        $allActivities = $this->loadAllGroupeActivities($groupe->id, $groupeContext);

        $categorieActivitesArr = CategorieActivite::all();

        // Summary table with ratio
        $categorieActivites = $this->buildGroupeCategoryStats(
            $categorieActivitesArr, $allActivities, $userIds, $nombreMembres
        );

        // Detailed per-activity stats with ratio
        $categorieActivitesDetails = $this->getGroupeCategoryDetails(
            $categorieActivitesArr, $userIds, $allActivities
        );

        // Per-member breakdown (equivalent of zone's "Par groupe" tab)
        $categorieActivitesMembres = $this->getMembresAttendanceStats(
            $categorieActivitesArr, $allActivities, $users
        );

        return view('dashboard.dashboard-groupe', compact(
            'categorieActivites',
            'categorieActivitesDetails',
            'categorieActivitesMembres',
            'groupe',
            'nombreMembres',
            'users'
        ));
    }

    /**
     * Resolve zone and sous-zone IDs from the groupe (uses eager-loaded relations; no extra queries).
     */
    private function resolveGroupeContext(Groupe $groupe): array
    {
        $sousZone = $groupe->sousZone;
        return [
            'sous_zone_id' => $sousZone?->id,
            'zone_id'      => $sousZone?->zone_id,
        ];
    }

    /**
     * Load all activities that are relevant for this group in a single query:
     * Régionale (all), Zonale (same zone), Sous-zonale (same sous-zone), Groupe (this group only).
     */
    private function loadAllGroupeActivities(int $groupeId, array $context): \Illuminate\Database\Eloquent\Collection
    {
        return Activite::where(function ($q) use ($groupeId, $context) {
            $q->where('type_activite', Constantes::ACTIVITE_REGIONALE)
              ->orWhere(function ($q2) use ($context) {
                  $q2->where('type_activite', Constantes::ACTIVITE_ZONALE)
                     ->where('zone_id', $context['zone_id']);
              })
              ->orWhere(function ($q2) use ($context) {
                  $q2->where('type_activite', Constantes::ACTIVITE_SOUS_ZONALE)
                     ->where('sous_zone_id', $context['sous_zone_id']);
              })
              ->orWhere(function ($q2) use ($groupeId) {
                  $q2->where('type_activite', Constantes::ACTIVITE_GROUPE)
                     ->where('groupe_id', $groupeId);
              });
        })->get();
    }

    /**
     * Build summary stats per category — including ratio — using a single aggregated participation query.
     */
    private function buildGroupeCategoryStats($categorieActivites, $allActivities, $userIds, $nombreMembres): array
    {
        $allActivityIds = $allActivities->pluck('id')->toArray();

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

        $activitiesByCategory = $allActivities->groupBy('categorie_activite_id');
        $result = [];

        foreach ($categorieActivites as $categorieActivite) {
            $catActivities    = $activitiesByCategory->get($categorieActivite->id, collect());
            $totalActivites   = $catActivities->count();

            if ($categorieActivite->periodicite == Constantes::PERIODE_ANNUELLE && $totalActivites > 0) {
                $totalActivites = 1;
            }

            $totalParticipations = $catActivities->sum(fn ($a) => $participationCounts[$a->id] ?? 0);
            $denominator         = $nombreMembres * $totalActivites;

            $result[$categorieActivite->nom] = [
                'nombreActivite'      => $totalActivites,
                'nombreParticipation' => $totalParticipations,
                'stats'               => $denominator > 0 ? round($totalParticipations * 100 / $denominator, 2) : 0,
                'ratio'               => $totalParticipations . '/' . $denominator,
            ];
        }

        return $result;
    }

    /**
     * Detailed per-activity stats for each category — with ratio per activity.
     * Single aggregated participation query; no per-activity DB calls.
     */
    private function getGroupeCategoryDetails($categorieActivites, $userIds, $allActivities): array
    {
        $totalMembers   = count($userIds);
        $allActivityIds = $allActivities->pluck('id')->toArray();

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

        $activitiesByCategory = $allActivities->groupBy('categorie_activite_id');
        $details = [];

        foreach ($categorieActivites as $categorieActivite) {
            $activities      = $activitiesByCategory->get($categorieActivite->id, collect());
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
                    $count      = $participationCounts[$activity->id] ?? 0;
                    $percentage = $totalMembers > 0 ? round($count * 100 / $totalMembers, 2) : 0;

                    $categoryDetails[$activity->nom] = [
                        'nombreParticipation' => $count,
                        'nombreActivite'      => 1,
                        'stats'               => $percentage,
                        'ratio'               => $count . '/' . $totalMembers,
                    ];
                }
            }

            $details[$categorieActivite->nom] = $categoryDetails;
        }

        return $details;
    }

    /**
     * Per-member attendance breakdown per category.
     * Equivalent of zone's "Par groupe" tab — shows who attended each activity.
     * Single participation query; no per-user DB calls.
     */
    private function getMembresAttendanceStats($categorieActivites, $allActivities, $users): array
    {
        $stats        = [];
        $allActivityIds = $allActivities->pluck('id')->toArray();
        $allUserIds     = $users->pluck('id')->toArray();

        // One query: build [activite_id][user_id] => true map
        $participationMap = [];
        if (!empty($allActivityIds) && !empty($allUserIds)) {
            DB::table('participations')
                ->whereIn('activite_id', $allActivityIds)
                ->whereIn('user_id', $allUserIds)
                ->whereNull('deleted_at')
                ->select('activite_id', 'user_id')
                ->get()
                ->each(function ($row) use (&$participationMap) {
                    $participationMap[$row->activite_id][$row->user_id] = true;
                });
        }

        $activitiesByCategory = $allActivities->groupBy('categorie_activite_id');

        foreach ($categorieActivites as $categorieActivite) {
            $activities  = $activitiesByCategory->get($categorieActivite->id, collect());
            $memberStats = [];

            foreach ($users as $user) {
                $userRow = [
                    'nom'                  => $user->nom . ' ' . $user->prenom,
                    'total_participations' => 0,
                    'total_activities'     => $activities->count(),
                    'activities'           => [],
                ];

                foreach ($activities as $activity) {
                    $participated = isset($participationMap[$activity->id][$user->id]);
                    $userRow['activities'][$activity->nom] = $participated;
                    if ($participated) $userRow['total_participations']++;
                }

                if ($activities->isEmpty()) {
                    $userRow['activities'][''] = false;
                }

                $totalActs  = $userRow['total_activities'];
                $totalParts = $userRow['total_participations'];
                $userRow['ratio'] = $totalParts . '/' . $totalActs;
                $userRow['stats'] = $totalActs > 0 ? round($totalParts * 100 / $totalActs, 2) : 0;

                $memberStats[] = $userRow;
            }

            // Sort by most participations descending
            usort($memberStats, fn ($a, $b) => $b['total_participations'] - $a['total_participations']);

            $stats[$categorieActivite->nom] = $memberStats;
        }

        return $stats;
    }

    protected function getPourcentageActivite($nombreActivite, $nombreParticipation, $nombreMembres)
    {
        return $nombreActivite > 0 ? $nombreParticipation * 100 / ($nombreMembres * $nombreActivite) : 0;
    }
}

