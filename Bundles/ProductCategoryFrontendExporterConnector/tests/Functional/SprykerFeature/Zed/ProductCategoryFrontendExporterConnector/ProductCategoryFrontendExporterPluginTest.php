<?php

namespace Functional\SprykerFeature\Zed\ProductCategoryFrontendExporterConnector;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;
use Generated\Shared\Transfer\CategoryCategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Pyz\Zed\Locale\Business\LocaleFacade;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Touch\Business\TouchFacade;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttributeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryClosureTableQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryNodeQuery;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryQuery;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\ProductCategory\Business\ProductCategoryFacade;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;

/**
 * @group SprykerFeature
 * @group Zed
 * @group ProductCategoryFrontendExporterConnector
 * @group ProductCategoryFrontendExporterPluginTest
 * @group FrontendExporterPlugin
 */
class ProductCategoryFrontendExporterPluginTest extends Test
{
    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @var CategoryFacade
     */
    protected $categoryFacade;

    /**
     * @var ProductFacade
     */
    protected $productFacade;

    /**
     * @var ProductCategoryFacade
     */
    protected $productCategoryFacade;

    /**
     * @var TouchFacade
     */
    protected $touchFacade;

    /**
     * @var UrlFacade
     */
    protected $urlFacade;

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();

        $this->localeFacade = $this->locator->locale()->facade();
        $this->locale = $this->localeFacade->createLocale('ABCDE');

        $this->categoryFacade = $this->locator->category()->facade();
        $this->productFacade = $this->locator->product()->facade();
        $this->productCategoryFacade = $this->locator->productCategory()->facade();

        $this->touchFacade = $this->locator->touch()->facade();
        $this->urlFacade = $this->locator->url()->facade();
    }

    public function testProductsWithCategoryNodes()
    {
        $this->eraseUrlsAndCategories();
        $this->createAttributeType();
        $idAbstractProduct = $this->createAbstractProductWithVariant('TestSku', 'TestProductName', $this->locale);
        $this->urlFacade->createUrl('/some-url', $this->locale, 'abstract_product', $idAbstractProduct);
        $this->touchFacade->touchActive('test', $idAbstractProduct);

        $idRootCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('ARootCategory'),
            $this->locale
        );

        $idRootCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idRootCategory, null, true),
            $this->locale
        );

        $idCategory = $this->categoryFacade->createCategory(
            $this->createCategoryTransfer('ACategory'),
            $this->locale
        );

        $idCategoryNode = $this->categoryFacade->createCategoryNode(
            $this->createCategoryNodeTransfer($idCategory, $idRootCategoryNode),
            $this->locale
        );

        $this->productCategoryFacade->createProductCategoryMapping('AbstractTestSku', 'ACategory', $this->locale);

        $this->doExporterTest(
            [
                $this->locator->productFrontendExporterConnector()->pluginProductQueryExpanderPlugin(),
                $this->locator->productCategoryFrontendExporterConnector()->pluginProductCategoryBreadcrumbQueryExpanderPlugin()
            ],
            [
                $this->locator->productFrontendExporterConnector()->pluginProductProcessorPlugin(),
                $this->locator->productCategoryFrontendExporterConnector()->pluginProductCategoryBreadcrumbProcessorPlugin()
            ],
            ['de.abcde.resource.abstract_product.' . $idAbstractProduct =>
                [
                    'abstract_attributes' => [
                        'thumbnail_url' => '/images/product/default.png',
                        'price' => 1395,
                        'width' => 12,
                        'height' => 27,
                        'depth' => 850,
                        'main_color' => 'gray',
                        'other_colors' => 'red',
                        'description' => 'A description!',
                        'name' => 'Ted Technical Robot',
                    ],
                    'name' => 'TestProductName',
                    'abstract_sku' => 'AbstractTestSku',
                    'url' => '/some-url',
                    'concrete_products' => [
                        [
                            'sku' => 'TestSku',
                            'attributes' => [
                                'image_url' => '/images/product/robot_buttons_black.png',
                                'weight' => 1.2,
                                'material' => 'aluminium',
                                'gender' => 'b',
                                'age' => 8,
                                'available' => true,
                            ]
                        ]
                    ],
                    'category' => [
                        $idCategoryNode => [
                            'node_id' => (string)$idCategoryNode,
                            'name' => 'ACategory',
                            'url' => '/acategory'
                        ]
                    ]
                ]
            ]
        );
    }

    protected function eraseUrlsAndCategories()
    {
        Propel::getConnection()->query('SET foreign_key_checks = 0;');
        SpyUrlQuery::create()->deleteAll();
        SpyCategoryClosureTableQuery::create()->deleteAll();
        SpyCategoryAttributeQuery::create()->deleteAll();
        SpyCategoryNodeQuery::create()->deleteAll();
        SpyCategoryQuery::create()->deleteAll();
        Propel::getConnection()->query('SET foreign_key_checks = 1;');
    }

    protected function createAttributeType()
    {
        if (!$this->productFacade->hasAttributeType('test')) {
            $this->productFacade->createAttributeType('test', 'test');
        }
    }

    /**
     * @param string $sku
     * @param string $name
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    protected function createAbstractProductWithVariant($sku, $name, LocaleTransfer $locale)
    {
        $idAbstractProduct = $this->createAbstractProductWithAttributes('Abstract' . $sku, 'Abstract' . $name, $locale);
        $this->createConcreteProductWithAttributes($idAbstractProduct, $sku, $name, $locale);

        return $idAbstractProduct;
    }

    /**
     * @param string $sku
     * @param string $name
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    protected function createAbstractProductWithAttributes($sku, $name, $locale)
    {
        $idAbstractProduct = $this->productFacade->createAbstractProduct($sku);

        $this->productFacade->createAbstractProductAttributes(
            $idAbstractProduct,
            $locale,
            $name,
            json_encode(
                [
                    'thumbnail_url' => '/images/product/default.png',
                    'price' => 1395,
                    'width' => 12,
                    'height' => 27,
                    'depth' => 850,
                    'main_color' => 'gray',
                    'other_colors' => 'red',
                    'description' => 'A description!',
                    'name' => 'Ted Technical Robot',
                ]
            )
        );

        return $idAbstractProduct;
    }

    /**
     * @param int $idAbstractProduct
     * @param string $sku
     * @param string $name
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    protected function createConcreteProductWithAttributes($idAbstractProduct, $sku, $name, LocaleTransfer $locale)
    {
        $idConcreteProduct = $this->productFacade->createConcreteProduct($sku, $idAbstractProduct, true);

        $this->productFacade->createConcreteProductAttributes(
            $idConcreteProduct,
            $locale,
            $name,
            json_encode(
                [
                    'image_url' => '/images/product/robot_buttons_black.png',
                    'weight' => 1.2,
                    'material' => 'aluminium',
                    'gender' => 'b',
                    'age' => 8,
                    'available' => true,
                ]
            )
        );

        return $idConcreteProduct;
    }

    /**
     * @param $name
     *
     * @return CategoryCategoryTransfer
     */
    protected function createCategoryTransfer($name)
    {
        $categoryTransfer = new CategoryCategoryTransfer();
        $categoryTransfer->setName($name);

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     * @param bool $isRoot
     * @param int $idParentCategory
     *
     * @return CategoryCategoryNodeTransfer
     */
    protected function createCategoryNodeTransfer($idCategory, $idParentCategory, $isRoot = false)
    {
        $categoryNodeTransfer = new CategoryCategoryNodeTransfer();
        $categoryNodeTransfer->setIsRoot($isRoot);
        $categoryNodeTransfer->setFkCategory($idCategory);
        $categoryNodeTransfer->setFkParentCategoryNode($idParentCategory);

        return $categoryNodeTransfer;
    }

    /**
     * @param QueryExpanderPluginInterface[] $expanderCollection
     * @param DataProcessorPluginInterface[] $processors
     * @param array $expectedResult
     */
    public function doExporterTest(array $expanderCollection, array $processors, array $expectedResult)
    {
        $query = $this->prepareQuery();

        foreach ($expanderCollection as $expander) {
            $query = $expander->expandQuery($query, $this->locale);
        }

        $results = $query->find();

        $processedResultSet = [];
        foreach ($processors as $processor) {
            $processedResultSet = $processor->processData($results, $processedResultSet, $this->locale);
        }

        $this->assertEquals($expectedResult, $processedResultSet);
    }

    /**
     * @return ModelCriteria
     * @throws PropelException
     */
    protected function prepareQuery()
    {
        $query = SpyTouchQuery::create()
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
            ->setFormatter(new PropelArraySetFormatter())
            ->filterByItemType('test');

        return $query;
    }
}