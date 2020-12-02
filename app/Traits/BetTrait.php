<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Validator;

trait BetTrait
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function validateStoreRequest(Request $request)
    {
        $rules = [
            'player_id' => ['required'],
            'stake_amount' => ['required', 'numeric', 'min:' . MIN_STAKE_AMOUNT, 'max:' . MAX_STAKE_AMOUNT, 'regex:' . TWO_DECIMAL_REGEX],
            'selections' => ['array', 'min:' . MIN_SELECTIONS, 'max:' . MAX_SELECTIONS, 'required'],
            'selections.*.id' => ['required', 'integer', 'distinct'],
            'selections.*.odds' => ['required', 'numeric', 'min:' . MIN_ODDS, 'max:' . MAX_ODDS, 'regex:' . THREE_DECIMAL_REGEX],
        ];

        $messages = [
            'required' => STRUCTURE_MISMATCH_ERROR_CODE . ',' . STRUCTURE_MISMATCH_ERROR_MESSAGE,
            'stake_amount.numeric' => STRUCTURE_MISMATCH_ERROR_CODE . ',' . STRUCTURE_MISMATCH_ERROR_MESSAGE,
            'stake_amount.min' => MIN_STAKE_AMOUNT_ERROR_CODE . ',' . MIN_STAKE_AMOUNT_ERROR_MESSAGE . MIN_STAKE_AMOUNT,
            'stake_amount.max' => MAX_STAKE_AMOUNT_ERROR_CODE . ',' . MAX_STAKE_AMOUNT_ERROR_MESSAGE . MAX_STAKE_AMOUNT,
            'stake_amount.regex' => STRUCTURE_MISMATCH_ERROR_CODE . ',' . STRUCTURE_MISMATCH_ERROR_MESSAGE,
            'selections.array' => STRUCTURE_MISMATCH_ERROR_CODE . ',' . STRUCTURE_MISMATCH_ERROR_MESSAGE,
            'selections.min' => MIN_SELECTIONS_ERROR_CODE . ',' . MIN_SELECTIONS_ERROR_MESSAGE . MIN_SELECTIONS,
            'selections.max' => MAX_SELECTIONS_ERROR_CODE . ',' . MAX_SELECTIONS_ERROR_MESSAGE . MAX_SELECTIONS,
            'selections.*.id.distinct' => DUPLICATE_SELECTION_ERROR_CODE . ',' . DUPLICATE_SELECTION_ERROR_MESSAGE . ',:attribute',
            'selections.*.odds.required' => STRUCTURE_MISMATCH_ERROR_CODE . ',' . STRUCTURE_MISMATCH_ERROR_MESSAGE . ',:attribute',
            'selections.*.odds.numeric' => STRUCTURE_MISMATCH_ERROR_CODE . ',' . STRUCTURE_MISMATCH_ERROR_MESSAGE . ',:attribute',
            'selections.*.odds.min' => MIN_ODD_ERROR_CODE . ',' . MIN_ODD_ERROR_MESSAGE . MIN_ODDS . ',:attribute',
            'selections.*.odds.max' => MAX_ODD_ERROR_CODE . ',' . MAX_ODD_ERROR_MESSAGE . MAX_ODDS . ',:attribute',
            'selections.*.odds.regex' => STRUCTURE_MISMATCH_ERROR_CODE . ',' . STRUCTURE_MISMATCH_ERROR_MESSAGE . ',:attribute',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getStoreRequestParameters(Request $request)
    {
        $parameters = [];
        if ($request->has('player_id')) {
            $parameters['player_id'] = $request->input('player_id');
        }
        if ($request->has('stake_amount')) {
            $parameters['stake_amount'] = $request->input('stake_amount');
        }
        if ($request->has('selections')) {
            $parameters['selections'] = $request->input('selections');
        }
        return $parameters;
    }
}
