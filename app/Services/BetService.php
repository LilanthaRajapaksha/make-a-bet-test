<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Interfaces\ServiceInterface;
use App\Repositories\BalanceTransactionRepository;
use App\Repositories\BetRepository;
use App\Repositories\BetSelectionsRepository;
use App\Repositories\PlayerRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class BetService extends MainService implements ServiceInterface
{
    protected $playerRepository;
    protected $balanceTransactionRepository;
    protected $betRepository;
    protected $betSelectionsRepository;

    /**
     * PlayerRepository constructor.
     * @param PlayerRepository $playerRepository
     * @param BalanceTransactionRepository $balanceTransactionRepository
     * @param BetRepository $betRepository
     * @param BetSelectionsRepository $betSelectionsRepository
     */
    public function __construct(
        PlayerRepository $playerRepository,
        BalanceTransactionRepository $balanceTransactionRepository,
        BetRepository $betRepository,
        BetSelectionsRepository $betSelectionsRepository
    ) {
        parent::__construct();
        $this->playerRepository = $playerRepository;
        $this->balanceTransactionRepository = $balanceTransactionRepository;
        $this->betRepository = $betRepository;
        $this->betSelectionsRepository = $betSelectionsRepository;
    }

    /**
     * @param array $parameters
     * @return mixed
     * @throws Exception
     */
    public function createBet(array $parameters)
    {
        try {
            $player = $this->findOrCreatePlayer($parameters['player_id']);
            $this->updatePlayerLockStatus($player->id, 1);
            $this->validateBet($parameters, $player);
            DB::beginTransaction();
            $createdBet = $this->insertBetData($parameters, $player);
            $this->insertBetSelectionsData($parameters['selections'], (object)$createdBet);
            $this->insertBetTransactionData($parameters, $player);
            $this->updatePlayerLockStatus($player->id, 0);
            DB::commit();
            return true;
        } catch (Exception $e) {
            $this->updatePlayerLockStatus($parameters['player_id'], 0);
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param int $playerId
     * @return mixed
     * @throws Exception
     */
    public function findOrCreatePlayer(int $playerId)
    {
        try {
            $findArray = ['id' => $playerId];
            $insertData = ['balance' => PLAYER_DEFAULT_BALANCE];
            return $this->playerRepository->findOrCreate($findArray, $insertData);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $parameters
     * @param object $player
     * @throws Exception
     */
    public function validateBet(array $parameters, object $player)
    {
        try {
            $this->validateMaxWinAmount($parameters);
            $this->validateBalance($parameters, $player);
            $this->validatePlayerPreviousActionStatus($player);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $parameters
     * @param object $player
     * @throws Exception
     */
    public function playerReduceBalance(array $parameters, object $player)
    {
        try {
            $newBalance = $player->balance - $parameters['stake_amount'];
            $this->playerRepository->update(['balance' => $newBalance], $player->id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $playerId
     * @param int $status
     */
    public function updatePlayerLockStatus(int $playerId, int $status)
    {
        $this->playerRepository->update(['lock_status' => $status], $playerId);
    }


    /**
     * @param object $player
     * @throws ValidationException
     */
    public function validatePlayerPreviousActionStatus(object $player)
    {
        if ($player->lock_status) {
            throw new ValidationException([], [[PRE_ACTION_ERROR_CODE . ',' . PRE_ACTION_ERROR_MESSAGE]]);
        }
    }

    /**
     * @param array $parameters
     * @param object $player
     * @throws ValidationException
     */
    public function validateBalance(array $parameters, object $player)
    {
        if ($parameters['stake_amount'] > $player->balance) {
            throw new ValidationException($parameters, [[INS_BAL_ERROR_CODE . ',' . INS_BAL_ERROR_MESSAGE]]);
        }
    }

    /**
     * @param array $parameters
     * @throws ValidationException
     */
    public function validateMaxWinAmount(array $parameters)
    {
        $collection = collect($parameters['selections']);
        $winAmount = $collection->reduce(function ($carry, $item) {
            return $carry * $item['odds'];
        }, $parameters['stake_amount']);
        if ($winAmount > MAX_WIN_AMOUNT) {
            throw new ValidationException($parameters, [[MAX_WIN_AMOUNT_ERROR_CODE . ',' . MAX_WIN_AMOUNT_ERROR_MESSAGE . MAX_WIN_AMOUNT]]);
        }
    }

    /**
     * @param array $parameters
     * @param object $player
     * @return void
     * @throws Exception
     */
    public function insertBetTransactionData(array $parameters, object $player)
    {
        try {
            $balanceTransactionData = [
                'player_id' => $player->id,
                'amount' => $parameters['stake_amount'],
                'amount_before' => $player->balance,
            ];
            $this->balanceTransactionRepository->create($balanceTransactionData);
            $this->playerReduceBalance($parameters, $player);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $parameters
     * @param object $player
     * @return array
     * @throws Exception
     */
    public function insertBetData(array $parameters, object $player)
    {
        try {
            $insertArray = [
                'player_id' => $player->id,
                'stake_amount' => $parameters['stake_amount'],
            ];
            return $this->betRepository->create($insertArray);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $selections
     * @param object $bet
     * @return array
     * @throws Exception
     */
    public function insertBetSelectionsData(array $selections, object $bet)
    {
        try {
            $currentTime = date(MYSQL_DATETIME_FORMAT);
            $betSelectionsInsertData = array_map(function ($selection) use ($bet, $currentTime) {
                return [
                    'bet_id' => $bet->id,
                    'selection_id' => $selection['id'],
                    'odds' => $selection['odds'],
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
            }, $selections);
            $this->betSelectionsRepository->insert($betSelectionsInsertData);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
