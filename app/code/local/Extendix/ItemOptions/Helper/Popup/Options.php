<?php
/**
 * @author Tsvetan Stoychev <ceckoslab@gmail.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Extendix_ItemOptions_Helper_Popup_Options extends Mage_Core_Helper_Abstract
{

    public function getOrderOptionsEditHtml($controller, $itemId)
    {
        $orderItem = Mage::getModel('sales/order_item')->load($itemId);

        $storeId = $orderItem->getStoreId();
        $productId = $orderItem->getProductId();

        $product = Mage::getModel('catalog/product')
            ->setStoreId($storeId)
            ->load($productId);

        if (!$product->getId()) {
            Mage::throwException($this->__('Product is not loaded.'));
        }

        Mage::register('current_product', $product);
        Mage::register('product', $product);

        // Prepare buy request values
        $buyRequest = $orderItem->getBuyRequest();
        if ($buyRequest) {
            Mage::helper('catalog/product')->prepareProductOptions($product, $buyRequest);
        }

        $this->_initConfigureResultLayout($controller);
        return $controller->getLayout()->getBlock('order.item.customoptions.edit.form')->toHtml();
    }

     /**
      * Init composite product configuration layout
      *
      * @param Mage_Adminhtml_Controller_Action $controller
      * @return Extendix_ItemOptions_Helper_Popup_Options
      */
    protected function _initConfigureResultLayout($controller)
    {
        $update = $controller->getLayout()->getUpdate();

        $update->addHandle('ADMINHTML_ORDER_EDIT_CUSTOM_OPTIONS');

        $controller->loadLayoutUpdates()->generateLayoutXml()->generateLayoutBlocks();
        return $this;
    }

    public function updateItemOptions($itemId, $updatedOptions)
    {

        if($updatedOptions) {
            $orderItem = Mage::getModel('sales/order_item')->load($itemId);

            $storeId = $orderItem->getStoreId();

            $orderItemOptions = $orderItem->getProductOptions();

            $this->updateBuyRequest($orderItemOptions, $updatedOptions);

            try {
                $this->updateOptionsValues($orderItemOptions, $updatedOptions, $storeId, $orderItem);
            } catch(Exception $e) {
                echo $e->getMessage();
            }

            $orderItem->setProductOptions($orderItemOptions);

            $orderItem->save();
        }
    }

    private function updateBuyRequest(&$orderItemOptions, $options)
    {
        $orderItemOptions['info_buyRequest']['options'] = array_replace($orderItemOptions['info_buyRequest']['options'], $options);
    }

    private function updateOptionsValues(&$orderItemOptions, $updatedOptions, $storeId)
    {
        foreach($orderItemOptions['options'] as $key => $originalOption) {
            $originalOptionId = $originalOption['option_id'];
            if(!empty($updatedOptions[$originalOptionId])) {
                $updateOptionValue = $updatedOptions[$originalOptionId];

                $newOptionValueObject = $this->prepareOptionValueObject($originalOptionId, $updateOptionValue, $storeId);

                $orderItemOptions['options'][$key]['option_value'] = $newOptionValueObject->getInternalValue();
                $orderItemOptions['options'][$key]['value'] = $newOptionValueObject->getFormattedValue();
                $orderItemOptions['options'][$key]['print_value'] = $newOptionValueObject->getFormattedValue();
            }
        }
    }

    /**
     * Prepare profile order item info
     */
    public function prepareOptionValueObject($optionId, $value, $storeId)
    {
            $option = Mage::getModel('catalog/product_option')->getCollection()
                ->addIdsToFilter($optionId)
                ->addValuesToResult($storeId)
                ->getFirstItem();

            $optionTypeInstance = $option->groupFactory($option->getType())
                ->setOption($option);

            //We need to prepare the internal format of the date if we update option of this type
            if(Mage_Catalog_Model_Product_Option::OPTION_GROUP_DATE == $option->getGroupByType($option->getType())) {
                $dateHelper = Mage::helper('extendix_itemoptions/option_type_date');
                $dateHelper->init($option);
                $value = $dateHelper->prepareDateForInternalFormat($value);

            }

            //We need to prepare the internal format of the muliselect and checkbox if we update option of this type
            if(Mage_Catalog_Model_Product_Option::OPTION_TYPE_CHECKBOX == $option->getType()) {
                $value = implode(',', $value);
            }

            //We need to prepare the internal format of the muliselect and checkbox if we update option of this type
            if(Mage_Catalog_Model_Product_Option::OPTION_TYPE_MULTIPLE == $option->getType()) {
                $value = $value[0];
            }

            $formattedValue = $optionTypeInstance->getFormattedOptionValue($value);

            $optionObject = new Varien_Object();
            $optionObject->addData(array(
                    'internal_value' => $value,
                    'formatted_value' => $formattedValue
                )
            );

            return $optionObject;
    }

}