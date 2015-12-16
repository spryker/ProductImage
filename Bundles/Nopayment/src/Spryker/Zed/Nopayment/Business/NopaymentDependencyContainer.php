<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\Nopayment\Business\Nopayment\Paid;
use Spryker\Zed\Nopayment\Persistence\NopaymentQueryContainer;

class NopaymentDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return NopaymentQueryContainer
     */
    public function locateQueryContainer()
    {
        return $this->getLocator()->nopayment()->queryContainer();
    }

    /**
     * @return Paid
     */
    public function createNopaymentPaid()
    {
        $queryContainer = $this->locateQueryContainer();

        return new Paid(
                $queryContainer,
                $this->getLocator()
            );
    }

}