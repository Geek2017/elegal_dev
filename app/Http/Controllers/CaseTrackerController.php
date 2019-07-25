<?php

namespace App\Http\Controllers;

use App\CaseTracker;
use App\CaseManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use DB;
use Auth;

class CaseTrackerController extends Controller
{
    private $rules = array(
                    'case_management_id' => 'required|exists:case_managements,id',
                    'transaction_date'   => 'required',
                    'due_date'           => 'required',
                    'activities'         => 'required',
                    'action_to_take'     => 'required',
                    'status'             => 'required|in:P,D'
                );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.case_tracker.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails()) {
                throw new \Exception("Error Processing Request", 1);
            }

            $inputs = $request->except(['_token']);

            $caseTrack = CaseTracker::create($inputs);

            return response()->json($caseTrack);
        } catch (Exception $e) {
            throw new \Exception("Error Processing Request", 1);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CaseTracker  $caseTracker
     * @return \Illuminate\Http\Response
     */
    public function show($caseManagementId)
    {
        $now = Carbon::now()->format('Y-m-d');
        $case = CaseManagement::select([
                'case_managements.*',
                DB::raw("COALESCE(case_managements.number, 'No Available') as number")
            ])
            ->with([
                'transaction.contract',
                'transaction.client.profile',
                'counsel.profile',
                'caseTracker'
            ])
            ->find($caseManagementId);

        return view('user.case_tracker.edit', compact('case', 'now'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CaseTracker  $caseTracker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $caseTrackerId)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules);

            if ($validator->fails()) {
                throw new \Exception("Error Processing Request", 1);
            }

            $inputs = $request->except(['_token']);

            $caseTracker = CaseTracker::findorFail($caseTrackerId);
            $caseTracker->fill($inputs);
            $caseTracker->save();

            return response()->json($caseTracker);
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception($e, 1);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CaseTracker  $caseTracker
     * @return \Illuminate\Http\Response
     */
    public function destroy($caseTrackerId)
    {
        $caseTracker = CaseTracker::findorFail($caseTrackerId);
        $caseTracker->delete();

        return response()->json('success');
    }

    public function alerts()
    {
        $user = Auth::user();
        $now = new Carbon();

        $caseTrackers = DB::table('case_trackers')->select('case_trackers.case_management_id as ref')
            ->join('case_managements', 'case_managements.id', '=', 'case_trackers.case_management_id')
            ->whereRaw("
                    (
                        case_managements.counsel_id = {$user->id}
                        OR
                        case_managements.creator_id = {$user->id}
                    )
                ")
            ->whereRaw("
                    (
                        case_trackers.due_date = '{$now->addDays(3)->format('Y-m-d')}'
                        OR
                        case_trackers.status = 'P'
                    )
                ")
            ->groupBy('case_trackers.case_management_id')
            ->get();

        if ($caseTrackers) {
            session(['is_case_was_notified' => 'yes']);
        }

        return response()->json($caseTrackers);
    }

    public function pendingCaseActions(Request $request)
    {
        $user = Auth::user();
        $now = new Carbon();

        $caseTrackers = CaseTracker::
            select([
                'case_trackers.*',
                DB::raw("CONCAT(case_managements.title, ' [', case_trackers.action_to_take ,']') as title"),
                DB::raw("case_trackers.due_date as start_date"),
                DB::raw("IF(case_trackers.status = 'P', 'red', 'green') as color")
            ])
            ->join('case_managements', 'case_managements.id', '=', 'case_trackers.case_management_id')
            ->whereBetween('case_trackers.due_date', [$request['start'], $request['end']])
            ->where('case_managements.counsel_id', $user->id)
            ->get();

        return response()->json($caseTrackers);
    }
}
