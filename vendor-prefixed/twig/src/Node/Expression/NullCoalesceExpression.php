<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PodlovePublisher_Vendor\Twig\Node\Expression;

use PodlovePublisher_Vendor\Twig\Compiler;
use PodlovePublisher_Vendor\Twig\Node\Expression\Binary\AndBinary;
use PodlovePublisher_Vendor\Twig\Node\Expression\Test\DefinedTest;
use PodlovePublisher_Vendor\Twig\Node\Expression\Test\NullTest;
use PodlovePublisher_Vendor\Twig\Node\Expression\Unary\NotUnary;
use PodlovePublisher_Vendor\Twig\Node\Node;
use PodlovePublisher_Vendor\Twig\TwigTest;
class NullCoalesceExpression extends ConditionalExpression
{
    public function __construct(Node $left, Node $right, int $lineno)
    {
        $test = new DefinedTest(clone $left, new TwigTest('defined'), new Node(), $left->getTemplateLine());
        // for "block()", we don't need the null test as the return value is always a string
        if (!$left instanceof BlockReferenceExpression) {
            $test = new AndBinary($test, new NotUnary(new NullTest($left, new TwigTest('null'), new Node(), $left->getTemplateLine()), $left->getTemplateLine()), $left->getTemplateLine());
        }
        parent::__construct($test, $left, $right, $lineno);
    }
    public function compile(Compiler $compiler) : void
    {
        /*
         * This optimizes only one case. PHP 7 also supports more complex expressions
         * that can return null. So, for instance, if log is defined, log("foo") ?? "..." works,
         * but log($a["foo"]) ?? "..." does not if $a["foo"] is not defined. More advanced
         * cases might be implemented as an optimizer node visitor, but has not been done
         * as benefits are probably not worth the added complexity.
         */
        if ($this->getNode('expr2') instanceof NameExpression) {
            $this->getNode('expr2')->setAttribute('always_defined', \true);
            $compiler->raw('((')->subcompile($this->getNode('expr2'))->raw(') ?? (')->subcompile($this->getNode('expr3'))->raw('))');
        } else {
            parent::compile($compiler);
        }
    }
}
