<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PodlovePublisher_Vendor\Twig\Node;

use PodlovePublisher_Vendor\Twig\Attribute\YieldReady;
use PodlovePublisher_Vendor\Twig\Compiler;
use PodlovePublisher_Vendor\Twig\Node\Expression\AbstractExpression;
use PodlovePublisher_Vendor\Twig\Node\Expression\ConstantExpression;
/**
 * Represents an embed node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
#[\Twig\Attribute\YieldReady]
class EmbedNode extends IncludeNode
{
    // we don't inject the module to avoid node visitors to traverse it twice (as it will be already visited in the main module)
    public function __construct(string $name, int $index, ?AbstractExpression $variables, bool $only, bool $ignoreMissing, int $lineno)
    {
        parent::__construct(new ConstantExpression('not_used', $lineno), $variables, $only, $ignoreMissing, $lineno);
        $this->setAttribute('name', $name);
        $this->setAttribute('index', $index);
    }
    protected function addGetTemplate(Compiler $compiler) : void
    {
        $compiler->write('$this->loadTemplate(')->string($this->getAttribute('name'))->raw(', ')->repr($this->getTemplateName())->raw(', ')->repr($this->getTemplateLine())->raw(', ')->string($this->getAttribute('index'))->raw(')');
    }
}
