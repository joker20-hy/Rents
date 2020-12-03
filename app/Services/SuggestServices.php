<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SuggestServices
{
    public function provinces($keywords, $limit = null)
    {
        $query = "select id, name, slug from provinces where name like '%$keywords%' and deleted_at is null";
        if (!is_null($limit)) {
            $query = "$query limit $limit";
        }
        $provinces = DB::select($query);
        return $provinces;
    }

    public function districts($keywords, $province = null, $limit = null)
    {
        $where = [];
        if (!is_null($province)) {
            array_push($where, "province_id=$province");
        }
        array_push($where, "name like '%$keywords%'");
        $where = implode(" and ", $where);
        $query = "select id, name, slug from districts where $where and deleted_at is null";
        if (!is_null($limit)) {
            $query = "$query limit $limit";
        }
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
        $query = "select id, name, slug from areas where $where and deleted_at is null limit $limit";
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
        array_push($where, "h.address_detail like '%$keywords%'");
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
                h.address_detail as name
            from $from
            where $where
            limit $limit";
        $houses = DB::select($query);
        return $houses;
    }

    public function address($keywords)
    {
        $areas = DB::select("
        select
            a.id as id,
            a.slug as slug,
            3 as type,
            concat(a.name, ', ', d.name, ', ', p.name) as name
        from
            areas a join provinces p on a.province_id = p.id
            join districts d on a.district_id = d.id
        where match(a.name) against('$keywords') and a.deleted_at is null limit 5");
        $districts = DB::select("
        select
            d.id as id,
            d.slug as slug,
            2 as type,
            concat(d.name, ', ', p.name) as name
        from
            districts d join provinces p on d.province_id = p.id
        where match(d.name) against('$keywords') and d.deleted_at is null limit 5");
        $results = array_merge($districts, $areas);
        return $results;
    }
}
