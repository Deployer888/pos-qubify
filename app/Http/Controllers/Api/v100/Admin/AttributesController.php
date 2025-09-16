<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\AttributeResource;
use App\Http\Requests\API\AttributeRequest;
use App\Services\Attribute\AttributeService;


class AttributesController extends Controller
{
    use ApiReturnFormatTrait;

    protected $attributeService;

    /**
     * __construct
     *
     * @param  mixed $attributeService
     * @return void
     */
    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $attributes = AttributeResource::collection($this->attributeService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Attribute List'), $attributes);

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
    public function store(AttributeRequest $request)
    {
        try {
            $data = $request->validated();
            // $data['item_data'] = json_decode($data['item_data'], true);
            logger($data);
            $attribute = $this->attributeService->createOrUpdate($data);
            return $this->responseWithSuccess('Attribute Created',new AttributeResource($attribute));

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
            $attribute = $this->attributeService->get($id);
            if(!$attribute)
             return $this->responseWithError('Not found',[],404);

             return $this->responseWithSuccess('Attribute Details',new AttributeResource($attribute));


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
    public function update(AttributeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            // $data['item_data'] = json_decode($data['item_data'], true);
            $this->attributeService->createOrUpdate($data,$id);
            $attribute = $this->attributeService->get($id);
            return $this->responseWithSuccess('Attribute Updated',new AttributeResource($attribute));

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
            if ($this->attributeService->delete($id)) {
                return $this->responseWithSuccess(__('custom.attribute_deleted_successful'));
            } else {
                return $this->responseWithError(__('custom.attribute_deleted_failed'));
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
}
