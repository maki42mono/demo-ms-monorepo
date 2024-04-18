<?php
declare(strict_types=1);

namespace App\Service;

use Legion\LegionBundle\Service\LFInternalApiAbstract;

/**
 * Class TestInternalApiService
 * @package App\Service
 */
class YInfoFormService extends LFInternalApiAbstract
{
    const URI_GET_USER = "/user/auth/find-user-by-otp";
    const URI_GET_USER_BY_TOKEN = "/user/auth/find-user-by-token";

    const URI_SET_USER_DATA_JWT = "/user/data/set-jwt";
    const URI_GET_USER_DATA_BY_JWT = "/user/data/get-by-jwt";

    const URI_CREATE_USER = "/user/account/create";

    public function __construct(string $lfApiUrl, string $lfApiToken)
    {
        $this->url    = $lfApiUrl;
        $this->token  = $lfApiToken;
    }

    /**
     * @param string $email
     * @param string $otp
     * @return array
     */
    public function findUser(string $email, string $otp): ?array
    {
        $options = [];
        $res = $this->request("GET", self::URI_GET_USER . "?email=" . urlencode($email) . "&otp=" . urlencode($otp), [], [], $options);
        return $res["success"] ? $res["data"] : null;
    }

    /**
     * @param string $token
     * @return array
     */
    public function findUserByToken(string $token): ?array
    {
        $res = $this->request("GET", self::URI_GET_USER_BY_TOKEN . "?token=" . $token);
        return $res["success"] ? $res["data"] : null;
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
        $res = $this->request(
            "POST",
            self::URI_SET_USER_DATA_JWT,
            [
                "jwt" => $jwt,
                "email" => $email,
                "role" => $role,
                "exp" => $exp
            ]
        );

        return $res["success"];
    }

    /**
     * TODO Temporary method - see https://app.diagrams.net/#G1P1uryoT5lSDqJnmwXOWStU5v96-awBxh
     * TODO Will be moved to redis
     *
     * @param string $jwt
     * @return array|null
     */
    public function getUserByJwt(string $jwt): ?array
    {
        $res = $this->request(
            "POST",
            self::URI_GET_USER_DATA_BY_JWT,
            [
                "jwt" => $jwt,
            ]
        );

        return $res["success"] ? $res["data"] : null;
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $avatar
     * @return mixed|null
     */
    public function createUser(string $email, string $name, $avatar = "")
    {
        $res = $this->request(
            "POST",
            self::URI_CREATE_USER,
            [
                "email" => $email,
                "name" => $name,
                "avatar" => $avatar,
            ]
        );

        return $res["success"] ? $res["data"] : null;
    }
}