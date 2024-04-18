<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\UserService;
use Legion\LegionBundle\Entity\ApiResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * AuthenticationController
 * @Route("/back-api/auth/token")
 */
class TokenController extends AbstractController
{
    /* @var LoggerInterface */
    private $logger;

    /**
     * TestController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/verify", methods={"GET"})
     *
     * @param Request $request
     * @param UserService $service
     * @return ApiResponse
     */
    public function verify(Request $request, UserService $service): ApiResponse
    {
        $this->logger->info("TokenController#verify");
        $authorizationHeader = $request->headers->get("Authorization");
        $authorizationHeader = substr($authorizationHeader, 7);
        $this->logger->info($authorizationHeader);

        if (!$service->validateJwtToken($authorizationHeader)) {
            throw new BadRequestHttpException();
        }

        return new ApiResponse("verified", []);
    }

    /**
     * @Route("/otp-create", methods={"POST"})
     *
     * @param Request $request
     * @param UserService $service
     * @return ApiResponse
     * @throws JWTEncodeFailureException
     */
    public function otpCreate(Request $request, UserService $service): ApiResponse
    {
        $this->logger->info("TokenController#otpCreate");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["email"]) || empty($decodedBody["otp"])) {
            throw new BadRequestHttpException();
        }

        $token = $service->getJWTByOtp($decodedBody["email"], $decodedBody["otp"]);

        if (empty($token)) {
            throw new BadRequestHttpException("Token not received");
        }

        return new ApiResponse("Token created", ["token" => $token]);
    }

    /**
     * @Route("/token-link-create", methods={"POST"})
     *
     * @param Request $request
     * @param UserService $service
     * @return ApiResponse
     * @throws JWTEncodeFailureException
     */
    public function tokenLinkCreate(Request $request, UserService $service): ApiResponse
    {
        $this->logger->info("TokenController#tokenLinkCreate");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["token"])) {
            throw new BadRequestHttpException();
        }

        $token = $service->getJWTByTokenLink($decodedBody["token"]);

        if (empty($token)) {
            throw new BadRequestHttpException("Token not received");
        }

        return new ApiResponse("Token created", ["token" => $token]);
    }
}
