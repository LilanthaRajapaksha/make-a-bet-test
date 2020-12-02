<?php
namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $errors;
    protected $request;

    public function __construct($request, $errors)
    {
        $this->request = $request;
        $this->errors = $errors;
        parent::__construct();
    }

    /**
     * @return array[]
     */
    public function formattedErrorsData()
    {
        $globalErrors = [];
        $selectionsErrors = [];

        foreach ($this->errors as $key => $datum) {
            $errorMessage = explode(",", $datum[0]);
            if (count($errorMessage) == 2) {
                $newGlobalError = [
                    "code" => $errorMessage[0],
                    "message" => $errorMessage[1]
                ];
                if (!in_array($newGlobalError, $globalErrors)) {
                    $globalErrors[] = $newGlobalError;
                }
            } else {
                $errorMessageContains = explode(".", $errorMessage[2]);
                $key = $errorMessageContains[1];
                if (array_key_exists($this->request['selections'][$key]['id'], $selectionsErrors)) {
                    $newSelectionsError = [
                        "code" => $errorMessage[0],
                        "message" => $errorMessage[1]
                    ];
                    if (!in_array($newSelectionsError, $selectionsErrors[$this->request['selections'][$key]['id']]['errors'])) {
                        $selectionsErrors[$this->request['selections'][$key]['id']]['errors'][] = $newSelectionsError;
                    }
                } else {
                    $selectionsErrors[$this->request['selections'][$key]['id']] = [
                        'id' => $this->request['selections'][$key]['id'],
                        'errors' => [[
                            "code" => $errorMessage[0],
                            "message" => $errorMessage[1]
                        ]]
                    ];
                }
            }
        }
        return $errors = [
            'errors' => $globalErrors,
            'selections' => array_values($selectionsErrors),
        ];
    }
}
