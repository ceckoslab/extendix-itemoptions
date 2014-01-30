<?php
/**
 * @author Tsvetan Stoychev <ceckoslab@gmail.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Extendix_ItemOptions_Helper_Option_Type_Date extends Mage_Core_Helper_Abstract
{

    protected $_option;

    public function init($option)
    {
        $this->_option = $option;
    }

    public function prepareDateForInternalFormat($value)
    {
        $timestamp = 0;

        if ($this->_dateExists()) {
                $timestamp += mktime(0, 0, 0, $value['month'], $value['day'], $value['year']);
        } else {
            $timestamp += mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        }

        if ($this->_timeExists()) {
            // 24hr hour conversion
            if (! $this->is24hTimeFormat()) {
                $pmDayPart = ('pm' == strtolower($value['day_part']));
                if (12 == $value['hour']) {
                    $value['hour'] = $pmDayPart ? 12 : 0;
                } elseif ($pmDayPart) {
                    $value['hour'] += 12;
                }
            }

            $timestamp += 60 * 60 * $value['hour'] + 60 * $value['minute'];
        }

        $date = new Zend_Date($timestamp);
        $result = $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        return $result;
    }

    /**
     * Does option have date?
     *
     * @return boolean
     */
    protected function _dateExists()
    {
        return in_array($this->_option->getType(), array(
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_DATE,
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_DATE_TIME
        ));
    }

    /**
     * Does option have time?
     *
     * @return boolean
     */
    protected function _timeExists()
    {
        return in_array($this->_option->getType(), array(
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_DATE_TIME,
            Mage_Catalog_Model_Product_Option::OPTION_TYPE_TIME
        ));
    }

    /**
     * Time Format
     *
     * @return boolean
     */
    public function is24hTimeFormat()
    {
        return true;
    }

}