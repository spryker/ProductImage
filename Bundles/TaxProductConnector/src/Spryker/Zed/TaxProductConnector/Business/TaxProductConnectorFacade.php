<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\TaxProductConnector\Business\Plugin\TaxChangeTouchPlugin;

/**
 * @method TaxProductConnectorDependencyContainer getDependencyContainer()
 */
class TaxProductConnectorFacade extends AbstractFacade
{

    /**
     * @return TaxChangeTouchPlugin
     */
    public function getTaxChangeTouchPlugin()
    {
        return $this->getDependencyContainer()->getTaxChangeTouchPlugin();
    }

}