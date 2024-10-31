<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PodlovePublisher_Vendor\Twig\TokenParser;

use PodlovePublisher_Vendor\Twig\Error\SyntaxError;
use PodlovePublisher_Vendor\Twig\Node\AutoEscapeNode;
use PodlovePublisher_Vendor\Twig\Node\Expression\ConstantExpression;
use PodlovePublisher_Vendor\Twig\Node\Node;
use PodlovePublisher_Vendor\Twig\Token;
/**
 * Marks a section of a template to be escaped or not.
 *
 * @internal
 */
final class AutoEscapeTokenParser extends AbstractTokenParser
{
    public function parse(Token $token) : Node
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        if ($stream->test(Token::BLOCK_END_TYPE)) {
            $value = 'html';
        } else {
            $expr = $this->parser->getExpressionParser()->parseExpression();
            if (!$expr instanceof ConstantExpression) {
                throw new SyntaxError('An escaping strategy must be a string or false.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
            $value = $expr->getAttribute('value');
        }
        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideBlockEnd'], \true);
        $stream->expect(Token::BLOCK_END_TYPE);
        return new AutoEscapeNode($value, $body, $lineno);
    }
    public function decideBlockEnd(Token $token) : bool
    {
        return $token->test('endautoescape');
    }
    public function getTag() : string
    {
        return 'autoescape';
    }
}
