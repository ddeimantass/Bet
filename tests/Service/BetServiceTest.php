<?php

namespace App\Tests\Service;

use App\Entity\Player;
use App\Request\BetRequest;
use App\Service\BetService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class BetServiceTest extends TestCase
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BetService */
    private $betService;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->betService = new BetService($this->entityManager);
    }

    public function testSaveBet()
    {
        $content = '{
            "player_id": 1,
            "stake_amount": "100",
            "selections": [
                {
                    "id": 1,
                    "odds": "2"
                },
                {
                    "id": 2,
                    "odds": "3"
                }
            ]
        }';
        $request = new Request([], [], [], [], [], [], $content);
        $betRequest = new BetRequest($request);

        $this->entityManager->expects($this->once())
            ->method('find')
            ->with($this->equalTo(Player::class), $this->equalTo(1))
            ->willReturn(null);

        $this->entityManager->expects($this->exactly(5))
            ->method('persist')
            ->with($this->anything());

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->betService->saveBet($betRequest);
    }
}
