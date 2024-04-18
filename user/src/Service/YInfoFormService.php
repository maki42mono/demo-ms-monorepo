<?php
declare(strict_types=1);

namespace App\Service;

use Legion\LegionBundle\Service\LFInternalApiAbstract;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class YInfoFormService
 * @package App\Service
 */
class YInfoFormService extends LFInternalApiAbstract
{
    const URI_PROCESS_OTP = "/user/auth/generate-otp";
    const URI_PROCESS_TOKEN_LINK = "/user/auth/generate-token-link";

    const URI_GET_ORDERS = "/user/order/list";
    const URI_GET_ORDER_COUNTS = "/user/order/counts";

    const URI_GET_ACCOUNT_INFO = "/user/account/info";
    const URI_GET_ACCOUNT_PRO_INFO = "/user/account/pro-info";

    const URI_GET_USER_DATA_BY_EMAIL_PASSWORD = "/user/data/get-by-email-password";
    const URI_GET_USER_DATA_BY_EMAIL = "/user/data/get-by-email";
    const URI_GET_USER_DATA_BY_OTP_LINK_HASH = "/user/data/get-by-otp-link-hash";
    const URI_GET_USER_DATA_BY_OTP_PASSWORD = "/user/data/get-by-otp-password";

    const URI_SET_USER_DATA_OTP_LINK_HASH = "/user/data/set-otp-link-hash";
    const URI_SET_USER_DATA_OTP_PASSWORD = "/user/data/set-otp-password";

    const URI_GET_OFFERS = "/offer/index";
    const URI_GET_OFFER = "/offer/view";

    public function __construct(string $lfApiUrl, string $lfApiToken)
    {
        $this->url = $lfApiUrl;
        $this->token = $lfApiToken;
    }

    /**
     * @param array $data
     * @return array
     */
    public function processOtp(array $data): array
    {
        return $this->request("POST", self::URI_PROCESS_OTP, $data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function processTokenLink(array $data): array
    {
        return $this->request("POST", self::URI_PROCESS_TOKEN_LINK, $data);
    }

    public function getOrders(string $email, ?string $page, string $type): array
    {
        $url = self::URI_GET_ORDERS . "?email=" . $email . "&type=" . $type;
        if ($page !== null) {
            $url .= "&page=" . $page;
        }
        return $this->request("GET", $url);
    }

    public function getOrderCounts(string $email): array
    {
        return $this->request("GET", self::URI_GET_ORDER_COUNTS . "?email=" . $email);
    }

    public function getOffers(array $params): array
    {
        if (!empty($params)) {
            $uri = self::URI_GET_OFFERS. '?'. http_build_query($params);
        } else {
            $uri = self::URI_GET_OFFERS;
        }
        return $this->request("GET", $uri);
    }

    public function getOffer(array $params): array
    {
        if (!empty($params)) {
            $uri = self::URI_GET_OFFER. '?'. http_build_query($params);
        } else {
            $uri = self::URI_GET_OFFER;
        }
        return $this->request("GET", $uri);
    }

    public function getAccountInfo(string $email): array
    {
        $res = $this->request("GET", self::URI_GET_ACCOUNT_INFO . "?email=" . $email);

//        throw new BadRequestHttpException(json_encode($res));

        return $res;
    }

    public function getAccountProInfo(string $email, string $token = null): array
    {
        $headers = [];
        if ($token) {
            $headers["Authorization" ] = $token;
        }
        return $this->request("GET", self::URI_GET_ACCOUNT_PRO_INFO . "?email=" . $email, [], $headers);
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $role
     * @return array|null
     */
    public function getUserDataByEmailPassword(string $email, string $password, string $role): ?array
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

        return $res["success"] ? $res["data"] : null;
    }

    /**
     * @param string $email
     * @param string $role
     * @return array|null
     */
    public function getUserDataByEmail(string $email, string $role): ?array
    {
        $res = $this->request(
            "POST",
            self::URI_GET_USER_DATA_BY_EMAIL,
            [
                "email" => $email,
                "role" => $role,
            ]
        );

        return $res["success"] ? $res["data"] : null;
    }

    /**
     * @param string $otpLinkHash
     * @return array|null
     */
    public function getUserDataByOtpLinkHash(string $otpLinkHash): ?array
    {
        $res = $this->request(
            "POST",
            self::URI_GET_USER_DATA_BY_OTP_LINK_HASH,
            [
                "otp_link_hash" => $otpLinkHash,
            ]
        );

        return $res["success"] ? $res["data"] : null;
    }

    /**
     * @param string $otpPassword
     * @return array|null
     */
    public function getUserDataByOtpPassword(string $otpPassword): ?array
    {
        $res = $this->request(
            "POST",
            self::URI_GET_USER_DATA_BY_OTP_PASSWORD,
            [
                "otp_password" => $otpPassword,
            ]
        );

        return $res["success"] ? $res["data"] : null;
    }

    /**
     * @param string $email
     * @param string $role
     * @param string $otpLinkHash
     * @return bool
     */
    public function setUserOtpLinkHash(string $email, string $role, string $otpLinkHash): bool
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
    public function setUserOtpPassword(string $email, string $role, int $otpPassword): bool
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
}