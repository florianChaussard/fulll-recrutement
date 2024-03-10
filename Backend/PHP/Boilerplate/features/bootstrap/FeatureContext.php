<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Fulll\App\Calculator;

class FeatureContext implements Context
{
    /**
     * @When I multiply :a by :b into :var
     */
    public function iMultiply(int $a, int $b, string $var): void
    {
        $calculator = new Calculator();
        $this->$var = $calculator->multiply($a, $b);
    }

    /**
     * @Then :var should be equal to :value
     */
    public function aShouldBeEqualTo(string $var, int $value): void
    {
        if ($value !== $this->$var) {
            throw new \RuntimeException(sprintf('%s is expected to be equal to %s, got %s', $var, $value, $this->$var));
        }
    }


    /**
     * @When I make a test and store a value into :aTestVar
     */
    public function iMakeATest(string $aTestVar): void
    {
        $this->$aTestVar = $aTestVar;
    }

    /**
     * @Then my var :aTestVar should be displayed as :value
     */
    public function aTestVarShouldBeDisplayedAs(string $aTestVar, string $value): void
    {
        if ($value !== $this->$aTestVar) {
            throw new \RuntimeException(sprintf('%s is expected to be equal to %s, got %s', $aTestVar, $value, $this->$aTestVar));
        }
    }



    /**
     * @When I want to concatenate :text1 and :text2 into :concatenatedText
     */
    public function iContatenateTwoValues(string $text1, string $text2, string $concatenatedText): void
    {
        $this->$concatenatedText = $text1.$text2;
    }

    /**
     * @Then the concatenation result stored in :concatenatedText should be displayed as :value
     */
    public function concatenationResult(string $concatenatedText, string $value): void
    {
        if ($value !== $this->$concatenatedText) {
            throw new \RuntimeException(sprintf('%s is expected to be equal to %s, got %s', $concatenatedText, $value, $this->$concatenatedText));
        }
    }
}
