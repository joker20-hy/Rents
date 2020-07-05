<?php

namespace App\Services;

use App\Models\Criteria;

class CriteriaServices
{
    protected $criteria;

    public function __construct(Criteria $criteria)
    {
        $this->criteria = $criteria;
    }

    public function index($type = 1)
    {
        $criterias = $this->criteria->get();
        return $criterias;
    }

    public function list($paginate = 10)
    {
        $criterias = $this->criteria->paginate($paginate);
        return $criterias;
    }

    /**
     * Create criteria
     *
     * @param array $params
     *
     * @return \App\Models\Criteria
     */
    public function store($params)
    {
        $criteria = $this->criteria->create($params);
        return $criteria;
    }

    /**
     * Update criteria
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Criteria
     */
    public function update($id, $params)
    {
        $criteria = $this->criteria->findOrFail($id);
        $criteria->update($params);
        return $criteria;
    }

    /**
     * Delete criteria
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $criteria = $this->criteria->findOrFail($id);
        $criteria->delete();
    }
}
