<?php

namespace App\Service;

/**
 * Class UserDataService
 * @package App\Service
 */
class UserDataService
{
    private $YInfoFormService;

    public function __construct(YInfoFormService $YInfoFormService)
    {
        $this->YInfoFormService = $YInfoFormService;
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $role
     * @return array|null
     */
    public function getByEmailPassword(string $email, string $password, string $role): ?array
    {
        $userData = $this->YInfoFormService->getUserDataByEmailPassword($email, $password, $role);

        if (!$userData) {
            return null;
        }

        return [
            "id" => $userData["id"],
            "email" => $userData["email"],
            "role" => $userData["role"],
        ];
    }

    /**
     * @param string $email
     * @param string $role
     * @return array|null
     */
    public function getByEmail(string $email, string $role): ?array
    {
        $userData = $this->YInfoFormService->getUserDataByEmail($email, $role);

        if (!$userData) {
            return null;
        }

        return [
            "id" => $userData["id"],
            "email" => $userData["email"],
            "role" => $userData["role"],
        ];
    }

    /**
     * @param string $otpLinkHash
     * @return array|null
     */
    public function getByOtpLinkHash(string $otpLinkHash): ?array
    {
        $userData = $this->YInfoFormService->getUserDataByOtpLinkHash($otpLinkHash);

        if (!$userData) {
            return null;
        }

        return [
            "id" => $userData["id"],
            "email" => $userData["email"],
            "role" => $userData["role"],
        ];
    }

    /**
     * @param string $otpPassword
     * @return array|null
     */
    public function getByOtpPassword(string $otpPassword): ?array
    {
        $userData = $this->YInfoFormService->getUserDataByOtpPassword($otpPassword);

        if (!$userData) {
            return null;
        }

        return [
            "id" => $userData["id"],
            "email" => $userData["email"],
            "role" => $userData["role"],
        ];
    }

    /**
     * @param string $email
     * @param string $role
     * @param string $otpLinkHash
     * @return bool
     */
    public function setOtpLinkHash(string $email, string $role, string $otpLinkHash): bool
    {
        return $this->YInfoFormService->setUserOtpLinkHash($email, $role, $otpLinkHash);
    }

    /**
     * @param string $email
     * @param string $role
     * @param int $otpPassword
     * @return bool
     */
    public function setOtpPassword(string $email, string $role, int $otpPassword): bool
    {
        return $this->YInfoFormService->setUserOtpPassword($email, $role, $otpPassword);
    }
}