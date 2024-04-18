<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class BaseController
 * @package App\Controller
 *
 * @property LoggerInterface $logger
 */
class BaseController extends AbstractController
{
    /* @var LoggerInterface */
    protected $logger;

    /**
     * TestController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @return string|string[]
     */
    protected function getNameLog(Request $request)
    {
        $path = $request->attributes->get("_controller");
        $parts = explode("\\", $path);
        $part = $parts[(count($parts) - 1)];
        $part = str_replace("::", "#", $part);

        return $part;
    }

    /**
     * @param Request $request
     * @return false|resource|string
     */
    protected function getBodyRequest(Request $request)
    {
        $content = $request->getContent();

        if (empty($content)) {
            throw new BadRequestHttpException();
        }

        return json_decode($content, true);
    }
}
