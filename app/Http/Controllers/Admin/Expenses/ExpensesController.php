<?php

namespace App\Http\Controllers\Admin\Expenses;

use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\ExpenseImport;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\DataTables\ExpensesDataTable;
use App\Http\Requests\ExpensesRequest;
use App\Services\Expenses\ExpensesService;
use App\Services\Expenses\ExpensesCategoryService;

class ExpensesController extends Controller
{
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
    public function index(Request $request, ExpensesDataTable $dataTable)
    {
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

        set_page_meta(__('custom.expenses_list'));
        return $dataTable->render('admin.expenses.index', compact(
            'graph_data',
            'pie_graph_data',
            'report_range',
            'total',
            'single_month_graph',
            'this_month_total',
            'last_month_total',
            'total_all_time'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->expensesCategoryService->get();
        $users = User::query()->pluck('name', 'id');

        set_page_meta(__('custom.add_expenses'));
        return view('admin.expenses.create', compact('categories', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpensesRequest $request)
    {
        $data = $request->validated();
        $data['category_id'] = $request->category;
        // dd($data);
        if ($this->expensesService->createOrUpdate($data)) {
            flash(__('custom.expenses_create_successful'))->success();
        } else {
            flash(__('custom.expenses_create_failed'))->error();
        }

        return redirect()->route('admin.expenses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expenses = $this->expensesService->get($id, ['category', 'items', 'files']);$expenses = $this->expensesService->get($id, ['category', 'items', 'files']);

        set_page_meta(__('custom.expenses_details'));
        return view('admin.expenses.show', compact('expenses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expenses = $this->expensesService->get($id, ['category', 'items']);
        $categories = $this->expensesCategoryService->get();
        $users = User::query()->pluck('name', 'id');
        set_page_meta(__('custom.edit_expenses'));
        return view('admin.expenses.edit', compact('expenses', 'categories', 'users'));
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
        $data = $request->validated();
        $data['category_id'] = $request->category;

        if ($this->expensesService->createOrUpdate($data, $id)) {
            flash(__('custom.expenses_updated_successful'))->success();
        } else {
            flash(__('custom.expenses_updated_failed'))->error();
        }

        return redirect()->route('admin.expenses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->expensesService->delete($id)) {
            flash(__('custom.expenses_deleted_successful'))->success();
        } else {
            flash(__('custom.expenses_deleted_failed'))->error();
        }

        return redirect()->route('admin.expenses.index');
    }

    /**
     * deleteFile
     *
     * @param  mixed $file_id
     * @return void
     */
    public function deleteFile($file_id)
    {
        if ($this->expensesService->deleteFile($file_id)) {
            flash(__('custom.file_deleted_successfully'))->success();
        } else {
            flash(__('custom.file_deleted_fail'))->error();
        }

        return redirect()->back();
    }

    /**
     * Bulk import brands from Excel file.
     */
    public function import(Request $request)
    {
        try {
            Excel::import(new ExpenseImport(), $request->file('import_file'));

            flash(__('custom.import_success'))->success();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Show validation error messages
            $messages = $e->validator->errors()->all();
            flash(__('custom.import_failed') . ': ' . implode(' | ', $messages))->error();
        } catch (\Throwable $e) {
            // Show unexpected error messages
            flash(__('custom.import_failed') . ': ' . $e->getMessage())->error();
        }

        return redirect()->route('admin.expenses.index');
    }

}
