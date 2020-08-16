<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BetSelectionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BetSelectionsRepository::class)
 */
class BetSelections
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bet")
     * @ORM\JoinColumn(name="bet_id")
     */
    private Bet $bet;

    /**
     * @ORM\Column(type="integer")
     */
    private int $selectionId;

    /**
     * @ORM\Column(type="float")
     */
    private float $odds;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Bet
     */
    public function getBet(): Bet
    {
        return $this->bet;
    }

    /**
     * @param Bet $bet
     * @return BetSelections
     */
    public function setBet(Bet $bet): self
    {
        $this->bet = $bet;

        return $this;
    }

    /**
     * @return int
     */
    public function getSelectionId(): int
    {
        return $this->selectionId;
    }

    /**
     * @param int $selectionId
     * @return BetSelections
     */
    public function setSelectionId(int $selectionId): self
    {
        $this->selectionId = $selectionId;

        return $this;
    }

    /**
     * @return float
     */
    public function getOdds(): float
    {
        return $this->odds;
    }

    /**
     * @param float $odds
     * @return BetSelections
     */
    public function setOdds(float $odds): self
    {
        $this->odds = $odds;

        return $this;
    }
}
