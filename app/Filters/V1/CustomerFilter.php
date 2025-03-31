<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\QueryFilter;

class CustomerFilter extends QueryFilter
{
    protected $allowedParams = [
        'postalCode' => ['eq', 'gt', 'lt'],
        'name' => ['eq', 'like'],
        'email' => ['eq'],
        'type' => ['eq'],
        'address' => ['eq', 'like'],
        'city' => ['eq', 'like'],
        'state' => ['eq', 'like'],
    ];

    protected $columnMap = [
        'postalCode' => 'postal_code',

    ];
    // protected $operatorMap = [
    //     'eq' => '=',
    //     'gt' => '>',
    //     'lt' => '<',
    //     'like' => 'like',
    // ];

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
