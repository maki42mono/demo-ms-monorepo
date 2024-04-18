<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\UserDataService;
use Legion\LegionBundle\Entity\ApiResponse;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * DataController
 * @Route("/back-api/user/data")
 */
class DataController extends AbstractController
{
    /* @var LoggerInterface */
    private $logger;

    /**
     * DataController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/get-by-email-password", methods={"POST"})
     * @param Request $request
     * @param UserDataService $service
     * @return ApiResponse
     */
    public function getByEmailPassword(Request $request, UserDataService $service): ApiResponse
    {
        $this->logger->info("DataController#getByEmailPassword");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["email"]) || empty($decodedBody["password"]) || empty($decodedBody["role"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        $userData = $service->getByEmailPassword($decodedBody["email"], $decodedBody["password"], $decodedBody["role"]);

        if (!$userData) {
            throw new NotFoundHttpException("User not found");
        }

        return new ApiResponse(
            "User data",
            [
                "id" => $userData["id"],
                "email" => $userData["email"],
                "role" => $userData["role"],
            ]
        );
    }

    /**
     * @Route("/get-by-email", methods={"POST"})
     * @param Request $request
     * @param UserDataService $service
     * @return ApiResponse
     */
    public function getByEmail(Request $request, UserDataService $service): ApiResponse
    {
        $this->logger->info("DataController#getByEmail");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["email"]) || empty($decodedBody["role"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        $userData = $service->getByEmail($decodedBody["email"], $decodedBody["role"]);

        if (!$userData) {
            throw new NotFoundHttpException("User not found");
        }

        return new ApiResponse(
            "User data",
            [
                "id" => $userData["id"],
                "email" => $userData["email"],
                "role" => $userData["role"],
            ]
        );
    }

    /**
     * @Route("/get-by-otp-link-hash", methods={"POST"})
     * @param Request $request
     * @param UserDataService $service
     * @return ApiResponse
     */
    public function getByOtpLinkHash(Request $request, UserDataService $service): ApiResponse
    {
        $this->logger->info("DataController#getByOtpLinkHash");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["otp_link_hash"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        $userData = $service->getByOtpLinkHash($decodedBody["otp_link_hash"]);

        if (!$userData) {
            throw new NotFoundHttpException("User not found");
        }

        return new ApiResponse(
            "User data",
            [
                "id" => $userData["id"],
                "email" => $userData["email"],
                "role" => $userData["role"],
            ]
        );
    }

    /**
     * @Route("/get-by-otp-password", methods={"POST"})
     * @param Request $request
     * @param UserDataService $service
     * @return ApiResponse
     */
    public function getByOtpPassword(Request $request, UserDataService $service): ApiResponse
    {
        $this->logger->info("DataController#getByOtpPassword");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["otp_password"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        $userData = $service->getByOtpPassword($decodedBody["otp_password"]);

        if (!$userData) {
            throw new NotFoundHttpException("User not found");
        }

        return new ApiResponse(
            "User data",
            [
                "id" => $userData["id"],
                "email" => $userData["email"],
                "role" => $userData["role"],
            ]
        );
    }

    /**
     * @Route("/set-otp-link-hash", methods={"POST"})
     * @param Request $request
     * @param UserDataService $service
     * @return ApiResponse
     */
    public function setOtpLinkHash(Request $request, UserDataService $service): ApiResponse
    {
        $this->logger->info("DataController#setOtpLinkHash");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["email"]) || empty($decodedBody["role"]) || empty($decodedBody["otp_link_hash"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        $res = $service->setOtpLinkHash($decodedBody["email"], $decodedBody["role"], $decodedBody["otp_link_hash"]);

        if (!$res) {
            throw new NotFoundHttpException("Cannot set otp_link_hash");
        }

        return new ApiResponse("otp_link_hash was set");
    }

    /**
     * @Route("/set-otp-password", methods={"POST"})
     * @param Request $request
     * @param UserDataService $service
     * @return ApiResponse
     */
    public function setOtpPassword(Request $request, UserDataService $service): ApiResponse
    {
        $this->logger->info("DataController#setOtpPassword");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (empty($decodedBody["email"]) || empty($decodedBody["role"]) || empty($decodedBody["otp_password"])) {
            throw new BadRequestHttpException("Invalid params");
        }

        $res = $service->setOtpPassword($decodedBody["email"], $decodedBody["role"], $decodedBody["otp_password"]);

        if (!$res) {
            throw new NotFoundHttpException("Cannot set otp_password");
        }

        return new ApiResponse("otp_password was set");
    }
}