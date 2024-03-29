<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\EventManager;

/**
 * Interface for allowing attachment of shared aggregate listeners.
 *
 * @deprecated This interface is deprecated with 2.6.0, and will be removed in 3.0.0.
 *     See {@link https://github.com/zendframework/zend-eventmanager/blob/develop/doc/book/migration/removed.md}
 *     for details.
 */
interface SharedEventAggregateAwareInterface
{
    /**
     * Attach a listener aggregate
     *
     * @param  SharedListenerAggregateInterface $aggregate
     * @param  int $priority If provided, a suggested priority for the aggregate to use
     * @return mixed return value of {@link SharedListenerAggregateInterface::attachShared()}
     */
    public function attachAggregate(SharedListenerAggregateInterface $aggregate, $priority = 1);

    /**
     * Detach a listener aggregate
     *
     * @param  SharedListenerAggregateInterface $aggregate
     * @return mixed return value of {@link SharedListenerAggregateInterface::detachShared()}
    */
    public function detachAggregate(SharedListenerAggregateInterface $aggregate);
}
