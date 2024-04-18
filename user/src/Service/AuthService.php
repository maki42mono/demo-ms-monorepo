<?php

namespace App\Service;

use DateTime;

/**
 * Class AuthService
 * @package App\Service
 */
class AuthService
{
    private $YInfoFormService;

    public function __construct(YInfoFormService $YInfoFormService)
    {
        $this->YInfoFormService = $YInfoFormService;
    }

    /**
     * @param string $email
     * @param ?string $role
     * @return bool
     */
    public function generateOtp(string $email, ?string $role = 'buyer'): bool
    {
        $otp = rand(100000, 999999);
        $res = $this->YInfoFormService->processOtp([
            "email"  => $email,
            "role"   => $role,
            "otp"    => $otp,
            "expire" => (new DateTime())->modify("+1 day")->format(DateTime::W3C)
        ]);

        return isset($res["success"]) ? $res["success"] : false;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function generateTokenLink(string $email): bool
    {
        $token = md5(uniqid(rand(), true));

        $res = $this->YInfoFormService->processTokenLink([
            "email" => $email,
            "token" => $token
        ]);

        return isset($res["success"]) ? $res["success"] : false;
    }
}