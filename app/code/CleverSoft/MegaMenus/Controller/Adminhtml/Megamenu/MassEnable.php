<?php
/**
 * @category    CleverSoft
 * @package     CleverMegaMenus
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\MegaMenus\Controller\Adminhtml\Megamenu;
use CleverSoft\MegaMenus\Controller\Adminhtml\MassStatus;
class MassEnable extends MassStatus{
	const ID_FIELD = 'id';
	protected $collection = 'CleverSoft\MegaMenus\Model\ResourceModel\Megamenu\Collection';
	protected $model = 'CleverSoft\MegaMenus\Model\Megamenu';
	protected $status = true;
}