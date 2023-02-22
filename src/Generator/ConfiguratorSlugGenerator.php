<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Asdoria\SyliusConfiguratorPlugin\Generator;

use Asdoria\SyliusConfiguratorPlugin\Model\ConfiguratorInterface;
use Behat\Transliterator\Transliterator;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Webmozart\Assert\Assert;

final class ConfiguratorSlugGenerator implements ConfiguratorSlugGeneratorInterface
{
    public function generate(ConfiguratorInterface $configurator, ?string $locale = null): string
    {
        $name = $configurator->getTranslation($locale)->getName();

        $slug = $this->generateSlug($name);

        return $slug;
    }

    public function generateSlug(string $name, ?string $locale = null): string
    {
        Assert::notEmpty($name, 'Cannot generate slug without a name.');

        return $this->transliterate($name);
    }

    private function transliterate(string $string): string
    {
        // Manually replacing apostrophes since Transliterator started removing them at v1.2.
        return Transliterator::transliterate(str_replace('\'', '-', $string));
    }
}
