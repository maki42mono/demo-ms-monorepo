<?php

namespace App\Service;

/**
 * Class OrderService
 * @package App\Service
 */
class OrderService
{
    private $YInfoFormService;

    public function __construct(YInfoFormService $YInfoFormService)
    {
        $this->YInfoFormService = $YInfoFormService;
    }

    /**
     * @param string $email
     * @param string|null $page
     * @param string $type
     * @return array|null
     */
    public function getUsersOrders(string $email, ?string $page, string $type): ?array
    {
        $res = $this->YInfoFormService->getOrders($email, $page, $type);

        if (isset($res["success"]) && $res["success"] && !empty($res["data"])) {
            return $res["data"];
        }

        return [];
    }

    /**
     * @param string $email
     * @param int $page
     * @param string $type
     * @return array|null
     */
    public function getUsersOrderCounts(string $email): ?array
    {
        $res = $this->YInfoFormService->getOrderCounts($email);

        if (isset($res["success"]) && $res["success"] && !empty($res["data"])) {
            return $res["data"];
        }

        return [];
    }
}