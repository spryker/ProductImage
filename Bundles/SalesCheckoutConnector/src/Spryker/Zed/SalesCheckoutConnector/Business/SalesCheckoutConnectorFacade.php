<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SalesCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method SalesCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class SalesCheckoutConnectorFacade extends AbstractFacade
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(OrderTransfer $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getDependencyContainer()->getSalesOrderSaver()->saveOrder($orderTransfer, $checkoutResponse);
    }

}