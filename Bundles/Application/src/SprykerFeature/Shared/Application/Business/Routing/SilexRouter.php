<?php

/*
 * This file is part of the SilexRouting extension.
 *
 * (c) Project A Ventures GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SprykerFeature\Shared\Application\Business\Routing;

use Psr\Log\LoggerInterface;
use Silex\RedirectableUrlMatcher;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * The default router, which matches/generates all the routes
 * add by the methods in Application
 */
class SilexRouter implements RouterInterface
{

    /**
     * @var \Pimple
     */
    protected $app;

    /**
     * @var RequestContext
     */
    protected $context;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Pimple $app
     * @param LoggerInterface $logger
     */
    public function __construct(\Pimple $app, LoggerInterface $logger = null)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return ($this->context) ?: $this->app['request_context'];
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection()
    {
        return $this->app['routes'];
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        $generator = new UrlGenerator($this->getRouteCollection(), $this->getContext(), $this->logger);

        return $generator->generate($name, $parameters, $referenceType);
    }

    /**
     * Tries to match a URL path with a set of routes.
     *
     * If the matcher can not find information, it must throw one of the exceptions documented
     * below.
     *
     * @param string $pathinfo The path info to be parsed (raw format, i.e. not urldecoded)
     *
     * @return array An array of parameters
     *
     * @throws ResourceNotFoundException If the resource could not be found
     * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
     *
     * @api
     */
    public function match($pathinfo)
    {
        $matcher = new RedirectableUrlMatcher($this->getRouteCollection(), $this->getContext());

        return $matcher->match($pathinfo);
    }
}
