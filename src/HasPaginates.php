<?php

namespace Jjsty1e\LaravelQueryBuilder;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HasPaginates
{
    public static $maxPageSize = 500;
    public static $defaultPageSize = 20;

    public function getPageNoPageSize($params): array
    {
        $pageNo = !empty($params['current_page']) ? intval($params['current_page']) : 1;
        $pageSize = !empty($params['per_page']) ? intval($params['per_page']) : static::$defaultPageSize;

        $pageNo = $pageNo > 0 ? $pageNo : 1;
        $pageSize = ($pageSize < 1 || $pageSize > static::$maxPageSize) ? static::$defaultPageSize : $pageSize;

        return [$pageNo, $pageSize];
    }

    public function getPageFromPageSize($params): array
    {
        [$pageNo, $pageSize] = $this->getPageNoPageSize($params);
        return [$pageNo($pageNo - 1) * $pageSize, $pageSize];
    }

    /**
     * @param Builder|QueryBuilder $query
     * @param array $paginate
     * @return array
     */
    public function paginate($query, array $paginate): array
    {
        [$pageNo, $pageSize] = $paginate;
        $total = $query->count();
        $list = $query->forPage($pageNo, $pageSize)->get()->toArray();
        return $this->page($list, $total, $pageSize, $pageNo);
    }

    /**
     * @param Builder|QueryBuilder $query
     * @param array $paginate
     * @param string $groupBy
     * @return array
     */
    public function paginateGrouped($query, array $paginate, string $groupBy): array
    {
        [$pageNo, $pageSize] = $paginate;
        $total = $query->count(DB::raw('distinct ' . $groupBy));
        $list = $query->groupBy($groupBy)->forPage($pageNo, $pageSize)->get()->toArray();
        return $this->page($list, $total, $pageSize, $pageNo);
    }

    public function page($list, $total, $pageSize, $pageNo): array
    {
        return [
            'data' => $list,
            'total' => (int)$total,
            'per_page' => (int)$pageSize,
            'current_page' => (int)$pageNo,
        ];
    }

    public function emptyPage(array $paginate): array
    {
        [$pageNo, $pageSize] = $paginate;
        return [
            'data' => [],
            'total' => 0,
            'per_page' => (int)$pageSize,
            'current_page' => (int)$pageNo,
        ];
    }
}
