<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 2018-12-05
 * Time: 18:17
 */

namespace Tests;
//use Tests\TestCase;
/**
 * Class ModelBase
 * Simply wraps a base test case to encorouage test logic reuse
 * @package Tests\Unit
 */
class ModelTestBase extends TestCase{
    /**
     * Abstract this away, to reduce code duplication
     * @param string $relationshipType e.g. \Illuminate\Database\Eloquent\Relations\HasMany'
     * @param string $relationshipMethod e.g. 'hasMany'
     * @param string $methodToTest e.g. 'products'
     * @param string $classToTest e.g. '\App\ProductGroup'
     */
    protected function runRelationshipTest(string $relationshipType , string $relationshipMethod, string $methodToTest, string $classToTest) {
        $mockIlluminateClass = $this->getMockBuilder($relationshipType)->disableOriginalConstructor()->getMock();
        $this->sut = $this->getMockBuilder($classToTest)->setMethods([$relationshipMethod])->disableOriginalConstructor()->getMock();
        $this->sut->expects($this->once())->method($relationshipMethod)->will($this->returnValue($mockIlluminateClass));
        $this->assertinstanceOf($relationshipType, $this->sut->{$methodToTest}());
    }

}