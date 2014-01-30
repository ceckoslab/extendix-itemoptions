<?php
/**
 * @author Tsvetan Stoychev <ceckoslab@gmail.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Extendix_ItemOptions_Helper_Popup_Link extends Mage_Core_Helper_Abstract
{

    const POPUP_LINK_BLOCK_TYPE = 'extendix_itemoptions/adminhtml_popup_link';

    /**
     * Getter for the html of the edit options link
     *
     * @param Mage_Sales_Model_Quote_Item $item
     * @return string
     */
    public function getEditLinkHtml($item)
    {
        $html = '';
        $options = $item->getProductOptions();
        if (!empty($options['options'])) {
            $html = Mage::app()->getLayout()->createBlock(self::POPUP_LINK_BLOCK_TYPE, 'test', array('item' => $item))->toHtml();
        }
        return $html;
    }

}