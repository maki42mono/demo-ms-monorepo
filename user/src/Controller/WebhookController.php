<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * OrderController
 * @Route("/back-api/user/webhook")
 */
class WebhookController extends AbstractController
{
    /**
     * @var string
     */
    private $xGitlabToken;

    public function __construct(string $xGitlabToken)
    {
        $this->xGitlabToken = $xGitlabToken;
    }

    /**
     * @Route("/repo-update", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function masterUpdate(Request $request)
    : Response {
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);
        try {
            $root         = __DIR__ . '/../..';
            $token        = $request->headers->get('x-gitlab-token');
            $body         = json_decode($request->getContent(), true);
            $targetBranch = $body['object_attributes']['target_branch'] ?? null;
            $event        = $body['object_attributes']['action'] ?? $body['event_name'] ?? null;

            if ($token !== $this->xGitlabToken) {
                return $response;
            }

            $cmd = null;
            if ('merge' === $event && 'master' === $targetBranch) {
                $cmd = "/bin/sh $root/sh/master.sh $root";
            } else if ('push' === $event) {
                $cmd = "/bin/sh $root/sh/stage.sh $root";
            }
            $cmd && exec($cmd);

        } catch (\Exception $e) {
            $response = $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}