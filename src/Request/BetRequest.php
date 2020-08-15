<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use App\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class BetRequest
{
    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Positive()
     * @Assert\Type("int")
     */
    private $playerId;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @AppAssert\MaxNumbersAfterDot(2)
     */
    private $stakeAmount;

    /**
     * @var array
     * @Assert\Type("array")
     * @Assert\All(
     *     @Assert\Collection(
     *         fields = {
     *             "id" = {
     *                 @Assert\NotBlank(),
     *                 @Assert\Positive(),
     *                 @Assert\Type("int")
     *             },
     *             "odds" = {
     *                  @Assert\NotBlank,
     *                  @AppAssert\MaxNumbersAfterDot(3),
     *                  @Assert\Type("string")
     *               }
     *          }
     *     )
     * )
     */
    private $selections;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent() ?? '', true);
        $this->playerId = $data['player_id'] ?? null;
        $this->stakeAmount = $data['stake_amount'] ?? null;
        $this->selections = $data['selections'] ?? null;
    }

    /**
     * @return int
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    /**
     * @return string
     */
    public function getStakeAmount(): string
    {
        return $this->stakeAmount;
    }

    /**
     * @return array
     */
    public function getSelections(): array
    {
        return $this->selections;
    }
}
