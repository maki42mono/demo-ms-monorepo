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
    const URI_GET_PARAMS = "/order/form-info/params";
    const URI_SEND_DATA = "/order/form-info/send";

    public function __construct(string $lfApiUrl, string $lfApiToken)
    {
        $this->url = $lfApiUrl;
        $this->token = $lfApiToken;
    }

    /**
     * @param string $orderHash
     * @return array|null
     */
    public function getParams(string $orderHash): ?array
    {
        $res = $this->request("GET", self::URI_GET_PARAMS . "?hash=" . $orderHash);
        return $res["success"] ? $res["data"] : null;
    }

    public function sendForm(array $data): bool
    {
        $res = $this->request("POST", self::URI_SEND_DATA, $data);
        return isset($res["success"]) ? $res["success"] : false;
    }
}