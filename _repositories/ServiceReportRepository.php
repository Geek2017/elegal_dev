<?php

namespace Repositories;

use App\ServiceReport;

class ServiceReportRepository
{
    public function getcounselServiceReports($request)
    {
        $serviceReports = ServiceReport::select([
                'service_reports.*'
            ])
            ->with([
                'chargeables.fee', 
                'client.profile',
                'cases'
            ])
            ->where('counsel_id', $request['counsel_id']);

        if ($request['type'] == 'dateRage') {
            $serviceReports->whereBetween('service_reports.date', [$request['start_date'], $request['end_date']]);
        } else {
            $serviceReports->where('service_reports.date', '<=', $request['as_of_date']);
        }

        return $serviceReports->get();
    }
}
