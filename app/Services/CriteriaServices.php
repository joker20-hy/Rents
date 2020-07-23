<?php

namespace App\Services;

use App\Repositories\CriteriaRepository;
use App\Models\Criteria;

class CriteriaServices
{
    protected $criteria;
    protected $criteriaRepository;

    public function __construct(Criteria $criteria, CriteriaRepository $criteriaRepository)
    {
        $this->criteria = $criteria;
        $this->criteriaRepository = $criteriaRepository;
    }

    public function index()
    {
        return $this->criteriaRepository->all();
    }

    public function list($paginate = 10)
    {
        return $this->criteriaRepository->listAll($paginate);
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
        return $this->criteriaRepository->store($params);
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
        $criteria = $this->criteriaRepository->update($id, $params);
        return $criteria;
    }

    /**
     * Delete criteria
     *
     * @param integer $id
     */
    public function destroy($id)
    {
        $this->criteriaRepository->destroy($id);
    }
}
