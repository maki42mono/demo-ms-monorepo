<?php

namespace App\Service;

/**
 * Class AccountService
 * @package App\Service
 */
class AccountService
{
    private $YInfoFormService;

    public function __construct(YInfoFormService $YInfoFormService)
    {
        $this->YInfoFormService = $YInfoFormService;
    }

    /**
     * @param string $email
     * @return array|null
     */
    public function getAccountInfo(string $email): ?array
    {
        $response = $this->YInfoFormService->getAccountInfo($email);
        return isset($response["success"]) && $response["success"] ? $response["data"] : null;
    }

    /**
     * @param string $email
     * @param string|null $token
     * @return array|null
     */
    public function getAccountProInfo(string $email, string $token = null): ?array
    {
        $response = $this->YInfoFormService->getAccountProInfo($email, $token);
        return isset($response["success"]) && $response["success"] ? $response["data"] : null;
    }
}