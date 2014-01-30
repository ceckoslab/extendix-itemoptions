<?php
/**
 * @author Tsvetan Stoychev <ceckoslab@gmail.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Extendix_ItemOptions_Adminhtml_ItemController extends Mage_Adminhtml_Controller_Action
{

    public function edit_optionsAction()
    {
        $itemId = $this->getRequest()->getParam('item_id', null);

        $html = '';
        if(!is_null($itemId)) {
            $orderOptionsHelper = Mage::helper('extendix_itemoptions/popup_options');
            $html = $orderOptionsHelper->getOrderOptionsEditHtml($this, $itemId);
        }

        $this->getResponse()->setBody($html);
    }

    public function update_optionsAction()
    {
        $itemId = $this->getRequest()->getParam('item_id', null);
        $options = $this->getRequest()->getPost('options', array());

        if(!is_null($itemId)) {
            $orderOptionsHelper = Mage::helper('extendix_itemoptions/popup_options');
            $orderOptionsHelper->updateItemOptions($itemId, $options);
        }
    }

}