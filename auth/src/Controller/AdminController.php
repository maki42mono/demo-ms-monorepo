<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\UserDataService;
use App\Service\UserService;
use Legion\LegionBundle\Entity\ApiResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * AdminController
 * @Route("/back-api/auth/admin")
 */
class AdminController extends AbstractController
{
    /* @var LoggerInterface */
    private $logger;

    /**
     * AdminController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/credentials", methods={"POST"})
     *
     * @param Request $request
     * @param UserDataService $userDataService
     * @param UserService $userService
     * @return ApiResponse
     * @throws JWTEncodeFailureException
     */
    public function credentials(Request $request, UserDataService $userDataService, UserService $userService): ApiResponse
    {
        $this->logger->info("AdminController#credentials");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["email"]) || empty($decodedBody["password"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        //TODO to think about roles
        $userData = $userDataService->getByEmailPassword(
            $decodedBody["email"],
            $decodedBody["password"],
            "backend"
        );

        if (empty($userData)) {
            throw new BadRequestHttpException("User not found");
        }

        $jwt = $userService->generateJWT($userData["id"], $userData["email"], $userData["role"]);
        $payload = $userService->getJWTPayload($jwt);

        if (!$payload) {
            throw new BadRequestHttpException("Token generation error");
        }

        if (!$userDataService->setUserJwt($userData["email"], "backend", $jwt, $payload['exp'])) {
            throw new BadRequestHttpException("Token generation error");
        }

        return new ApiResponse("JWT created", ["jwt" => $jwt]);
    }
}
