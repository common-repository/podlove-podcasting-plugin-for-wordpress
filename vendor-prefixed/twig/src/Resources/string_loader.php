<?php

namespace PodlovePublisher_Vendor;

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use PodlovePublisher_Vendor\Twig\Environment;
use PodlovePublisher_Vendor\Twig\Extension\StringLoaderExtension;
use PodlovePublisher_Vendor\Twig\TemplateWrapper;
/**
 * @internal
 *
 * @deprecated since Twig 3.9
 */
function twig_template_from_string(Environment $env, $template, ?string $name = null) : TemplateWrapper
{
    trigger_deprecation('twig/twig', '3.9', 'Using the internal "%s" function is deprecated.', __FUNCTION__);
    return StringLoaderExtension::templateFromString($env, $template, $name);
}
