<?php

namespace App\Repositories;

use App\BetSelection;
use App\Interfaces\RepositoryInterface;

class BetSelectionsRepository extends MainRepository implements RepositoryInterface
{
    /**
     * PlayerRepository constructor.
     * @param BetSelection $betSelection
     */
    public function __construct(BetSelection $betSelection)
    {
        parent::__construct($betSelection);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function insert(array $data)
    {
        return $this->model->insert($data);
    }
}
