<?php

declare(strict_types=1);

namespace App\Tests\Handler;

use App\Exception\InsufficientBalanceException;
use App\Handler\BetHandler;
use App\Request\BetRequest;
use App\Service\BetService;
use App\Validator\BetValidator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BetHandlerTest extends TestCase
{
    /** @var BetValidator */
    private $validator;

    /** @var BetService */
    private $betService;

    /** @var LoggerInterface */
    private $logger;

    /** @var BetHandler */
    private $handler;

    public function setUp(): void
    {
        $this->validator = $this->createMock(BetValidator::class);
        $this->betService = $this->createMock(BetService::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->handler = new BetHandler(
            $this->validator,
            $this->betService,
            $this->logger
        );
    }

    public function testHandleSuccess(): void
    {
        $request = new Request();
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest));

        $this->betService->expects($this->once())
            ->method('saveBet')
            ->with($this->equalTo($betRequest));


        $expected = new Response(null, Response::HTTP_CREATED);
        $this->assertEquals($expected, $this->handler->handle($request));
    }

    public function testHandleError(): void
    {
        $request = new Request();
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest));

        $this->betService->expects($this->once())
            ->method('saveBet')
            ->with($this->equalTo($betRequest))
            ->willThrowException(new InsufficientBalanceException());

        $message = '{"errors":[{"code":11,"message":"Insufficient balance"}]}';

        $this->logger->expects($this->once())
            ->method('warning')
            ->with($this->equalTo($message), $this->isType('array'));

        $expected = new Response($message, Response::HTTP_BAD_REQUEST);
        $this->assertEquals($expected, $this->handler->handle($request));
    }
}
