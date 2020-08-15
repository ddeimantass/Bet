<?php

declare(strict_types=1);

namespace App\Controller;

use App\Handler\BetHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/bet")
 */
class BetController extends AbstractController
{
    private BetHandler $betHandler;

    public function __construct(BetHandler $betHandler)
    {
        $this->betHandler = $betHandler;
    }


    /**
     * @Route("", methods="POST")
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->betHandler->handle($request);
    }
}
