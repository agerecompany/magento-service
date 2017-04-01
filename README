# Magento Service Module

Very often customers want specific functionality for theirs stores, such as CallMe, PreOrder, TestDrive etc.
Main characteristic is pop-up windows with form and send email to seller.

This module is base and contain core code. Other module simply usage its.
Module is based on (jQuery Fancybox library)[http://fancyapps.com].

## Usage
Your can attach specific options to your element and the call `AgereService.activate` callback
```
<?php $jsOptions = json_encode(array(
    'product' => $_product->getId(),
    'mode' => Mage::getStoreConfig('agere_preOrder/settings/mode'),
    'fancybox' => array(
        'type' => "ajax",
        'href' => $this->getUrl('pre-order/index/getForm'),
    ),
)); ?>
<div class="add-to-cart">
    <button type="button" title="<?php echo $buttonTitle ?>" class="button btn-cart" data-options='<?php echo $jsOptions ?>'>
        <span><span><i class="icon-cart"></i><?php echo $buttonTitle ?></span></span>
    </button>
</div>
```
```
jQuery('.add-to-cart .btn-cart').click(AgereService.activate);
```
