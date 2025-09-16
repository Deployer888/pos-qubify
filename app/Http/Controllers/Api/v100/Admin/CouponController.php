<?php
namespace App\Http\Controllers\Api\v100\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\CouponResource;
use App\Services\Coupon\CouponService;
use App\Http\Requests\API\CouponRequest;
use App\Http\Resources\AppliedCouponResource;



class CouponController extends Controller
{
    use ApiReturnFormatTrait;

    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function applyCoupon($code)
    {
        try {
            $coupon = $this->couponService->getActiveCouponByCode($code);
            // dd($coupon);
            if ($coupon) {
            //   return new AppliedCouponResource($coupon);

             return $this->responseWithSuccess('Coupon Applied Products',new AppliedCouponResource($coupon));

            }
            else {
                    $data =[
                        'status'                => false,
                        'coupon'                => null,
                        'coupon_product_ids'    => null
                    ];
                  return $this->responseWithSuccess('Coupon Not Found',$data);

                }

        } catch (\Exception $e) {

            return $this->responseWithError('Something went wrong', $e->getMessage());
        }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $coupons = CouponResource::collection($this->couponService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Coupon List'), $coupons);

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
    public function store(CouponRequest $request)
    {
        try{
            $data = $request->validated();
            $coupon =$this->couponService->createOrUpdateWithFile($data, 'banner');
            return $this->responseWithSuccess('Coupon Created',new CouponResource($coupon));

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
            $coupon = $this->couponService->get($id);
            if(!$coupon)
             return $this->responseWithError('Not found',[],404);

             return $this->responseWithSuccess('Coupon details',new CouponResource($coupon));


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
    public function update(CouponRequest $request, $id)
    {
        try{
            $data = $request->validated();
            $this->couponService->createOrUpdateWithFile($data, 'banner',$id);
            $coupon = $this->couponService->get($id);
            return $this->responseWithSuccess('Coupon Updated',new CouponResource($coupon));

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
            if ($this->couponService->delete($id)) {
                return $this->responseWithSuccess(__('custom.coupon_deleted_successful'),[],200);
            } else {
                return $this->responseWithError(__('custom.coupon_deleted_failed'),[],500);
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', [],500);

        }
    }
}
