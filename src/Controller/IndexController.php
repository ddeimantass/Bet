<?php

declare(strict_types=1);

namespace App\Controller;

use App\Handler\BetHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private BetHandler $betHandler;

    public function __construct(BetHandler $betHandler)
    {
        $this->betHandler = $betHandler;
    }


    /**
     * @Route("")
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        return new Response('Bet Application');
    }
}
