<?php

namespace App\Repositories;

use App\BalanceTransaction;
use App\Interfaces\RepositoryInterface;

class BalanceTransactionRepository extends MainRepository implements RepositoryInterface
{
    /**
     * PlayerRepository constructor.
     * @param BalanceTransaction $balanceTransaction
     */
    public function __construct(BalanceTransaction $balanceTransaction)
    {
        parent::__construct($balanceTransaction);
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
