<?php

namespace App\Http\Controllers;

use App\Constantes;
use App\Models\Activite;
use App\Models\CategorieActivite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardMembreController extends DashboardController
{

    public function index(Request $request)
    {
        $users = User::where('role', '!=', Constantes::ROLE_ADMIN)->get();
        $nombreMembres = $users->count();

        return view('dashboard.dashboard-user-index', compact('users', 'nombreMembres'));
    }

    public function dashboard(Request $request)
    {
        if (!$request->user) abort(404);

        $user = User::find($request->user);
        if (!$user) abort(404);

        $groupe   = $user->groupeActif();
        $sousZone = $user->sousZone();
        $zone     = $user->zone();

        if (!$groupe) abort(404);

        $context = [
            'groupe_id'    => $groupe->id,
            'sous_zone_id' => $sousZone?->id,
            'zone_id'      => $zone?->id,
        ];

        // Load all in-scope activities in one query
        $allActivities = $this->loadAllMemberActivities($context);

        $categorieActivitesArr = CategorieActivite::all();
        $allActivityIds = $allActivities->pluck('id')->toArray();

        // Single aggregated query: participations by this user across all in-scope activities
        $userParticipations = [];
        if (!empty($allActivityIds)) {
            DB::table('participations')
                ->where('user_id', $user->id)
                ->whereIn('activite_id', $allActivityIds)
                ->whereNull('deleted_at')
                ->selectRaw('activite_id, COUNT(*) as count')
                ->groupBy('activite_id')
                ->get()
                ->each(function ($row) use (&$userParticipations) {
                    $userParticipations[$row->activite_id] = $row->count;
                });
        }

        $categorieActivites        = $this->buildMemberCategoryStats($categorieActivitesArr, $allActivities, $userParticipations);
        $categorieActivitesDetails = $this->buildMemberCategoryDetails($categorieActivitesArr, $allActivities, $userParticipations);

        return view('dashboard.dashboard-user', compact(
            'categorieActivites', 'categorieActivitesDetails', 'user', 'groupe', 'sousZone', 'zone'
        ));
    }

    /**
     * Load all activities in scope for this member (Régionale + Zonale + Sous-zonale + Groupe).
     */
    private function loadAllMemberActivities(array $context): \Illuminate\Database\Eloquent\Collection
    {
        return Activite::where(function ($q) use ($context) {
            $q->where('type_activite', Constantes::ACTIVITE_REGIONALE)
              ->orWhere(function ($q2) use ($context) {
                  $q2->where('type_activite', Constantes::ACTIVITE_ZONALE)
                     ->where('zone_id', $context['zone_id']);
              })
              ->orWhere(function ($q2) use ($context) {
                  $q2->where('type_activite', Constantes::ACTIVITE_SOUS_ZONALE)
                     ->where('sous_zone_id', $context['sous_zone_id']);
              })
              ->orWhere(function ($q2) use ($context) {
                  $q2->where('type_activite', Constantes::ACTIVITE_GROUPE)
                     ->where('groupe_id', $context['groupe_id']);
              });
        })->get();
    }

    /**
     * Summary stats per category, with ratio (participations/total_activities).
     */
    private function buildMemberCategoryStats($categorieActivites, $allActivities, $participations): array
    {
        $activitiesByCategory = $allActivities->groupBy('categorie_activite_id');
        $result = [];

        foreach ($categorieActivites as $cat) {
            $catActivities  = $activitiesByCategory->get($cat->id, collect());
            $totalActivites = $catActivities->count();

            if ($cat->periodicite == Constantes::PERIODE_ANNUELLE && $totalActivites > 0) {
                $totalActivites = 1;
            }

            $totalParticipations = $catActivities->sum(fn ($a) => min($participations[$a->id] ?? 0, 1));

            $result[$cat->nom] = [
                'nombreActivite'      => $totalActivites,
                'nombreParticipation' => $totalParticipations,
                'stats'               => $totalActivites > 0 ? round($totalParticipations * 100 / $totalActivites, 2) : 0,
                'ratio'               => $totalParticipations . '/' . $totalActivites,
            ];
        }

        return $result;
    }

    /**
     * Detailed per-activity stats per category, with ratio.
     */
    private function buildMemberCategoryDetails($categorieActivites, $allActivities, $participations): array
    {
        $activitiesByCategory = $allActivities->groupBy('categorie_activite_id');
        $details = [];

        foreach ($categorieActivites as $cat) {
            $activities      = $activitiesByCategory->get($cat->id, collect());
            $categoryDetails = [];

            if ($activities->isEmpty()) {
                $categoryDetails[''] = [
                    'nombreParticipation' => 0,
                    'nombreActivite'      => 0,
                    'stats'               => 0,
                    'ratio'               => '0/0',
                ];
            } else {
                foreach ($activities as $activity) {
                    $count = min($participations[$activity->id] ?? 0, 1); // 0 or 1 per activity per member
                    $categoryDetails[$activity->nom] = [
                        'nombreParticipation' => $count,
                        'nombreActivite'      => 1,
                        'stats'               => $count * 100,
                        'ratio'               => $count . '/1',
                    ];
                }
            }

            $details[$cat->nom] = $categoryDetails;
        }

        return $details;
    }

    protected function getPourcentageActivite($nombreActivite, $nombreParticipation, $nombreMembres)
    {
        return $nombreActivite > 0 ? $nombreParticipation * 100 / ($nombreMembres * $nombreActivite) : 0;
    }
}
