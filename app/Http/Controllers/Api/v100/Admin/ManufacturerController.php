<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\ManufacturerResource;
use App\Http\Requests\API\ManufacturerRequest;
use App\Services\Manufacturer\ManufacturerService;



class ManufacturerController extends Controller
{

    use ApiReturnFormatTrait;

    protected $manufacturerService;
    public function __construct(ManufacturerService $manufacturerService){

        $this->manufacturerService = $manufacturerService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $manufacturers = ManufacturerResource::collection($this->manufacturerService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Manufacturer List'), $manufacturers);
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
    public function store(ManufacturerRequest $request)
    {
        try{
            $data = $request->validated();
            $manufacturer =$this->manufacturerService->createOrUpdateWithFile($data, 'image');
             return $this->responseWithSuccess('Manufacturer Created',new ManufacturerResource($manufacturer));

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
            $manufacturer = $this->manufacturerService->get($id);
            if(!$manufacturer)
             return $this->responseWithError('Not found',[],404);

            return $this->responseWithSuccess('Manufacturer Details',new ManufacturerResource($manufacturer));

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
    public function update(ManufacturerRequest $request, $id)
    {
        try{
            $data = $request->validated();
            $this->manufacturerService->createOrUpdateWithFile($data, 'image',$id);
            $manufacturer =$this->manufacturerService->get($id);
             return $this->responseWithSuccess('Manufacturer Updated',new ManufacturerResource($manufacturer));

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
            if ($this->manufacturerService->delete($id)) {
                return $this->responseWithSuccess(__('custom.manufacturer_deleted_successfully'));
            } else {
                return $this->responseWithError(__('custom.manufacturer_deleted_failed'));
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
}
