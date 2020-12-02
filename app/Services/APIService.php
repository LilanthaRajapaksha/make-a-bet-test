<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class APIService
{
    protected $data;
    protected $status_code = 200;

    /**
     * APIService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    private function getStructuredRespondData()
    {
        return $this->getData();
    }

    /**
     * @return array
     */
    private function getStructuredErrorRespondData()
    {
        return $this->getData();
    }

    /**
     * @return mixed
     */
    private function getData()
    {
        return $this->data;
    }

    /**
     * @param $status_code
     */
    private function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }

    /**
     * @param $data
     */
    private function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param null $data
     * @param int $status_code
     * @return JsonResponse
     */
    public function respond($data = null, $status_code = 200)
    {
        $this->setData($data);
        $this->setStatusCode($status_code);
        return response()->json($this->getStructuredRespondData(), $status_code);
    }

    /**
     * @param null $data
     * @param int $status_code
     * @return JsonResponse
     */
    public function respondError($data = null, $status_code = 200)
    {
        $this->setData($data);
        $this->setStatusCode($status_code);
        return response()->json($this->getStructuredErrorRespondData(), $status_code);
    }
}
