<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\BalanceTransaction;
use App\Entity\Bet;
use App\Entity\BetSelections;
use App\Entity\Player;
use App\Exception\InsufficientBalanceException;
use App\Request\BetRequest;
use Doctrine\ORM\EntityManagerInterface;

class BetService
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param BetRequest $betRequest
     * @throws InsufficientBalanceException
     */
    public function saveBet(BetRequest $betRequest): void
    {
        $player = $this->getPlayer($betRequest->getPlayerId());

        $stakeAmount = (float)$betRequest->getStakeAmount();
        $balanceBefore = $player->getBalance();
        $currentBalance = $balanceBefore - $stakeAmount;

        if ($currentBalance < 0) {
            throw new InsufficientBalanceException();
        }

        $player->setBalance($currentBalance);

        $balanceTransaction = new BalanceTransaction();
        $balanceTransaction->setPlayer($player)
            ->setAmountBefore($balanceBefore)
            ->setAmount($currentBalance);

        $bet = new Bet();
        $bet->setStakeAmount($stakeAmount);

        $selections = $betRequest->getSelections();
        foreach ($selections as $selection) {
            $betSelections = new BetSelections();
            $betSelections->setBet($bet)
                ->setOdds((float)$selection['odds'])
                ->setSelectionId($selection['id']);

            $this->entityManager->persist($betSelections);
        }

        $this->entityManager->persist($player);
        $this->entityManager->persist($balanceTransaction);
        $this->entityManager->persist($bet);

        $this->entityManager->flush();
    }

    /**
     * @param int $playerId
     * @return Player
     */
    private function getPlayer(int $playerId): Player
    {
        $player = $this->entityManager->find(Player::class, $playerId);
        if (!$player) {
            $player = new Player();
            $player->setId($playerId);
        }

        return $player;
    }
}
