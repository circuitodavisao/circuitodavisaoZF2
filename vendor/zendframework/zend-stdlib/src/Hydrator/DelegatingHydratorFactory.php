<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Stdlib\Hydrator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @deprecated Use Zend\Hydrator\DelegatingHydratorFactory from zendframework/zend-hydrator instead.
 */
class DelegatingHydratorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Assume that this factory is registered with the HydratorManager,
        // and just pass it directly on.
        return new DelegatingHydrator($serviceLocator);
    }
}
