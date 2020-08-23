<?php

namespace App\Services;

use App\Repositories\CriteriaRepository;

class CriteriaServices
{
    protected $criteriaRepository;

    public function __construct(CriteriaRepository $criteriaRepository)
    {
        $this->criteriaRepository = $criteriaRepository;
    }

    public function index()
    {
        return $this->criteriaRepository->all();
    }

    /**
     * List and paginate criteria
     *
     * @param integer $paginate
     *
     * @return mixed
     */
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
    public function store(array $params)
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
    public function update($id, array $params)
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
