<?php

namespace App\Services\Dashboard;

use Throwable;
use App\Models\Invoice;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class DashboardService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function filterByDateRange($start = null, $end = null, $with = [])
    {
        try {
            $query = $this->model::query()->with($with);

            if ($start) {
                $query->whereDate('date', '>=', $start);
            }

            if ($end) {
                $query->whereDate('date', '<=', $end);
            }

            return $query->orderBy('date', 'DESC')->get();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * monthGraph
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  mixed $with
     * @return void
     */
    public function monthGraph($start = null, $end = null, $with = [])
    {
        try {
            $sales = [];

            for ($i = 1; $i <= 12; $i++) {
                $filter = false;
                $query = $this->model::query()->with($with);

                if ($start) {
                    $filter = true;
                    $query->whereDate('date', '>=', $start);
                }

                if ($end) {
                    $filter = true;
                    $query->whereDate('date', '<=', $end);
                }

                if ($filter) {
                    $e = $query->whereMonth('date', $i)->sum('total');
                } else {
                    $e = $query->whereYear('date', date('Y'))->whereMonth('date', $i)->sum('total');
                }

                $e ? $sales[] = $e : $sales[] = 0;
            }

            return $sales;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * monthGraphPie
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  mixed $with
     * @return void
     */
    public function monthGraphPie($start = null, $end = null, $with = [])
    {
        try {
            $sales = [];

            for ($i = 1; $i <= 12; $i++) {
                $query = $this->model::query()->with($with);

                if ($start) {
                    $query->whereDate('date', '>=', $start);
                }

                if ($end) {
                    $query->whereDate('date', '<=', $end);
                }

                $e = $query->whereMonth('date', $i)->sum('total');


                $e ? $sales[] = $e : $sales[] = 0;
            }

            return $sales;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * singleMonthGraph
     *
     * @return void
     */
    public function singleMonthGraph()
    {
        return $this->model::select(DB::raw('DAY(date) as day , sum(total) as total'))
            ->whereMonth('date', date('m'))
            ->whereYear('date', date('Y'))
            ->groupBy('date')
            ->get();
    }

    /**
     * monthTotal
     *
     * @param  mixed $month
     * @return void
     */
    public function monthTotal($month)
    {
        return $this->model->newQuery()->select(DB::raw('sum(total) as total'))
            ->whereMonth('date', $month)
            ->whereYear('date', date('Y'))
            ->get()[0]->total ?? 00;
    }

    /**
     * totalAllTime
     *
     * @return void
     */
    public function totalAllTime()
    {
        return $this->model::select(DB::raw('sum(total) as total'))
            ->get()[0]->total ?? 00;
    }
}
