<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductPaginator
{
    public static function get_current_page(){
    	$currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;

        return array('currentPage' => $currentPage, 'perPage' => $perPage);
    }

    public static function paginate_products($productsList, $currentPage, $perPage){

    	$collection = collect($productsList);
        return new LengthAwarePaginator($collection->forPage($currentPage, $perPage), $collection->count(), $perPage, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath()]);
    }
}
