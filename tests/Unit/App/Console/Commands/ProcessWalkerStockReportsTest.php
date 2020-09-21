<?php


namespace Tests\Unit\App\Console\Commands;


use Tests\ModelTestBase;
use App\Console\Commands\processWalkerDespatchConfirms;

class ProcessWalkerStockReportsTest
{
    public function providerpopulateFromUnleashed()
    {
        $stdObject                 = new \stdClass();
        $stdObject->Guid           = 'someguid';
        $stdObject->GroupName      = 'someGroupName';
        $stdObject->LastModifiedOn = '\/Date(1538861748000)\/';
        return [
            [false, ['source' => 'unleashed', 'guid' => 'someguid', 'remote_last_modified' => '2018-10-06 21:35:48'], false, $stdObject],
            [true, ['source' => 'unleashed', 'guid' => 'someguid', 'remote_last_modified' => '2018-10-06 21:35:48'], true, $stdObject],
        ];
    }

    /**
     * A basic test example.
     * @dataProvider providerpopulateFromUnleashed
     * @param $expectedSaveResult
     * @param $expectedValues
     * @param bool $saveReturns
     * @param \stdClass $dataObject
     */
    public function testnotifyUsers(int $expectedSuccessCount, int $expectedFailCountarray, array $successes, array $fails, int $numberOfUsers)
    {
        $this->sut = $this->getMockBuilder('\App\Console\Commands\processWalkerDespatchConfirms')->setMethods(['getNotifiableUsers'])->disableOriginalConstructor()->getMock();
        $mockUsers = [];
        for ($x = 0; $x < $numberOfUsers; $x++) {
            $mockUsers[] = $this->getMockBuilder('\App\User')->setMethods(['notify'])->disableOriginalConstructor()->getMock();
            $mockUsers[count($mockUsers) - 1]->expects($this->exactly($expectedSuccessCount))->method('notify')->with();
        }

        $this->sut->method('getNotifiableUsers')->willReturn($mockUsers);
        $this->sut->expects($this->exactly($expectedSuccessCount))->method();
        $this->assertSame($expectedSuccessCount, $this->sut->populateFromUnleashed($dataObject));
        foreach ($expectedValues as $expectedKey => $expectedValue) {
            $this->assertSame($this->sut->{$expectedKey}, $expectedValue);
        }
    }
}