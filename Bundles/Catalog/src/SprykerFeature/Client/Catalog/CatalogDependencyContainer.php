<?php

namespace SprykerFeature\Client\Catalog;

use Elastica\Index;
use Generated\Client\Ide\FactoryAutoCompletion\Catalog;
use SprykerFeature\Client\Catalog\Model\FacetConfig;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class CatalogDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var Catalog
     */
    protected $factory;

    /**
     * @return Model\Catalog
     */
    public function createCatalogModel()
    {
        return $this->getFactory()->createModelCatalog(
            $this->getProductKeyBuilder(),
            $this->getLocator()->kvStorage()->readClient()->getInstance(),
            \SprykerEngine\Shared\Kernel\Store::getInstance()->getCurrentLocale()
        );
    }

    /**
     * @param Request $request
     * @param array $category
     * @return Model\FacetSearch
     */
    public function createFacetSearch(Request $request, array $category)
    {
        $facetConfig = $this->createFacetConfig();

        return $this->getFactory()->createModelFacetSearch(
            $request,
            $facetConfig,
            $this->getSearchIndex(),
            $this->getFacetAggregationBuilder(),
            $this->createFacetFilterHandler($facetConfig),
            $this->getFacetExtractor(),
            $this->getRangeExtractor(),
            $this->createCatalogModel(),
            $category
        );
    }

    /**
     * @param Request $request
     * @return Model\FulltextSearch
     */
    public function createFulltextSearch(Request $request)
    {
        $facetConfig = $this->createFacetConfig();

        return $this->getFactory()->createModelFulltextSearch(
            $request,
            $facetConfig,
            $this->getSearchIndex(),
            $this->getFacetAggregationBuilder(),
            $this->createFacetFilterHandler($facetConfig),
            $this->getFacetExtractor(),
            $this->getRangeExtractor(),
            $this->createCatalogModel()
        );
    }

    /**
     * @return FacetConfig
     */
    public function createFacetConfig()
    {
        return $this->getFactory()->createModelFacetConfig();
    }

    /**
     * @param FacetConfig $facetConfig
     * @return Model\FacetFilterHandler
     */
    protected function createFacetFilterHandler(FacetConfig $facetConfig)
    {
        return $this->getFactory()->createModelFacetFilterHandler(
            $this->getFactory()->createModelBuilderNestedFilterBuilder(
                $this->getFactory()->createModelBuilderFilterBuilder()
            ),
            $facetConfig
        );
    }

    /**
     * @return Index
     */
    protected function getSearchIndex()
    {
        return $this->getLocator()->search()->indexClient()->getInstance();
    }

    /**
     * @return Model\Builder\FacetAggregationBuilder
     */
    protected function getFacetAggregationBuilder()
    {
        return $this->getFactory()->createModelBuilderFacetAggregationBuilder();
    }

    /**
     * @return Model\Extractor\FacetExtractor
     */
    protected function getFacetExtractor()
    {
        return $this->getFactory()->createModelExtractorFacetExtractor();
    }

    /**
     * @return Model\Extractor\RangeExtractor
     */
    protected function getRangeExtractor()
    {
        return $this->getFactory()->createModelExtractorRangeExtractor();
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function getProductKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderProductResourceKeyBuilder();
    }
}
