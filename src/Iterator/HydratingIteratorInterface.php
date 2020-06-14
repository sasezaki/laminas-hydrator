<?php

/**
 * @see       https://github.com/laminas/laminas-hydrator for the canonical source repository
 * @copyright https://github.com/laminas/laminas-hydrator/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-hydrator/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Laminas\Hydrator\Iterator;

use Iterator;
use Laminas\Hydrator\HydratorInterface;

/**
 * @template TKey
 * @template TValue
 * @template-extends \Iterator<TKey, TValue>
 */
interface HydratingIteratorInterface extends Iterator
{
    /**
     * This sets the prototype to hydrate.
     *
     * This prototype can be the name of the class or the object itself;
     * iteration will clone the object.
     *
     * @param string|object $prototype
     * @psalm-param class-string<TValue>|TValue $prototype
     */
    public function setPrototype($prototype) : void;

    /**
     * Sets the hydrator to use during iteration.
     */
    public function setHydrator(HydratorInterface $hydrator) : void;
}
