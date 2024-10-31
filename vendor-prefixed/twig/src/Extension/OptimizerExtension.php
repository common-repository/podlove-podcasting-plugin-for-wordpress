<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PodlovePublisher_Vendor\Twig\Extension;

use PodlovePublisher_Vendor\Twig\NodeVisitor\OptimizerNodeVisitor;
final class OptimizerExtension extends AbstractExtension
{
    public function __construct(private int $optimizers = -1)
    {
    }
    public function getNodeVisitors() : array
    {
        return [new OptimizerNodeVisitor($this->optimizers)];
    }
}
