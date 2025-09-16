<?php

namespace App\Http\Controllers\Api\v100\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\MeasurementUnitResource;
use App\Http\Requests\API\MeasurementUnitRequest;
use App\Models\MeasurementUnit;
use App\Services\MeasurementUnit\MeasurementUnitService;


class MeasurementUnitsController extends Controller
{
    use ApiReturnFormatTrait;
    protected $measurementUnitService;

    /**
     * __construct
     *
     * @param  mixed $measurementUnitService
     * @return void
     */
    public function __construct(MeasurementUnitService $measurementUnitService)
    {
        $this->measurementUnitService = $measurementUnitService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $measurementUnits = MeasurementUnitResource::collection($this->measurementUnitService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('MeasurementUnit List'), $measurementUnits);

           } catch(\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

           }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MeasurementUnitRequest $request)
    {
        try {
             $data = $request->validated();
             $measurementUnit = $this->measurementUnitService->createOrUpdate($data);
             return $this->responseWithSuccess('MeasurementUnit Created',new MeasurementUnitResource($measurementUnit));

        } catch(\Exception $e){
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
            $measurementUnit = $this->measurementUnitService->get($id);
            if(!$measurementUnit)
             return $this->responseWithError('Not found',[],404);

             return $this->responseWithSuccess('MeasurementUnit Details',new MeasurementUnitResource($measurementUnit));


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
    public function update(MeasurementUnitRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $this->measurementUnitService->createOrUpdate($data,$id);
            $measurementUnit = $this->measurementUnitService->get($id);
            return $this->responseWithSuccess('MeasurementUnit Updated',new MeasurementUnitResource($measurementUnit));

       } catch(\Exception $e){
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
            if ($this->measurementUnitService->delete($id)) {
                return $this->responseWithSuccess(__('custom.measurement_unit_deleted_successfully'));
            } else {
                return $this->responseWithError(__('custom.measurement_unit_delete_failed'));
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
}
