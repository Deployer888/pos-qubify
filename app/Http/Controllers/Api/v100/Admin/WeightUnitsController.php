<?php

namespace App\Http\Controllers\Api\v100\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WeightUnitResource;
use App\Http\Requests\API\WeightUnitRequest;
use App\Models\WeightUnit;
use App\Services\WeightUnit\WeightUnitService;
use App\Traits\ApiReturnFormatTrait;


class WeightUnitsController extends Controller
{
    use ApiReturnFormatTrait;
    protected $weightUnitService;

    /**
     * __construct
     *
     * @param  mixed $weightUnitService
     * @return void
     */
    public function __construct(WeightUnitService $weightUnitService)
    {
        $this->weightUnitService = $weightUnitService;
    }
    public function index()
    {
        try{
            $weightUnits = WeightUnitResource::collection($this->weightUnitService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('WeightUnit List'), $weightUnits);

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
    public function store(WeightUnitRequest $request)
    {
        try {
             $data = $request->validated();
             $weightUnit = $this->weightUnitService->createOrUpdate($data);
             return $this->responseWithSuccess(__('WeightUnit Created'), new WeightUnitResource($weightUnit));

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
            $weightUnit = $this->weightUnitService->get($id);
            if(!$weightUnit)
             return $this->responseWithError('Not found',[],404);

             return $this->responseWithSuccess(__('WeightUnit Details'), new WeightUnitResource($weightUnit));


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
    public function update(WeightUnitRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $this->weightUnitService->createOrUpdate($data,$id);
            $weightUnit = $this->weightUnitService->get($id);
            return $this->responseWithSuccess(__('WeightUnit Updated'), new WeightUnitResource($weightUnit));

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
            if ($this->weightUnitService->delete($id)) {
                return $this->responseWithSuccess(__('custom.weight_unit_deleted_successfully'));
            } else {
                return $this->responseWithError(__('custom.weight_unit_delete_failed'));
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }
}
