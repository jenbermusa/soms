<?php

namespace App\Http\Controllers;

use App\Models\SubmittedMinorOffense;
use App\Models\SubmittedMajorOffense;
use Illuminate\Http\Request;

class BarGraphController extends Controller
{
    public function getBarData(Request $request) 
    {
        // Convert start date and end date to appropriate format with time range
        $startDate = date('Y-m-d 00:00:00', strtotime($request->input('start_date')));
        $endDate = date('Y-m-d 23:59:59', strtotime($request->input('end_date')));

        // Fetch top minor offenses based on their frequency
        $minorOffenses = SubmittedMinorOffense::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('minor_offense_id, COUNT(*) as count')
            ->groupBy('minor_offense_id')
            ->orderByDesc('count')
            ->with('minorOffense') // Fetch the related offense details
            ->get();

        // Fetch top major offenses based on their frequency
        $majorOffenses = SubmittedMajorOffense::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('major_offense_id, COUNT(*) as count')
            ->groupBy('major_offense_id')
            ->orderByDesc('count')
            ->with('majorOffense') // Fetch the related offense details
            ->get();

        // Combine both minor and major offenses into a single collection
        $combinedOffenses = $minorOffenses->map(function ($offense) {
            return [
                'offense_name' => $offense->minorOffense->minor_offenses,
                'count' => $offense->count,
            ];
        })->concat($majorOffenses->map(function ($offense) {
            return [
                'offense_name' => $offense->majorOffense->major_offenses,
                'count' => $offense->count,
            ];
        }));

        // Sort the combined offenses by count and take the top 5
        $topOffenses = $combinedOffenses->sortByDesc('count')->take(5);

        // Prepare the data for the frontend
        $data = $topOffenses->values()->all(); // Reset keys and convert to array

        return response()->json($data);
    }
}