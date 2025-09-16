<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ExpensesRequest;
use App\Http\Resources\ExpensesDetailsResource;
use App\Http\Resources\ExpensesResource;
use App\Services\Expenses\ExpensesCategoryService;
use App\Services\Expenses\ExpensesService;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ExpensesController extends Controller
{
    use ApiReturnFormatTrait;
    protected $expensesCategoryService;
    protected $expensesService;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        ExpensesCategoryService $expensesCategoryService,
        ExpensesService $expensesService
    ) {
        $this->expensesCategoryService = $expensesCategoryService;
        $this->expensesService = $expensesService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            if(empty($request->all()) || $request->page){

               return $this->responseWithSuccess(__('Expenses Details'), ExpensesResource::collection($this->expensesService->all())->response()->getData(true));

            }
            $total = 0;
            $data = [];
            $report_range = '';
            $start = $request->from_date;
            $end = $request->to_date;
            if ($start && $end) {
                $report_range = $start . ' - ' . $end;
                $data = $this->expensesService->filterByDateRange($start, $end, ['category']);
            } else {
                $report_range = 'All Time';
                $data = $this->expensesService->getLastYearData(null, ['category']);
            }

            // Calculate total
            if ($data instanceof Collection) {
                $total = $data->sum('total');
            }

            // Monthly graph
            $graph_data = $this->expensesService->monthGraph($start, $end, ['category']);
            // Pie graph
            $pie_graph_data = $this->expensesService->monthGraphPie();
            // Single month
            $single_month_graph = $this->expensesService->singleMonthGraph();
            // This month total
            $this_month_total = $this->expensesService->monthTotal(date('m'));
            // Last month total
            $last_month_total = $this->expensesService->monthTotal(date("m", strtotime("first day of previous month")));
            // Total all time
            $total_all_time = $this->expensesService->totalAllTime();
            $expenses_details = [
                'graph_data' => $graph_data,
                'pie_graph_data' => $pie_graph_data,
                'report_range' => $single_month_graph,
                'total' => $total,
                'single_month_graph' => $single_month_graph,
                'this_month_total' => $this_month_total,
                'last_month_total' => $last_month_total,
                'total_all_time' => $total_all_time,
               // 'expenses_list' => $this->expensesService->all(),
            ];
            return $this->responseWithSuccess(__('Expenses Details'), new ExpensesDetailsResource($expenses_details));

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpensesRequest $request)
    {
        try {
            $data = $request->validated();
            $data['category_id'] = $request->category;
            // dd($data);
            $expenses = $this->expensesService->createOrUpdate($data);
            return $this->responseWithSuccess('Expenses Created', new ExpensesResource($expenses));

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $expenses = $this->expensesService->get($id, ['category', 'items', 'files']);

            if(!$expenses)
             return $this->responseWithError('Not found',[],404);

             return $this->responseWithSuccess('Expenses ', new ExpensesResource($expenses));


        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpensesRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $data['category_id'] = $request->category;
            $expenses = $this->expensesService->createOrUpdate($data, $id);
            return $this->responseWithSuccess('Expenses Updated', new ExpensesResource($expenses));

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if ($this->expensesService->delete($id)) {
                return $this->responseWithSuccess(__('custom.expenses_deleted_successful'));
            } else {
                return $this->responseWithError(__('custom.expenses_deleted_failed'));
            }
        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
    public function deleteFile($file_id)
    {
        try {
            if ($this->expensesService->deleteFile($file_id)) {
                return $this->responseWithSuccess(__('custom.file_deleted_successfully'));

            } else {
                return $this->responseWithError(__('custom.custom.file_deleted_fail'));

            }

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }
}
