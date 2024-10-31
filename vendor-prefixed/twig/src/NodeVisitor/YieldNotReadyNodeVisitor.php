<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PodlovePublisher_Vendor\Twig\NodeVisitor;

use PodlovePublisher_Vendor\Twig\Attribute\YieldReady;
use PodlovePublisher_Vendor\Twig\Environment;
use PodlovePublisher_Vendor\Twig\Node\Expression\AbstractExpression;
use PodlovePublisher_Vendor\Twig\Node\Node;
/**
 * @internal to be removed in Twig 4
 */
final class YieldNotReadyNodeVisitor implements NodeVisitorInterface
{
    private $yieldReadyNodes = [];
    public function __construct(private bool $useYield)
    {
    }
    public function enterNode(Node $node, Environment $env) : Node
    {
        $class = \get_class($node);
        if ($node instanceof AbstractExpression || isset($this->yieldReadyNodes[$class])) {
            return $node;
        }
        if (!($this->yieldReadyNodes[$class] = (bool) (new \ReflectionClass($class))->getAttributes(YieldReady::class))) {
            if ($this->useYield) {
                throw new \LogicException(\sprintf('You cannot enable the "use_yield" option of Twig as node "%s" is not marked as ready for it; please make it ready and then flag it with the #[YieldReady] attribute.', $class));
            }
            trigger_deprecation('twig/twig', '3.9', 'Twig node "%s" is not marked as ready for using "yield" instead of "echo"; please make it ready and then flag it with the #[YieldReady] attribute.', $class);
        }
        return $node;
    }
    public function leaveNode(Node $node, Environment $env) : ?Node
    {
        return $node;
    }
    public function getPriority() : int
    {
        return 255;
    }
}
