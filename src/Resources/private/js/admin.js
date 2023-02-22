import * as moveConfiguratorItems from './sylius-move-configurator-items';
// import $ from "jquery";
import './sylius-prototype-handler';

document.addEventListener('DOMContentLoaded', () => {
  $('.asdoria-update-configurator-items').moveConfiguratorItems($('.asdoria-configurator-item-position'));

  $('#asdoria_product_configurator_calculator').handlePrototypes({
    prototypePrefix: 'asdoria_configurator_calculator_calculators',
    containerSelector: '.configuration',
  });
  $('#asdoria_configurator_item_product_attribute_calculator').handlePrototypes({
    prototypePrefix: 'asdoria_configurator_item_product_attribute_calculator_calculators',
    containerSelector: '.configuration',
  });
  $('#asdoria_configurator_item_calculator').handlePrototypes({
    prototypePrefix: 'asdoria_configurator_item_calculator_calculators',
    containerSelector: '.configuration',
  });
});


