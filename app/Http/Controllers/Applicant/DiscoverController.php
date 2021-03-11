<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\ProgramCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DiscoverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        // @TODO repo me
        $now = Carbon::now();
        $cycles = ProgramCycle::with('program.organization')
                    ->where('opens_at', '<=', $now)
                    ->where('closes_at', '>=', $now)
                    ->orderBy('closes_at', 'ASC')
                    ->get();

        return view('applicant.discover')->with([
            'cycles' => $cycles,
        ]);

    }
}
