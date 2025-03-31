<?php

namespace App\Filters;

use Illuminate\Http\Request;

class QueryFilter
{
    protected $allowedParams = [];

    protected $columnMap = [];
    protected $operatorMap = [
        'eq' => '=',
        'gt' => '>',
        'lt' => '<',
        'like' => 'like',
        'ne' => '!=',
        'gte' => '>=',
        'lte' => '<=',
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
