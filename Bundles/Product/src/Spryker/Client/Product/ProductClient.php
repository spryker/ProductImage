<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Product;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method ProductDependencyContainer getDependencyContainer()
 */
class ProductClient extends AbstractClient implements ProductClientInterface
{

    /**
     * @param int $idAbstractProduct
     *
     * @return array
     */
    public function getAbstractProductFromStorageByIdForCurrentLocale($idAbstractProduct)
    {
        $locale = $this->getDependencyContainer()->getLocaleClient()->getCurrentLocale();
        $productStorage = $this->getDependencyContainer()->createProductStorage($locale);
        $product = $productStorage->getAbstractProductFromStorageById($idAbstractProduct);

        return $product;
    }

    /**
     * @param int $idAbstractProduct
     * @param string $locale
     *
     * @return array
     */
    public function getAbstractProductFromStorageById($idAbstractProduct, $locale)
    {
        $productStorage = $this->getDependencyContainer()->createProductStorage($locale);
        $product = $productStorage->getAbstractProductFromStorageById($idAbstractProduct);

        return $product;
    }

}