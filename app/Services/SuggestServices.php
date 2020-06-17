<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        if (!is_null($district)) {
            array_push($where, "district_id=$district");
        } elseif (!is_null($province)) {
            array_push($where, "province_id=$province");
        }
        array_push($where, "name like '%$keywords%'");
        $where = implode(" and ", $where);
        $query = "select id, name from areas where $where limit $limit";
        $areas = DB::select($query);
        return $areas;
    }

    /**
     * 
     */
    public function houses($keywords, $condition, $limit = 10)
    {
        $where = [];
        if (!is_null($condition['area'])) {
            array_push($where, "area_id=".$condition['area']);
        } elseif (!is_null($condition['district'])) {
            array_push($where, "district_id=".$condition['district']);
        } elseif (!is_null($condition['province'])) {
            array_push($where, "province_id=".$condition['province']);
        }
        array_push($where, "concat(h.address,', ',a.name,', ',d.name,', ',p.name) like '%$keywords%'");
        $where = implode(" and ", $where);
        $from = "houses h 
            join
            provinces p
            on h.province_id=p.id
            join
            districts d
            on h.district_id=d.id
            join
            areas a
            on h.area_id=a.id";
        $query = "
            select
                h.id,
                concat(h.address,', ',a.name,', ',d.name,', ',p.name) as name
            from $from
            where $where
            limit $limit";
        $houses = DB::select($query);
        return $houses;
    }
}
