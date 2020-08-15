<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Exception\CustomException;
use App\Exception\StructureException;
use App\Request\BetRequest;
use App\Validator\BetValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BetValidatorTest extends TestCase
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var BetValidator */
    private $betValidator;

    public function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->betValidator = new BetValidator($this->validator);
    }

    public function testValidateSuccess(): void
    {
        $content = '{
            "player_id": 1,
            "stake_amount": "1000.71",
            "selections": [
                {
                    "id": 1,
                    "odds": "1.001"
                }
            ]
        }';
        $request = new Request([], [], [], [], [], [], $content);
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest))
            ->willReturn([]);

        $this->assertNull($this->betValidator->validate($betRequest));
    }

    public function testValidateStructure(): void
    {
        $violation = $this->createMock(ConstraintViolationInterface::class);
        $violationList = new ConstraintViolationList([$violation]);
        $request = new Request();
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest))
            ->willReturn($violationList);

        $this->expectException(StructureException::class);
        $this->expectExceptionMessage('{"errors":[{"code":1,"message":"Betslip structure mismatch"}]}');

        $this->betValidator->validate($betRequest);
    }

    public function testValidateStakeAmountMax(): void
    {
        $content = '{
            "player_id": 1,
            "stake_amount": "10000.71",
            "selections": [
                {
                    "id": 1,
                    "odds": "1.001"
                }
            ]
        }';
        $request = new Request([], [], [], [], [], [], $content);
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest))
            ->willReturn([]);

        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('{"errors":[{"code":3,"message":"Maximum stake amount is 10000"}]}');
        $this->betValidator->validate($betRequest);
    }

    public function testValidateStakeAmountMin(): void
    {
        $content = '{
            "player_id": 1,
            "stake_amount": "0.01",
            "selections": [
                {
                    "id": 1,
                    "odds": "1.001"
                }
            ]
        }';
        $request = new Request([], [], [], [], [], [], $content);
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest))
            ->willReturn([]);

        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('{"errors":[{"code":2,"message":"Minimum stake amount is 0.3"}]}');
        $this->betValidator->validate($betRequest);
    }

    public function testValidateWinAmountMax(): void
    {
        $content = '{
            "player_id": 1,
            "stake_amount": "1000.71",
            "selections": [
                {
                    "id": 1,
                    "odds": "20.001"
                }
            ]
        }';
        $request = new Request([], [], [], [], [], [], $content);
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest))
            ->willReturn([]);

        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('{"errors":[{"code":9,"message":"Maximum win amount is 20000"}]}');
        $this->betValidator->validate($betRequest);
    }

    public function testValidateSelectionsMin(): void
    {
        $content = '{
            "player_id": 1,
            "stake_amount": "1000.71",
            "selections": [
            ]
        }';
        $request = new Request([], [], [], [], [], [], $content);
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest))
            ->willReturn([]);

        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('{"errors":[{"code":4,"message":"Minimum number of selections is 1"}]}');
        $this->betValidator->validate($betRequest);
    }

    public function testValidateSelectionsMax(): void
    {
        $content = '{
            "player_id": 1,
            "stake_amount": "1000.71",
            "selections": [
                {"id": 1, "odds": "1"},
                {"id": 2, "odds": "1"},
                {"id": 3, "odds": "1"},
                {"id": 4, "odds": "1"},
                {"id": 5, "odds": "1"},
                {"id": 6, "odds": "1"},
                {"id": 7, "odds": "1"},
                {"id": 8, "odds": "1"},
                {"id": 9, "odds": "1"},
                {"id": 10, "odds": "1"},
                {"id": 11, "odds": "1"},
                {"id": 12, "odds": "1"},
                {"id": 13, "odds": "1"},
                {"id": 14, "odds": "1"},
                {"id": 15, "odds": "1"},
                {"id": 16, "odds": "1"},
                {"id": 17, "odds": "1"},
                {"id": 18, "odds": "1"},
                {"id": 19, "odds": "1"},
                {"id": 20, "odds": "1"},
                {"id": 21, "odds": "1"}
            ]
        }';
        $request = new Request([], [], [], [], [], [], $content);
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest))
            ->willReturn([]);

        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('{"errors":[{"code":5,"message":"Maximum number of selections is 20"}]}');
        $this->betValidator->validate($betRequest);
    }

    public function testValidateSelectionsDuplicate(): void
    {
        $content = '{
            "player_id": 1,
            "stake_amount": "1000.71",
            "selections": [
                {"id": 1, "odds": "1"},
                {"id": 1, "odds": "1"}
            ]
        }';
        $request = new Request([], [], [], [], [], [], $content);
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest))
            ->willReturn([]);

        $this->expectException(CustomException::class);
        $error = '{"id":1,"errors":{"code":8,"message":"Duplicate selection found"}}';
        $message = sprintf('{"selections":[%s,%s]}', $error, $error);
        $this->expectExceptionMessage($message);
        $this->betValidator->validate($betRequest);
    }

    public function testValidateSelectionOddsMin(): void
    {
        $content = '{
            "player_id": 1,
            "stake_amount": "1.71",
            "selections": [
                {"id": 1, "odds": "0.1"}
            ]
        }';
        $request = new Request([], [], [], [], [], [], $content);
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest))
            ->willReturn([]);

        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('{"selections":[{"id":1,"errors":{"code":6,"message":"Minimum odds are 1"}}]}');
        $this->betValidator->validate($betRequest);
    }

    public function testValidateSelectionOddsMax(): void
    {
        $content = '{
            "player_id": 1,
            "stake_amount": "1.71",
            "selections": [
                {"id": 1, "odds": "10000.1"}
            ]
        }';
        $request = new Request([], [], [], [], [], [], $content);
        $betRequest = new BetRequest($request);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($betRequest))
            ->willReturn([]);

        $this->expectException(CustomException::class);
        $this->expectExceptionMessage('{"selections":[{"id":1,"errors":{"code":7,"message":"Maximum odds are 10000"}}]}');
        $this->betValidator->validate($betRequest);
    }
}
