<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * DefaultController
 * @Route("/back-api/user")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @return Response
     */
    public function healthCheck(): Response
    {
        return new Response(null, Response::HTTP_OK);
    }
}