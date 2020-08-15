<?php

declare(strict_types=1);

namespace App\Validator;

use App\Constant\ErrorConstant;
use App\Exception\CustomException;
use App\Exception\StructureException;
use App\Request\BetRequest;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BetValidator
{
    private ValidatorInterface $validator;
    private array $errors;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->errors = [];
    }

    /**
     * @param BetRequest $betRequest
     * @throws Exception
     */
    public function validate(BetRequest $betRequest): void
    {
        $this->validateStructure($betRequest);
        $this->validateStakeAmount($betRequest);
        $this->validateSelectionsCount($betRequest);
        $this->validateWinAmount($betRequest);
        $this->validateSelections($betRequest);

        if ([] !== $this->errors) {
            throw new CustomException(\json_encode($this->errors));
        }
    }

    /**
     * @param BetRequest $betRequest
     * @throws Exception
     */
    private function validateStructure(BetRequest $betRequest): void
    {
        $violations = $this->validator->validate($betRequest);
        if (0 === \count($violations)) {
            return;
        }

        throw new StructureException();
    }

    /**
     * @param BetRequest $betRequest
     */
    private function validateStakeAmount(BetRequest $betRequest): void
    {
        $stakeAmount = $betRequest->getStakeAmount();
        if ($stakeAmount < ErrorConstant::MIN_STAKE_AMOUNT) {
            $this->errors['errors'][] = ErrorConstant::getErrorByCode(2);

            return;
        }

        if ($stakeAmount > ErrorConstant::MAX_STAKE_AMOUNT) {
            $this->errors['errors'][] = ErrorConstant::getErrorByCode(3);
        }
    }

    /**
     * @param BetRequest $betRequest
     */
    private function validateSelectionsCount(BetRequest $betRequest): void
    {
        $selections = \count($betRequest->getSelections());
        if ($selections < ErrorConstant::MIN_SELECTIONS_NUMBER) {
            $this->errors['errors'][] = ErrorConstant::getErrorByCode(4);

            return;
        }

        if ($selections > ErrorConstant::MAX_SELECTIONS_NUMBER) {
            $this->errors['errors'][] = ErrorConstant::getErrorByCode(5);
        }
    }

    /**
     * @param BetRequest $betRequest
     */
    private function validateWinAmount(BetRequest $betRequest): void
    {
        $stakeAmount = $betRequest->getStakeAmount();
        $selections = $betRequest->getSelections();
        $winAmount = $stakeAmount;

        foreach ($selections as $selection) {
            $winAmount *= $selection['odds'];
        }

        if ($winAmount > ErrorConstant::MAX_WIN_AMOUNT) {
            $this->errors['errors'][] = ErrorConstant::getErrorByCode(9);
        }
    }

    /**
     * @param BetRequest $betRequest
     */
    private function validateSelections(BetRequest $betRequest): void
    {
        $selectionErrors = $unique = [];
        $selections = $betRequest->getSelections();
        foreach ($selections as $key => $selection) {
            $id = $selection['id'];
            if (\in_array($id, $unique)) {
                $previousKey = \array_search($id, $unique);
                $selectionErrors[$previousKey]['id'] = $id;
                $selectionErrors[$previousKey]['errors'] = ErrorConstant::getErrorByCode(8);
                $selectionErrors[$key]['id'] = $id;
                $selectionErrors[$key]['errors'] = ErrorConstant::getErrorByCode(8);

                continue;
            }

            $unique[$key] = $id;

            if ($selection['odds'] < ErrorConstant::MIN_ODDS) {
                $selectionErrors[$key]['id'] = $id;
                $selectionErrors[$key]['errors'] = ErrorConstant::getErrorByCode(6);

                continue;
            }

            if ($selection['odds'] > ErrorConstant::MAX_ODDS) {
                $selectionErrors[$key]['id'] = $id;
                $selectionErrors[$key]['errors'] = ErrorConstant::getErrorByCode(7);
            }
        }

        foreach ($selectionErrors as $selection) {
            $this->errors['selections'][] = [
                'id' => $selection['id'],
                'errors' => $selection['errors']
            ];
        }
    }
}
