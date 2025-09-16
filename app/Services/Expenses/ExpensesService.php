<?php

namespace App\Services\Expenses;

use Throwable;
use App\Models\Expenses;
use App\Models\ExpensesFile;
use App\Models\ExpensesItem;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * ExpensesService
 */
class ExpensesService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Expenses $model)
    {
        parent::__construct($model);
    }

    /**
     * getLastYearData
     *
     * @param  mixed $id
     * @param  mixed $with
     * @param  mixed $limit
     * @return void
     */
    public function getLastYearData($id = null, $with = [], $limit = null)
    {
        try {
            if ($id) {
                $data = $this->model->with($with)->whereYear('date', date('Y'))->find($id);
                return $data ? $data : false;
            } else {
                return $this->model->with($with)->whereYear('date', date('Y'))->limit($limit)->get();
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * filterByDateRange
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  mixed $with
     * @return void
     */
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
     * customFilter
     *
     * @param  mixed $request
     * @param  mixed $with
     * @return void
     */
    public function customFilter($request, $with = [])
    {
        try {
            $query = $this->model::query()->with($with);

            if (isset($request->from_date)) {
                $query->whereDate('date', '>=', $request->from_date);
            }

            if (isset($request->to_date)) {
                $query->whereDate('date', '<=', $request->to_date);
            }

            return $query->get();
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
            $expenses = [];

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

                $e ? $expenses[] = $e : $expenses[] = 0;
            }

            return $expenses;
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
            $expenses = [];

            for ($i = 1; $i <= 12; $i++) {
                $query = $this->model::query()->with($with);

                if ($start) {
                    $query->whereDate('date', '>=', $start);
                }

                if ($end) {
                    $query->whereDate('date', '<=', $end);
                }

                $e = $query->whereMonth('date', $i)->sum('total');


                $e ? $expenses[] = $e : $expenses[] = 0;
            }

            return $expenses;
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
        return Expenses::select(DB::raw('DAY(date) as day , sum(total) as total'))
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
        return Expenses::select(DB::raw('sum(total) as total'))
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
        return Expenses::select(DB::raw('sum(total) as total'))
            ->get()[0]->total ?? 00;
    }


    /**
     * createOrUpdate
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function createOrUpdate(array $data, $id = null)
    {

        try {
            DB::beginTransaction();

            if ($id) {
                // Update
                $expenses = $this->model::find($id);
                $expenses->updated_by = Auth::id();
                $expenses->title = $data['title'];
                $expenses->date = $data['date'];
                $expenses->notes = $data['notes'];
                $expenses->expense_by = array_key_exists('expense_user', $data) ? $data['expense_user'] : \auth()->id();

                // Delete old item
                ExpensesItem::where('expenses_id', $id)->delete();
                // Create expenses item
                $total = 0;
                 foreach ($data['data'] as $item) {
                    $item['expenses_id'] = $expenses->id;
                    ExpensesItem::create($item);
                    $total += ($item['amount'] ?? 0);
                }
                // Update expenses total
                $expenses->total = $total;
                $expenses->save();

                // Expenses files
                if (isset($data['files'])) {

                    foreach ($data['files'] as $file) {
                        if(strpos($file, 'base64') !== false)
                        {
                            $name = $this->fileUploadService->uploadBase64($file, Expenses::FILE_STORE_PATH);
                            
                            $ef = new ExpensesFile();
                            $ef->expenses_id = $expenses->id;
                            // $ef->original_name = $file->getClientOriginalName();
                            $ef->file_name = $name;
                            $ef->save();
                        } else {
                            $name = $this->uploadFile($file);
                            $ef = new ExpensesFile();
                            $ef->expenses_id = $expenses->id;
                            $ef->original_name = $file->getClientOriginalName();
                            $ef->file_name = $name;
                            $ef->save();
                        }
                        
                    }
                }


                DB::commit();
                return $expenses;
            } else {
                // Create
                $data['created_by'] = Auth::id();
                $data['expense_by'] = array_key_exists('expense_user', $data) ? $data['expense_user'] : \auth()->id();
                $expenses = $this->model::create($data);

                // Create expenses item
                $total = 0;
                foreach ($data['data'] as $item) {
                    $item['expenses_id'] = $expenses->id;
                    ExpensesItem::create($item);
                    $total += ($item['amount'] ?? 0);
                }
                // Update expenses total
                $expenses->total = $total;
                $expenses->save();

                // Expenses files
                if (isset($data['files'])) {

                    foreach ($data['files'] as $file) {
                        
                        if(strpos($file, 'base64') !== false)
                        {
                            $name = $this->fileUploadService->uploadBase64($file, Expenses::FILE_STORE_PATH);

                            $ef = new ExpensesFile();
                            $ef->expenses_id = $expenses->id;
                            // $ef->original_name = $file->getClientOriginalName();
                            $ef->file_name = $name;
                            $ef->save();
                        } else {
                            $name = $this->uploadFile($file);
                            $ef = new ExpensesFile();
                            $ef->expenses_id = $expenses->id;
                            $ef->original_name = $file->getClientOriginalName();
                            $ef->file_name = $name;
                            $ef->save();
                        }
                 
                        
                    }
                }


                DB::commit();
                return $expenses;
            }
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * delete
     *
     * @param  mixed $id
     * @param  mixed $file_name
     * @return void
     */
    public function delete($id, $file_name = null)
    {
        try {
            $expenses = $this->model::find($id);

            $expenses_files = ExpensesFile::where('expenses_id', $expenses->id)->get();

            foreach ($expenses_files as $file) {
                $file->delete();
            }

            return $expenses->delete();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * deleteFile
     *
     * @param  mixed $file_id
     * @return void
     */
    public function deleteFile($file_id)
    {
        try {
            $file = ExpensesFile::find($file_id);
            return $file->delete();
        } catch (Throwable $th) {
            throw $th;
        }
    }
    public function all()
    {
        return $this->model->with(['category'])->select('expenses.*')->orderByDesc('date')->paginate(10);
    }
}
