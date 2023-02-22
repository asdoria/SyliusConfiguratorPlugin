<p align="center">
</p>

![Logo Asdoria](doc/asdoria.jpg)

<h1 align="center">Asdoria Configurator Plugin</h1>

<p align="center">Product Configurator 
Improve the customer experience in a visual and intuitive environment to boost your sales ! Configure complex products easily in the back office of Sylius.</p>

## Features

+ We created the configurator based on our years of experience. We learned that a configurator must be flexible and easy to maintain.

That's why this configurator is built as close as possible to the Sylius model in an intelligent way. It is based on the Product Attributes and solves complex problems while keeping its simplicity.

<div style="max-width: 75%; height: auto; margin: auto">

![Add to Cart](doc/configurator.gif)

</div>

## Installation

---
1. Run `composer require asdoria/sylius-configurator-plugin`


2. Add the bundle in `config/bundles.php`. You must put the Configurator plugin line ABOVE SyliusGridBundle

```PHP
Asdoria\SyliusConfiguratorPlugin\AsdoriaSyliusConfiguratorPlugin::class => ['all' => true],
[...]
Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
```

3. Import routes in `config/routes.yaml`

```yaml
asdoria_product_configurator:
    resource: "@AsdoriaSyliusConfiguratorPlugin/Resources/config/routing.yaml"
```

4. Import config in `config/packages/_sylius.yaml`
```yaml
imports:
    - { resource: "@AsdoriaSyliusConfiguratorPlugin/Resources/config/config.yaml"}
```

5. Paste the following content to the `src/Repository/ProductAttributeRepository.php`:
```php
    <?php

    declare(strict_types=1);

    namespace App\Repository;

    use Asdoria\SyliusConfiguratorPlugin\Repository\Model\Aware\ProductAttributeRepositoryAwareInterface;
    use Asdoria\SyliusConfiguratorPlugin\Repository\Traits\ProductAttributeRepositoryTrait;
    use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository as BaseProductAttributeRepository;

    /**
     * Class ProductAttributeRepository
     * @package App\Repository
     *
     * @author  Philippe Vesin <pve.asdoria@gmail.com>
     */
    class ProductAttributeRepository extends BaseProductAttributeRepository implements ProductAttributeRepositoryAwareInterface
    {
        use ProductAttributeRepositoryTrait;
    }
```
   
6. Configure repositories in `config/packages/_sylius.yaml`:
```diff  
sylius_attribute:
    resources:
        product:
            attribute:
                classes:
                    model: App\Entity\Product\ProductAttribute
+                   repository: App\Repository\ProductAttributeRepository
```
7. Paste the following content to the `src/Entity/Order/OrderItem.php`:

```diff
<?php

declare(strict_types=1);

namespace App\Entity\Order;

+use Asdoria\SyliusConfiguratorPlugin\Model\Aware\ConfiguratorAwareInterface;
+use Asdoria\SyliusConfiguratorPlugin\Traits\OrderItem\ConfiguratorTrait;
use Sylius\Component\Core\Model\OrderItem as BaseOrderItem;
+use Asdoria\SyliusConfiguratorPlugin\Traits\OrderItem\AttributeValuesTrait;
+use Asdoria\SyliusConfiguratorPlugin\Model\Aware\AttributeValuesAwareInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_order_item")
 *
 */
class OrderItem extends BaseOrderItem
+ implements AttributeValuesAwareInterface, ConfiguratorAwareInterface
{    
    +use AttributeValuesTrait;
    +use ConfiguratorTrait;
    
    +public function __construct()
    +{
    +    $this->initializeAttributeValues();
    +    parent::__construct();
    +}

}
```

8. Paste the following content to `templates/bundles/SyliusShopBundle/Product/_info.html.twig`:
```twig
{% set product = variant.product %}

<div class="ui header">
    {% if variant.hasImages %}
        {% include '@SyliusShop/Product/_mainImage.html.twig' with {'product': variant, 'filter': 'sylius_shop_product_tiny_thumbnail'} %}
    {% else %}
        {% include '@SyliusShop/Product/_mainImage.html.twig' with {'product': product, 'filter': 'sylius_shop_product_tiny_thumbnail'} %}
    {% endif %}
    <div class="content">
        <a href="{{ path('sylius_shop_product_show', {'slug': product.slug}) }}">
            <div class="sylius-product-name" {{ sylius_test_html_attribute('product-name', item.productName) }}>{{ item.productName }}</div>
            <span class="sub header sylius-product-variant-code" {{ sylius_test_html_attribute('product-variant-code', variant.code) }}>
                {{ variant.code }}
            </span>
        </a>
    </div>
</div>
{% if product.hasOptions() %}
    <div class="ui horizontal divided list sylius-product-options" {{ sylius_test_html_attribute('product-options') }}>
        {% for optionValue in variant.optionValues %}
            <div class="item" data-sylius-option-name="{{ optionValue.name }}" {{ sylius_test_html_attribute('option-name', optionValue.name) }}>
                {{ optionValue.value }}
            </div>
        {% endfor %}
    </div>
{% elseif item.variantName is not null %}
    <div class="ui horizontal divided list">
        <div class="item sylius-product-variant-name" {{ sylius_test_html_attribute('product-variant-name') }}>
            {{ item.variantName }}
        </div>
    </div>
{% endif %}

+{% include '@AsdoriaSyliusConfiguratorPlugin/Shop/Cart/Summary/_attribute_value.html.twig' with {'item': item|default()} %}
```

9. Paste the following content to `templates/bundles/SyliusAdminBundle/Product/_info.html.twig`:
```twig
<div class="ui header">
    {% include '@SyliusAdmin/Product/_mainImage.html.twig' with {'product': product, 'filter': 'sylius_admin_product_tiny_thumbnail'} %}
    <div class="content">
        <div class="sylius-product-name" title="{{ item.productName }}">{{ item.productName }}</div>
        <span class="sub header sylius-product-variant-code" title="{{ variant.code }}">
            {{ variant.code }}
        </span>
    </div>
</div>
{% if product.hasOptions() %}
    <div class="ui horizontal divided list sylius-product-options">
        {% for optionValue in variant.optionValues %}
            <div class="item" data-sylius-option-name="{{ optionValue.name }}">
                {{ optionValue.value }}
            </div>
        {% endfor %}
    </div>
{% elseif item.variantName is not null %}
    <div class="ui horizontal divided list">
        <div class="item sylius-product-variant-name">
            {{ item.variantName }}
        </div>
    </div>
{% endif %}

+{% include '@AsdoriaSyliusConfiguratorPlugin/Admin/Order/Summary/_attribute_value.html.twig' with {'item': item|default()} %}
```

## Demo

You can try the Configurator plugin online by following this [link](https://demo-sylius.asdoria.fr/en_US/configurators/classic-embroidered-hat-with-your-customization).

Note that we have developed several other open source plugins for Sylius, their demos and documentations are listed in the [following link](https://asdoria.github.io/).

## Shop Usage

1. In the front-office, go to /en_US/configurators/{slug} route. [here!](https://demo-sylius.asdoria.fr/en_US/configurators/classic-embroidered-hat-with-your-customization)
2. You can use your own Javascript (VueJs, React, etc..) with [ajax requests](https://demo-sylius.asdoria.fr/en_US/configurators/ajax/by-slug/classic-embroidered-hat-with-your-customization) route to get the structure of the configurator. And after POST the payload in /en_US/configurators/{slug} route

```json
{
    "asdoria_configurator_add_to_cart":
    {
        "step": "xxx (id)",
        "cartItem":
        {
            "quantity": "1",
            "variant": "xxx (id)",
            "attributes":
            [
                {
                    "localeCode": "en_US",
                    "attribute": "xxx (code)",
                    "value": "xxx"
                }
            ]
        },
        "additionalItems":
        [
            {
                "cartItem":
                {
                    "quantity": "1"
                    "variant": "xxx (id)"
                }
            }
        ]
    }
}
```
## Back-office Usage

1. In the back-office, go to [configurators](https://demo-sylius.asdoria.fr/admin/configurators) route.
2. Click on [Step Management](https://demo-sylius.asdoria.fr/admin/configurator-steps) and create your different steps for your configurator.
3. [Create](https://demo-sylius.asdoria.fr/admin/configurators/new) your first configurator.
    + Choose your target products and your calculator.  
    + Go to the [configurator items page](https://demo-sylius.asdoria.fr/admin/configurators/1/configurator-items) to manage your rows. It is possible to create an attribute item row or an additional item row. If the attribute item row matches a multiple selection attribute, you can specify the available choices for each product in the product sheet.
    
4. Go to the front-office /en_US/configurators/{slug} [route](https://demo-sylius.asdoria.fr/en_US/configurators/classic-embroidered-hat-with-your-customization).

## Contributing

You can open an issue or a Pull Request if you want! 
Thank you!
