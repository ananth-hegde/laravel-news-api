<?php

namespace App\Filters\V1;

use Illuminate\Http\Request;

class NewsArticleQueryFilter{
protected $safeParams = [
    'title' => 'like',
    'author' => ['like','eq'],
    'publishedAt' => ['eq','gt','gte','lt','lte'],
    'source' => 'eq',
    'category' => 'eq'
    ];

    protected $columnMap = [
        'title' => 'title',
        'author' => 'author',
        'publishedAt' => 'published_at',
        'source' => 'source',
        'category' => 'category'
    ];

    protected $operatorMap = [
        'eq' => '=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'like' => 'like'
    ];

    public function transform(Request $request){
        $eloQuery = [];
        foreach($this->safeParams as $param => $operators){
            $query = $request->query($param);
            if(!isset($query)){
                continue;
            }
            $column = $this->columnMap[$param] ?? $param;
            if(!is_array($operators)){
                $operators = [$operators];
            }
            foreach($operators as $operator){
                if(isset($query[$operator])){
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }

        }
        return $eloQuery;
    }
}

?>