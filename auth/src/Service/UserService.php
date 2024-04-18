<?php

namespace App\Service;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;

class UserService
{
    public $yInfoFormService;
    public $jwtEncoder;

    private const SESSION_LIFETIME_SEC = 3600 * 24 * 30;

    public function __construct(JWTEncoderInterface $jwtEncoder, YInfoFormService $yInfoFormService)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->yInfoFormService = $yInfoFormService;
    }

    /**
     * @param $id
     * @param $email
     * @param $role
     * @return string
     * @throws JWTEncodeFailureException
     */
    public function generateJWT($id, $email, $role): string
    {
        return $this->jwtEncoder->encode([
            "id" => $id,
            "email" => $email,
            "role" => $role,
            "exp"   => time() + self::SESSION_LIFETIME_SEC
        ]);
    }

    /**
     * @param string $jwt
     * @return array|null
     */
    public function getJWTPayload(string $jwt): ?array
    {
        try {
            if (!$payload = $this->jwtEncoder->decode($jwt)) {
                return null;
            }
        } catch (JWTDecodeFailureException $e) {
            return null;
        }

        return $payload;
    }

    /**
     * @param string $email
     * @param string $otp
     * @return string|null
     * @throws JWTEncodeFailureException
     */
    public function getJWTByOtp(string $email, string $otp): ?string
    {
        $user = $this->yInfoFormService->findUser($email, $otp);

        if (!$user) {
            return null;
        }

        return $this->jwtEncoder->encode([
            "email" => $user["email"],
            "role" => $user["role"],
            "exp"   => time() + self::SESSION_LIFETIME_SEC
        ]);
    }

    /**
     * @param string $token
     * @return string|null
     * @throws JWTEncodeFailureException
     */
    public function getJWTByTokenLink(string $token): ?string
    {
        $user = $this->yInfoFormService->findUserByToken($token);

        if (!$user) {
            return null;
        }

        return $this->jwtEncoder->encode([
            "email" => $user["email"],
            "exp"   => time() + self::SESSION_LIFETIME_SEC
        ]);
    }

    public function validateJwtToken($token)
    {
        try {
            if (!($payload = $this->jwtEncoder->decode($token))) {
                return false;
            }
        } catch (JWTDecodeFailureException $e) {
            return false;
        }

        $event = new JWTDecodedEvent($payload);

        return $event->isValid();
    }
}
