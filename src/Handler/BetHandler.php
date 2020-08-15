<?php

declare(strict_types=1);

namespace App\Handler;

use App\Constant\ErrorConstant;
use App\Exception\CustomException;
use App\Request\BetRequest;
use App\Service\BetService;
use App\Validator\BetValidator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BetHandler
{
    private BetValidator $validator;
    private BetService $betService;
    private LoggerInterface $logger;

    public function __construct(
        BetValidator $validator,
        BetService $betService,
        LoggerInterface $logger
    ) {
        $this->validator = $validator;
        $this->betService = $betService;
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        try {
            $betRequest = new BetRequest($request);
            $this->validator->validate($betRequest);
            $this->betService->saveBet($betRequest);

            return new Response(null, Response::HTTP_CREATED);
        } catch (CustomException $exception) {
            $this->logger->warning($exception->getMessage(), $exception->getTrace());

            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());

            $errors['errors'][] = ErrorConstant::getErrorByCode(0);

            return new Response(\json_encode($errors), Response::HTTP_BAD_REQUEST);
        }
    }
}
