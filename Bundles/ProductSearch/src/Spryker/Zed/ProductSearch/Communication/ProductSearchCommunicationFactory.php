<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSearch\Business\ProductSearchFacade;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class ProductSearchCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductSearchFacade
     */
    public function getAttributesTransformer()
    {
        return $this->getLocator()->productSearch()->facade();
    }

    /**
     * @return ProductSearchFacade
     */
    public function getProductsTransformer()
    {
        return $this->getLocator()->productSearch()->facade();
    }

    /**
     * @return ProductSearchQueryContainerInterface
     */
    public function getProductSearchQueryContainer()
    {
        return $this->getLocator()->productSearch()->queryContainer();
    }

}