<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductImage\Business\Model\Reader;
use Spryker\Zed\ProductImage\Business\Model\Writer;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferGenerator;
use Spryker\Zed\ProductImage\ProductImageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface getQueryContainer()
 */
class ProductImageBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductImage\Business\Model\ReaderInterface
     */
    public function createProductImageReader()
    {
        return new Reader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Model\WriterInterface
     */
    public function createProductImageWriter()
    {
        return new Writer(
            $this->getQueryContainer()
        );
    }

    /**
     * @return ProductImageTransferGenerator
     */
    public function createTransferGenerator()
    {
        return new ProductImageTransferGenerator(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade()
    {
        $this->getProvidedDependency(ProductImageDependencyProvider::FACADE_LOCALE);
    }

}
