<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageFacade getFacade()
 * @method \Spryker\Zed\ProductImage\Communication\ProductImageCommunicationFactory getFactory()
 */
class ProductAbstractReadPlugin extends AbstractPlugin implements ProductAbstractPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function run(ProductAbstractTransfer $productAbstractTransfer)
    {
        $imageSetCollection = $this->getFacade()->getProductImagesSetCollectionByProductAbstractId(
            $productAbstractTransfer->getIdProductAbstract()
        );

        if ($imageSetCollection === null) {
            return;
        }

        $productAbstractTransfer->setImageSets(
            new ArrayObject($imageSetCollection)
        );
    }

}
