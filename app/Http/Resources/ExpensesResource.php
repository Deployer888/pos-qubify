<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
class ExpensesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        // dd($routeName = Route::currentRouteName());

         return [
            'id' => $this->id,
            'title' => Str::limit($this->title, 50, '...'),
            'date' => $this->date,
            'total' => currencySymbol() . make2decimal($this->total),
            'category' => new ExpensesCategoriesResource($this->category),
            'expenseBy' => new UserResource($this->expenseBy),
            'notes' => $this->notes,
            'files' => Route::currentRouteName() !='expenses.index' ? ExpensesFileResource::collection($this->files)->response()->getData(true) :[],
            'items' =>Route::currentRouteName() !='expenses.index' ? ExpensesItemResource::collection($this->items)->response()->getData(true) : [],


        ];
    }
}
