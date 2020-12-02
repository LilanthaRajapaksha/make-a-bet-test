<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Player;

class PlayerRepository extends MainRepository implements RepositoryInterface
{
    /**
     * PlayerRepository constructor.
     * @param Player $player
     */
    public function __construct(Player $player)
    {
        parent::__construct($player);
    }

    /**
     * @param array $user
     * @param array $data
     * @return mixed
     */
    public function findOrCreate(array $user, array $data)
    {
        return $this->model->firstOrCreate($user, $data);
    }
}
