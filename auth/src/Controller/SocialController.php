<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\SocialService;
use App\Service\UserDataService;
use App\Service\UserService;
use Legion\LegionBundle\Entity\ApiResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * SocialController
 * @Route("/back-api/auth/social")
 */
class SocialController extends BaseController
{
    /**
     * @Route("/token", methods={"POST"})
     *
     * @SWG\Response(
     *      response="200",
     *      description="Get JWT"
     * )
     * @SWG\Parameter(
     *     in="query",
     *     name="service",
     *     required=true,
     *     type="string",
     *     enum={"google", "facebook", "discord"},
     *     description="social network provider name",
     * )
     * @SWG\Parameter(
     *     in="query",
     *     name="token",
     *     required=true,
     *     type="string",
     *     description="access token from provider"
     * )
     *
     * @param Request $request
     * @param UserDataService $userDataService
     * @param UserService $userService
     * @param SocialService $socialService
     * @return ApiResponse
     * @throws JWTEncodeFailureException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function token(
        Request $request,
        UserDataService $userDataService,
        UserService $userService,
        SocialService $socialService
    ): ApiResponse {
        $this->logger->info(self::getNameLog($request));
        $body = self::getBodyRequest($request);

        if (empty($body["token"]) || empty($body["service"])) {
            throw new BadRequestHttpException();
        }

        $info = $socialService->getUserInfo($body["service"], $body["token"]);

        if (empty($info["email"])) {
            throw new BadRequestHttpException("The user did not grant the required permissions.");
        }

        try {
            $userData = $userDataService->getByEmail($info["email"], "user");
        } catch (\Exception $exception) {
            // TODO: проверять что не найден!
            $userData = $userDataService->createUser($info["email"], $info["name"], $info["avatar"]);
        }

        if (empty($userData)) {
            throw new BadRequestHttpException("User not found");
        }

        $jwt = $userService->generateJWT($userData["id"], $userData["email"], $userData["role"]);
        $payload = $userService->getJWTPayload($jwt);

        if (!$payload) {
            throw new BadRequestHttpException("Token generation error");
        }

        if (!$userDataService->setUserJwt($userData["email"], "user", $jwt, $payload['exp'])) {
            throw new BadRequestHttpException("Token generation error");
        }

        return new ApiResponse("JWT created", ["jwt" => $jwt]);
    }
}
