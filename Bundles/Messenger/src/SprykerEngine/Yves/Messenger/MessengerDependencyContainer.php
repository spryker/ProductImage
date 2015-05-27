<?php

namespace SprykerEngine\Yves\Messenger;

use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerFeature\Sdk\Glossary\Translator;
use Twig_Environment;

class MessengerDependencyContainer extends AbstractDependencyContainer
{
    public function createYvesPresenter(
        MessengerInterface $messenger,
        Translator $translator,
        Twig_Environment $twig
    ){
        return $this->getFactory()->createYvesPresenter(
            $messenger,
            $translator,
            $twig
        );
    }
}