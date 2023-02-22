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

interface ConfiguratorSlugGeneratorInterface
{
    public function generate(ConfiguratorInterface $configurator, ?string $locale = null): string;


    public function generateSlug(string $name, ?string $locale = null): string;
}
