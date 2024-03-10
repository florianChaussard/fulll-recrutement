<?php

declare(strict_types=1);

namespace Fulll\App;

use Behat\Behat\Context\Context;
use Infra\Fleet;

class VehiculeContext implements Context
{
    /**
     * @Given my fleet
     */
    public function getFleet()
    {
        return new Fleet();
    }
}