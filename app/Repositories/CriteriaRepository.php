<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Criteria;

class CriteriaRepository
{
    protected $criteria;

    public function __construct(Criteria $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * List all criteria records
     *
     * @return ([\App\Models\Criteria])
     */
    public function all()
    {
        return $this->criteria->get();
    }

    /**
     * List and paginate criteria records
     *
     * @param integer $paginate
     *
     * @return ([\App\Models\Criteria])
     */
    public function listAll($paginate)
    {
        return $this->criteria->paginate($paginate);
    }

    /**
     * Create criteria record
     *
     * @param array $params
     *
     * @return \App\Models\Criteria
     */
    public function store(array $params)
    {
        $criteria = $this->criteria->create($params);
        return $criteria;
    }

    /**
     * Update criteria record
     *
     * @param integer $id
     * @param array $params
     *
     * @return \App\Models\Criteria
     */
    public function update($id, array $params)
    {
        $criteria = DB::transaction(function () use ($id, $params) {
            $criteria = $this->criteria->findOrFail($id);
            $criteria->update($params);
        });
        return $criteria;
    }

    /**
     * Delete criteria
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $criteria = $this->criteria->findOrFail($id);
            $criteria->delete();
        });
    }
}
