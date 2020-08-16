<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BalanceTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BalanceTransactionRepository::class)
 */
class BalanceTransaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="player_id")
     */
    private Player $player;

    /**
     * @ORM\Column(type="float")
     */
    private float $amount;

    /**
     * @ORM\Column(type="float")
     */
    private float $amountBefore;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @param Player $player
     * @return BalanceTransaction
     */
    public function setPlayer(Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return BalanceTransaction
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmountBefore(): float
    {
        return $this->amountBefore;
    }

    /**
     * @param float $amountBefore
     * @return BalanceTransaction
     */
    public function setAmountBefore(float $amountBefore): self
    {
        $this->amountBefore = $amountBefore;

        return $this;
    }
}
