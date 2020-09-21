<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 2019-01-30
 * Time: 09:02
 */

namespace App\Library\Services;


use App\Models\Fact;

class FactService
{
    static $actualFactService;

    /**
     * Singleton (boring!)
     * @return Fact
     */
    public static function getInstance(): FactService
    {
        if (!static::$actualFactService instanceof FactService) {
            static::$actualFactService = new FactService();
        }
        return static::$actualFactService;
    }

    protected function getFactFromDb(string $factName): ?Fact
    {
        return Fact::where('name', $factName)->first();
    }

    public function getFactValue(string $factName, string $default = null)
    {
        $result = $default;
        $fact   = $this->getFactFromDb($factName);
        if ($fact instanceof Fact) {
//            echo "got fact " .$fact->value."\n";
            $result = $fact->value;
        }
        return $result;
    }


    /**
     * This function gets a fact, incremments it, and returns the incremented version
     * @param string $factName
     * @param int $default
     * @return null|int
     * @throws \Exception
     */
    public function getAndIncrementFact(string $factName, int $default = 0): ?int
    {

        $value = $this->getFactValue($factName, $default);
        if (!is_numeric($value)) {
            throw new \Exception('Could not increment non-numeric fact ' . $factName);
        }
        $factValue = (int) ++$value;
        $this->setFact($factName, (int) $factValue);
        return $factValue;
    }


    public function setFact(string $factName, $factValue): bool
    {
        $fact = $this->getFactFromDb($factName);
        if (!$fact instanceof Fact) {
            $fact       = new Fact();
            $fact->name = $factName;
        }
        $fact->value = $factValue;
        return $fact->save();
    }
}