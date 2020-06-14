<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SuggestServices
{
    public function provinces($keywords, $limit = 10)
    {
        $query = "select id,name from provinces where name like '%$keywords%' limit $limit";
        $provinces = DB::select($query);
        return $provinces;
    }

    public function districts($keywords, $province=null, $limit = 10)
    {
        $where = [];
        if (!is_null($province)) {
            array_push($where, "province_id=$province");
        }
        array_push($where, "name like '%$keywords%'");
        $where = implode(" and ", $where);
        $query = "select id,name from districts where $where limit $limit";
        $districts = DB::select($query);
        return $districts;
    }

    public function areas($keywords, $province = null, $district = null, $limit = 10)
    {
        $where = [];
        if (!is_null($province)) {
            array_push($where, "province_id=$province");
        }
        if (!is_null($district)) {
            array_push($where, "district_id=$district");
        }
        array_push($where, "name like '%$keywords%'");
        $where = implode(" and ", $where);
        $query = "select id, name from areas where $where limit $limit";
        $areas = DB::select($query);
        return $areas;
    }
}
