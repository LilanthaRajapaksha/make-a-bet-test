<?php

namespace App\Repositories;

use App\Bet;
use App\Interfaces\RepositoryInterface;

class BetRepository extends MainRepository implements RepositoryInterface
{
    /**
     * PlayerRepository constructor.
     * @param Bet $bet
     */
    public function __construct(Bet $bet)
    {
        parent::__construct($bet);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
