<?php

namespace SprykerFeature\Client\FrontendExporter;

use Generated\Client\Ide\FactoryAutoCompletion\FrontendExporter;
use SprykerFeature\Yves\FrontendExporter\Business\Matcher\UrlMatcherInterface;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;

class FrontendExporterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @var FrontendExporter
     */
    protected $factory;

    /**
     * @return UrlMatcherInterface
     */
    public function createUrlMatcher()
    {
        $urlKeyBuilder = $this->getFactory()->createKeyBuilderUrlKeyBuilder();
        $kvReader = $this->getLocator()->kvStorage()->readClient()->getInstance();

        return $this->getFactory()->createMatcherUrlMatcher(
            $urlKeyBuilder,
            $kvReader
        );
    }
}
