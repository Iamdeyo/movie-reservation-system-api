<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\QueryFilter;

class InvoiceFilter extends QueryFilter
{
    protected $allowedParams = [
        'customerId' => ['eq',],
        'amount' => ['eq', 'gt', 'lt', 'gte', 'lte'],
        'status' => ['eq', 'ne'],
        'billedDate' => ['eq', 'gt', 'lt', 'gte', 'lte'],
        'paidDate' => ['eq', 'gt', 'lt', 'gte', 'lte'],
    ];

    protected $columnMap = [
        'customerId' => 'customer_id',
        'billedDate' => 'billed_date',
        'paidDate' => 'paid_date'

    ];


    public function transform(Request $request)
    {
        $eloQuery = [];
        foreach ($this->allowedParams as $param => $operators) {
            $query = $request->query($param);
            if (!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $value = $query[$operator];
                    $value = $operator === 'like' ? "%$value%" : $value;


                    $eloQuery[] = [$column, $this->operatorMap[$operator], $value];
                }
            }
        }
        return $eloQuery;
    }
}
