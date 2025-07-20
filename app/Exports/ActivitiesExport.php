<?php
namespace App\Exports;

use App\Models\Activity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ActivitiesExport implements FromView
{
    public function __construct(protected $activites)
    {}

    public function view(): View
    {
        return view('exports.activities', [
            'activites' => $this->activites
        ]);
    }
}
