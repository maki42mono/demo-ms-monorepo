<?php

namespace App\Service;

use DateTime;
use DateTimeZone;

/**
 * Class BuyerInfoFormService
 * @package App\Service
 */
class BuyerInfoFormService
{
    private $YInfoFormService;

    public function __construct(YInfoFormService $YInfoFormService)
    {
        $this->YInfoFormService = $YInfoFormService;
    }

    public function getParamsList(string $orderHash): array
    {
        $dynamicFormInfo = $this->YInfoFormService->getParams($orderHash);

        return [
            "timeZoneList"     => $this->getTimeZoneList(),
            "timeStartList"    => $this->getFreeHoursTimeListStart(),
            "timeEndList"      => $this->getFreeHoursTimeListEnd(),
            "gameList"         => isset($dynamicFormInfo["games"]) ? $dynamicFormInfo["games"] : null,
            "platformList"     => isset($dynamicFormInfo["platforms"]) ? $dynamicFormInfo["platforms"] : null,
            "crossSaveOptions" => isset($dynamicFormInfo["crossSave"]) ? $dynamicFormInfo["crossSave"] : null,
            "guardianList"     => isset($dynamicFormInfo["guardian"]) ? $dynamicFormInfo["guardian"] : null,
            "gameTagForm"      => isset($dynamicFormInfo["gameTagForm"]) ? $dynamicFormInfo["gameTagForm"] : null,
            "gameTag"          => isset($dynamicFormInfo["gameTag"]) ? $dynamicFormInfo["gameTag"] : null,
            "gameTitle"          => isset($dynamicFormInfo["gameTitle"]) ? $dynamicFormInfo["gameTitle"] : null,
            "realmList"        => isset($dynamicFormInfo["realms"]) ? $dynamicFormInfo["realms"] : null,
            "classList"        => isset($dynamicFormInfo["classes"]) ? $dynamicFormInfo["classes"] : null,
            "fractionList"     => isset($dynamicFormInfo["fractions"]) ? $dynamicFormInfo["fractions"] : null,
            "favoriteBoosters" => isset($dynamicFormInfo["favoriteBoosters"]) ? $dynamicFormInfo["favoriteBoosters"] : null,
            "buyer" => isset($dynamicFormInfo["buyer"]) ? $dynamicFormInfo["buyer"] : null,
        ];
    }

    public function sendInfoForm(array $data): bool
    {
        return $this->YInfoFormService->sendForm($data);
    }

    private function getTimeZoneList(): array
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        $timezoneInfo = [];

        foreach ($timezones as $timezone) {
            $tz = new DateTimeZone($timezone);
            $timezoneInfo[$timezone] = [
                'offset'      => $tz->getOffset(new DateTime),
                'currentTime' => (new DateTime('now', $tz))->format('H:i'),
            ];
        }

        asort($timezoneInfo);
        $list = [];

        foreach ($timezoneInfo as $timezone => $timezoneData) {
            $offsetPrefix = $timezoneData['offset'] < 0 ? '-' : '+';
            $offsetFormatted = gmdate('H:i', abs($timezoneData['offset']));
            $prettyOffset = "UTC${offsetPrefix}${offsetFormatted}";
            $list[$timezone] = "{$timezoneData['currentTime']} (${prettyOffset}) $timezone";
        }

        return $list;
    }

    private function getFreeHoursTimeListStart(): array
    {
        return [
            '12 a.m. (00:00)',
            '1 a.m. (01:00)',
            '2 a.m. (02:00)',
            '3 a.m. (03:00)',
            '4 a.m. (04:00)',
            '5 a.m. (05:00)',
            '6 a.m. (06:00)',
            '7 a.m. (07:00)',
            '8 a.m. (08:00)',
            '9 a.m. (09:00)',
            '10 a.m. (10:00)',
            '11 a.m. (11:00)',
            '12 p.m. (12:00)',
            '1 p.m. (13:00)',
            '2 p.m. (14:00)',
            '3 p.m. (15:00)',
            '4 p.m. (16:00)',
            '5 p.m. (17:00)',
            '6 p.m. (18:00)',
            '7 p.m. (19:00)',
            '8 p.m. (20:00)',
            '9 p.m. (21:00)',
            '10 p.m. (22:00)',
            '11 p.m. (23:00)',
        ];
    }

    private function getFreeHoursTimeListEnd(): array
    {
        return [
            1 => '1 a.m. (01:00)',
            '2 a.m. (02:00)',
            '3 a.m. (03:00)',
            '4 a.m. (04:00)',
            '5 a.m. (05:00)',
            '6 a.m. (06:00)',
            '7 a.m. (07:00)',
            '8 a.m. (08:00)',
            '9 a.m. (09:00)',
            '10 a.m. (10:00)',
            '11 a.m. (11:00)',
            '12 p.m. (12:00)',
            '1 p.m. (13:00)',
            '2 p.m. (14:00)',
            '3 p.m. (15:00)',
            '4 p.m. (16:00)',
            '5 p.m. (17:00)',
            '6 p.m. (18:00)',
            '7 p.m. (19:00)',
            '8 p.m. (20:00)',
            '9 p.m. (21:00)',
            '10 p.m. (22:00)',
            '11 p.m. (23:00)',
            '12 a.m. (24:00)',
        ];
    }
}
