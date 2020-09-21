<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Library\Services;

use Tests\TestCase;
use App\Library\Services\AfterShip;

class AfterShipTest extends Testcase
{
    public function providernormaliseMobileNumber()
    {
        return [
            ['+447899440443', '07899440443', 'GB'],
            [null, '07899440443', 'United Kingdom'],
            ['+447899440443', '07899 4404 43', 'GB'],
            ['+447899440443', '(0)7899 4404 43', 'GB'],
            ['+447899440443', '+447899440443', 'GB'],
        ];
    }

    /**
     * A basic test example.
     * @dataProvider providernormaliseMobileNumber
     * @return void
     */
    public function testnormaliseMobileNumber($expected, string $number, string $country)
    {
        $this->assertSame($expected, AfterShip::normaliseMobileNumber($number, $country));
//        $this->sut = $this->getMockBuilder(FtpUtils::class)->setMethods([])->disableOriginalConstructor()->getMock();
//        $this->sut->ftpHandle = null;
//        $this->assertSame(false, $this->sut->isConnected());
//        $this->sut->expects($this->once())->method('isLate')->will($this->returnValue($isLate));
    }


}

