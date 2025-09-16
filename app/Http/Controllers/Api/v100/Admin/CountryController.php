<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Models\SystemCountry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Services\Country\CountryService;
use App\Http\Requests\API\CountryRequest;
use App\Http\Resources\CountriesResource;

class CountryController extends Controller
{
    use ApiReturnFormatTrait;
    protected $countryService;
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{

            $countries = CountriesResource::collection($this->countryService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Country List'), $countries);
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
    public function store(CountryRequest $request)
    {
        try{
            $data = $request->validated();
            $country =$this->countryService->createOrUpdate($data);
             return $this->responseWithSuccess('Country Created',new CountriesResource($country));

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
            $country =$this->countryService->get($id);
            if(!$country)
             return $this->responseWithError('Not found',[],404);

             return $this->responseWithSuccess('Country Details',new CountriesResource($country));


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
    public function update(CountryRequest $request, $id)
    {
        try{
            $data = $request->validated();
            $this->countryService->createOrUpdate($data,$id);
            $country = $this->countryService->get($id);
            return $this->responseWithSuccess('Country Updated',new CountriesResource($country));

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
            if ($this->countryService->delete($id)) {
                return $this->responseWithSuccess(__('custom.country_deleted_successful'));
            } else {
                return $this->responseWithError(__('custom.country_deleted_failed'));
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
}
