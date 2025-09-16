<?php

namespace App\Http\Controllers\Api\v100\Admin;

use Illuminate\Http\Request;
use App\Services\City\CityService;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Requests\API\CityRequest;
use App\Http\Resources\CitiesResource;

class CityController extends Controller
{
    use ApiReturnFormatTrait;
    protected $cityService;
    public function __construct(CityService $cityService){

        $this->cityService = $cityService;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $cities = CitiesResource::collection($this->cityService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('City List'), $cities);

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
    public function store(CityRequest $request)
    {
        try {
          $data = $request->validated();
          $city = $this->cityService->createOrUpdate($data);
          return $this->responseWithSuccess('City Created',new CitiesResource($city));

        } catch(\Exception $e)
        {
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
            $city = $this->cityService->get($id,['state']);
            if(!$city)
             return $this->responseWithError('Not found',[],404);

             return $this->responseWithSuccess('City Details',new CitiesResource($city));

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
    public function update(CityRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $this->cityService->createOrUpdate($data , $id);
            $city = $this->cityService->get($id);
            return $this->responseWithSuccess('City Updated',new CitiesResource($city));

          } catch(\Exception $e)
          {
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
            if ($this->cityService->delete($id)) {
                return $this->responseWithSuccess(__('custom.city_deleted_successful'));
            } else {
                return $this->responseWithError(__('custom.city_delete_failed'));
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
}
