<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidationException;
use App\Services\APIService;
use App\Services\BetService;
use App\Traits\BetTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BetController extends Controller
{
    use BetTrait;

    protected $apiService;
    protected $betService;

    /**
     * VehicleController constructor.
     * @param APIService $apiService
     * @param BetService $betService
     */
    public function __construct(
        APIService $apiService,
        BetService $betService
    ) {
        $this->apiService = $apiService;
        $this->betService = $betService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->validateStoreRequest($request);
            if ($validator->fails()) {
                throw new ValidationException($request->all(), $validator->errors()->toArray());
            }
            $parameters = $this->getStoreRequestParameters($request);
            $this->betService->createBet($parameters);
            return $this->apiService->respond(null, 201);
        } catch (ValidationException $ex) {
            Log::error($ex);
            return $this->apiService->respondError($ex->formattedErrorsData(), 400);
        } catch (Exception $ex) {
            Log::error($ex);
            return $this->apiService->respondError(["errors" => [[
                "code" => UNKNOWN_ERROR_CODE,
                "message" => UNKNOWN_ERROR_MESSAGE
            ]]], 400);
        }
    }
}
