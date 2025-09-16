<?php

namespace App\Http\Controllers\Api\v100\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\State\StateService;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\StatesResource;
use App\Http\Requests\API\StateRequest;


class StateController extends Controller
{
    use ApiReturnFormatTrait;
    protected $stateService;
    public function __construct(StateService $stateService){

        $this->stateService = $stateService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try{
         if($request->page)
         {
            $states = StatesResource::collection($this->stateService->all())->response()->getData(true);
         } else {
            $states = StatesResource::collection($this->stateService->get())->response()->getData(true);

         }

            return $this->responseWithSuccess(__('State List'), $states);

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
    public function store(StateRequest $request)
    {
        try {
            $data = $request->validated();
            $state = $this->stateService->createOrUpdate($data);
            return $this->responseWithSuccess('State Created',new StatesResource($state));
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
            $state = $this->stateService->get($id,'country');
            if(!$state)
             return $this->responseWithError('Not found',[],404);

             return $this->responseWithSuccess('State Details',new StatesResource($state));

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
    public function update(StateRequest $request, $id)
    {

        try {

            $data = $request->validated();
            $this->stateService->createOrUpdate($data,$id);
            $state = $this->stateService->get($id);
            return $this->responseWithSuccess('State Updated',new StatesResource($state));
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
            if ($this->stateService->delete($id)) {
                return $this->responseWithSuccess(__('custom.state_deleted_successful'));
            } else {
                return $this->responseWithError(__('custom.state_delete_failed'));
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
}
