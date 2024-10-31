<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PodlovePublisher_Vendor\Twig\RuntimeLoader;

/**
 * Lazy loads the runtime implementations for a Twig element.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class FactoryRuntimeLoader implements RuntimeLoaderInterface
{
    /**
     * @param array $map An array where keys are class names and values factory callables
     */
    public function __construct(private array $map = [])
    {
    }
    public function load(string $class)
    {
        if (!isset($this->map[$class])) {
            return null;
        }
        $runtimeFactory = $this->map[$class];
        return $runtimeFactory();
    }
}