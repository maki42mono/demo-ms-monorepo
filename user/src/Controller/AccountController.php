<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\AccountService;
use Legion\LegionBundle\Entity\ApiResponse;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * AccountController
 * @Route("/back-api/user/account")
 */
class AccountController extends AbstractController
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
     * @Route("/info", methods={"GET"})
     * @param Request $request
     * @param AccountService $service
     * @return ApiResponse
     */
    public function info(Request $request, AccountService $service): ApiResponse
    {
        $this->logger->info("AccountController#info");

        $token = $request->headers->get("authorization");
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $email = $jwtPayload->email;

        if (empty($email)) {
            throw new BadRequestHttpException("User email not found");
        }

        return new ApiResponse(
            "",
            $service->getAccountInfo($email)
        );
    }

    /**
     * @Route("/pro-info", methods={"GET"})
     * @param Request $request
     * @param AccountService $service
     * @return ApiResponse
     */
    public function proInfo(Request $request, AccountService $service): ApiResponse
    {
        $this->logger->info("AccountController#pro-info");

        $token = $request->headers->get("authorization");
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $email = $jwtPayload->email;

        if (empty($email)) {
            throw new BadRequestHttpException("Pro email not found");
        }

        return new ApiResponse(
            "",
            $service->getAccountProInfo($email, $token)
        );
    }
}