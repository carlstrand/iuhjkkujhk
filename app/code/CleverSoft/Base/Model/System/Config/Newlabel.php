<?php
/**
 * @category    CleverSoft
 * @package     CleverBase
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\Base\Model\System\Config;
class Newlabel implements \Magento\Framework\Option\ArrayInterface {
    public function toOptionArray()
    {

        $types = [
            ['value' => 'new', 'label' => __('New')],
            ['value' => 'imagenew', 'label' => __('Image')],
            ['value' => '', 'label' => __('No')],
        ];

        return $types;
    }
}