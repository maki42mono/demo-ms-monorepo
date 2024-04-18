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
 * BuyerController
 * @Route("/back-api/auth/buyer")
 */
class BuyerController extends AbstractController
{
    /* @var LoggerInterface */
    private $logger;

    /**
     * BuyerController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/otp-link", methods={"POST"})
     *
     * @param Request $request
     * @param UserDataService $userDataService
     * @param UserService $userService
     * @return ApiResponse
     * @throws JWTEncodeFailureException
     */
    public function otpLink(Request $request, UserDataService $userDataService, UserService $userService): ApiResponse
    {
        $this->logger->info("BuyerController#otpLink");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["otp_link_hash"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        $userData = $userDataService->getByOtpLinkHash($decodedBody["otp_link_hash"]);

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

    /**
     * @Route("/otp-password", methods={"POST"})
     *
     * @param Request $request
     * @param UserDataService $userDataService
     * @param UserService $userService
     * @return ApiResponse
     * @throws JWTEncodeFailureException
     */
    public function otpPassword(Request $request, UserDataService $userDataService, UserService $userService): ApiResponse
    {
        $this->logger->info("BuyerController#otpPassword");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["otp_password"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        $userData = $userDataService->getByOtpPassword($decodedBody["otp_password"]);

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

    /**
     * @Route("/send-otp-link", methods={"POST"})
     *
     * @param Request $request
     * @param UserDataService $userDataService
     * @param UserService $userService
     * @return ApiResponse
     */
    public function sendOtpLink(Request $request, UserDataService $userDataService, UserService $userService): ApiResponse
    {
        $this->logger->info("BuyerController#sendOtpLink");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["email"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        //TODO to think about roles
        $userData = $userDataService->getByEmail($decodedBody["email"], "user");

        if (empty($userData)) {
            throw new BadRequestHttpException("User not found");
        }

        //TODO to move to service
        $otpLinkHash = md5(uniqid((string) rand(), true));

        if (!$userDataService->setOtpLinkHash($decodedBody["email"], "user", $otpLinkHash)) {
            throw new BadRequestHttpException("Cannot send otp link");
        }

        //TODO send email

        return new ApiResponse("Email with OTP link was sent");
    }

    /**
     * @Route("/send-otp-password", methods={"POST"})
     *
     * @param Request $request
     * @param UserDataService $userDataService
     * @param UserService $userService
     * @return ApiResponse
     */
    public function sendOtpPassword(Request $request, UserDataService $userDataService, UserService $userService): ApiResponse
    {
        $this->logger->info("BuyerController#sendOtpPassword");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["email"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        //TODO to think about roles
        $userData = $userDataService->getByEmail($decodedBody["email"], "user");

        if (empty($userData)) {
            throw new BadRequestHttpException("User not found");
        }

        //TODO to move to service
        $otpPassword = rand(100000, 999999);

        if (!$userDataService->setOtpPassword($decodedBody["email"], "user", $otpPassword)) {
            throw new BadRequestHttpException("Cannot send otp password");
        }

        //TODO send email

        return new ApiResponse("Email with OTP password was sent");
    }
}
