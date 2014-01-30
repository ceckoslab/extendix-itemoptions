<?php
/**
 * @author Tsvetan Stoychev <ceckoslab@gmail.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Extendix_ItemOptions_Block_Adminhtml_Popup_Link extends Mage_Adminhtml_Block_Template
{

    public function __construct()
    {
        $this->setTemplate('extendix/itemoptions/popup/link.phtml');
    }

    /**
     * Getter for Sales Item id
     *
     * @return string
     */
    protected function getItemId()
    {
        return $this->getItem()->getId();
    }

}