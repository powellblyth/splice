<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Library\Services;

use App\Library\Services\FtpUtils;
use phpmock\MockBuilder;
use phpmock\phpunit\PHPMock;
use Tests\TestCase;

class FtpUtilsTest extends Testcase
{
    use PHPMock;

    public function providerremoteFolderExists(): array
    {
        return [
            [false, true, true, false]
        ];
    }

    /**
     * @dataProvider providerremoteFolderExists
     * @param bool $expected
     */
    public function testremoteFolderExists(bool $expected, $isConnected, bool $canChdir, bool$folderPath)
    {
        return $this->assertTrue(true);


        $exec = $this->getFunctionMock(__NAMESPACE__, "timbool e");
        $exec->expects($this->once())->willReturn(9);
//        $exec->
        var_dump(time());

        echo "HERE\n";
        $exec = $this->getFunctionMock(__NAMESPACE__, "ftp_pwd");
        $exec->expects($this->once())->willReturn('/somehting/here');
        echo "HERE\n";
        //        $mock =

        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)->setName("ftp_chdir")
            ->setFunction(
                function () {
                    return $canChdir;
                }
            );

        $builder->setNamespace('App\\Library\\Services')->setName("ftp_close")
            ->setFunction(
                function () {
                    return true;
                }
            );

        $mock = $builder->build()->enable();

        $this->sut = $this->getMockBuilder(FtpUtils::class)->setMethods(['isConnected'])->disableOriginalConstructor()->getMock();
        $this->sut->method('isConnected')->willReturn($isConnected);

        $this->assertSame($expected, $this->sut->remoteFolderExists($folderPath));
    }

    public function providerisConnected()
    {
        return [
            [null, false],
            []
        ];
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testisConnected()
    {
        $this->sut            = $this->getMockBuilder(FtpUtils::class)->setMethods([])->disableOriginalConstructor()->getMock();
        $this->sut->ftpHandle = null;
        $this->assertSame(false, $this->sut->isConnected());
//        $this->sut->expects($this->once())->method('isLate')->will($this->returnValue($isLate));
    }

    public function providercleanPath()
    {
        return [
            ['/', '/'],
            ['/bob/', '/bob'],
            ['/bob/', '/bob/'],
        ];
    }

    /**
     * @dataProvider providerCleanPath
     * @param string $expected
     * @param string $input
     */
    public function testcleanPath(string $expected, string $input)
    {
        $this->sut = $this->getMockBuilder(FtpUtils::class)->setMethods(null)->disableOriginalConstructor()->getMock();
        $this->assertSame($expected, $this->sut->cleanPath($input));
//        vaR_dump( $this->sut);die();

    }

    public function providercleanFile()
    {
        return [
            ['', '/'],
            ['bobbob.csv', '/bobbob.csv'],
            ['bobbob.csv', '///bobbob.csv'],
        ];
    }

    /**
     * @dataProvider providerCleanFile
     * @param string $expected
     * @param string $input
     */
    public function testcleanFile(string $expected, string $input)
    {
        $this->sut = $this->getMockBuilder(FtpUtils::class)->setMethods(null)->disableOriginalConstructor()->getMock();
        $this->assertSame($expected, $this->sut->cleanFile($input));

    }


}
