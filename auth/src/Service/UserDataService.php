<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

//TODO to move this service to library
class UserDataService
{
    const URI_GET_USER_DATA_BY_EMAIL_PASSWORD = '/data/get-by-email-password';
    const URI_GET_USER_DATA_BY_EMAIL = "/data/get-by-email";
    const URI_GET_USER_DATA_BY_OTP_LINK_HASH = "/data/get-by-otp-link-hash";
    const URI_GET_USER_DATA_BY_OTP_PASSWORD = "/data/get-by-otp-password";

    const URI_SET_USER_DATA_OTP_LINK_HASH = "/data/set-otp-link-hash";
    const URI_SET_USER_DATA_OTP_PASSWORD = "/data/set-otp-password";

    /** @var string */
    protected $url;

    /** @var CurlHttpClient */
    protected $client;

    /** @var YInfoFormService */
    protected $yInfoFormService;

    public function __construct(string $userServiceApiUrl, YInfoFormService $yInfoFormService)
    {
        $this->url = $userServiceApiUrl;
        $this->client = HttpClient::create();
        $this->yInfoFormService = $yInfoFormService;
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $role
     * @return array|null
     */
    public function getByEmailPassword(string $email, string $password, string $role): ?array
    {
        $res = $this->request(
            "POST",
            self::URI_GET_USER_DATA_BY_EMAIL_PASSWORD,
            [
                "email" => $email,
                "password" => $password,
                "role" => $role,
            ]
        );

        if (!$res["success"]) {
            return null;
        }

        //TODO DTO
        return [
            "id" => $res["data"]["id"],
            "email" => $res["data"]["email"],
            "role" => $res["data"]["role"],
        ];
    }

    /**
     * @param string $email
     * @param string $role
     * @return array|null
     */
    public function getByEmail(string $email, string $role): ?array
    {
        $res = $this->request(
            "POST",
            self::URI_GET_USER_DATA_BY_EMAIL,
            [
                "email" => $email,
                "role" => $role,
            ]
        );

        if (!$res["success"]) {
            return null;
        }

        //TODO DTO
        return [
            "id" => $res["data"]["id"],
            "email" => $res["data"]["email"],
            "role" => $res["data"]["role"],
        ];
    }

    /**
     * @param string $otpLinkHash
     * @return array|null
     */
    public function getByOtpLinkHash(string $otpLinkHash): ?array
    {
        $res = $this->request(
            "POST",
            self::URI_GET_USER_DATA_BY_OTP_LINK_HASH,
            [
                "otp_link_hash" => $otpLinkHash,
            ]
        );

        if (!$res["success"]) {
            return null;
        }

        //TODO DTO
        return [
            "id" => $res["data"]["id"],
            "email" => $res["data"]["email"],
            "role" => $res["data"]["role"],
        ];
    }

    /**
     * @param string $otpPassword
     * @return array|null
     */
    public function getByOtpPassword(string $otpPassword): ?array
    {
        $res = $this->request(
            "POST",
            self::URI_GET_USER_DATA_BY_OTP_PASSWORD,
            [
                "otp_password" => $otpPassword,
            ]
        );

        if (!$res["success"]) {
            return null;
        }

        //TODO DTO
        return [
            "id" => $res["data"]["id"],
            "email" => $res["data"]["email"],
            "role" => $res["data"]["role"],
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
        $res = $this->request(
            "POST",
            self::URI_SET_USER_DATA_OTP_LINK_HASH,
            [
                "email" => $email,
                "role" => $role,
                "otp_link_hash" => $otpLinkHash,
            ]
        );

        return $res["success"];
    }

    /**
     * @param string $email
     * @param string $role
     * @param int $otpPassword
     * @return bool
     */
    public function setOtpPassword(string $email, string $role, int $otpPassword): bool
    {
        $res = $this->request(
            "POST",
            self::URI_SET_USER_DATA_OTP_PASSWORD,
            [
                "email" => $email,
                "role" => $role,
                "otp_password" => $otpPassword,
            ]
        );

        return $res["success"];
    }

    /**
     * TODO Temporary method - see https://app.diagrams.net/#G1P1uryoT5lSDqJnmwXOWStU5v96-awBxh
     * TODO Will be moved to redis
     *
     * @param string $email
     * @param string $role
     * @param string $jwt
     * @param int $exp
     * @return bool
     */
    public function setUserJwt(string $email, string $role, string $jwt, int $exp): bool
    {
        return $this->yInfoFormService->setUserJwt($email, $role, $jwt, $exp);
    }

    /**
     * TODO Temporary method - see https://app.diagrams.net/#G1P1uryoT5lSDqJnmwXOWStU5v96-awBxh
     * TODO Will be moved to redis
     *
     * @param string $jwt
     * @return array|null
     */
    public function getByJwt(string $jwt): ?array
    {
        $data = $this->yInfoFormService->getUserByJwt($jwt);

        //TODO DTO
        return [
            "id" => $data["id"],
            "email" => $data["email"],
            "role" => $data["role"],
        ];
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $avatar
     * @return array
     */
    public function createUser(string $email, string $name, $avatar = "")
    {
        //TODO to move to user microservice from yInfoFormService
        $data = $this->yInfoFormService->createUser($email, $name, $avatar);

        //TODO DTO
        return [
            "id" => $data["id"],
            "email" => $data["email"],
            "role" => $data["role"],
        ];
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return array
     */
    protected function request(string $method, string $uri, array $data = [], array $headers = []): array
    {
        $options = [
            "headers" => array_merge($headers, ["Content-Type" => "application/json"])
        ];

        if (!empty($data)) {
            $options['body'] = json_encode($data);
        }

        $url = $this->url . $uri;
        try {
            $result = $this->client->request($method, $url, $options);
            return json_decode($result->getContent(), true);
        } catch (ExceptionInterface $exception) {
            throw new BadRequestHttpException($exception->getMessage() . '. Error with request to: ' . $url);
        }
    }
}
