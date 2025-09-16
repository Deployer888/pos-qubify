<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'graph_data'   => $this['graph_data'],
            'pie_graph_data' => $this['pie_graph_data'],
            'report_range' => $this['single_month_graph'],
            'total' => $this['total'],
            'single_month_graph' => $this['single_month_graph'],
            'this_month_total' => $this['this_month_total'],
            'last_month_total' => $this['last_month_total'],
            'total_all_time' => $this['total_all_time'],

        ];
    }
}
