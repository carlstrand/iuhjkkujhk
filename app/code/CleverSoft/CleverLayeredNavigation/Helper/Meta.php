<?php
/**
 * @category    CleverSoft
 * @package     CleverLayeredNavigation
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\CleverLayeredNavigation\Helper;

use CleverSoft\CleverLayeredNavigation\Api\Data\FilterSettingInterface;
use CleverSoft\CleverLayeredNavigation\Model\Source\IndexMode;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Page\Config;


class Meta extends AbstractHelper
{
    /** @var  \CleverSoft\CleverLayeredNavigation\Helper\Data */
    protected $dataHelper;

    /** @var  RequestInterface */
    protected $request;

    public function __construct(Context $context, \CleverSoft\CleverLayeredNavigation\Helper\Data $dataHelper)
    {
        parent::__construct($context);
        $this->dataHelper = $dataHelper;
        $this->request = $context->getRequest();
    }

    public function setPageTags(Config $pageConfig)
    {
        $robots = $pageConfig->getRobots();

        if (!$this->scopeConfig->getValue('clevershopby/robots/control_robots'))
        {
            return;
        }

        $index = true;
        $follow = true;

        $appliedFiltersSettings = $this->dataHelper->getSelectedFiltersSettings();
        foreach ($appliedFiltersSettings as $row) {
            /** @var FilterSettingInterface $setting */
            $setting = $row['setting'];

            /** @var FilterInterface $filter */
            $filter = $row['filter'];

            $value = $this->request->getParam($filter->getRequestVar());
            $count = count(explode(',', $value));

            if ($setting->getIndexMode() == IndexMode::MODE_NEVER) {
                $index = false;
            }
            elseif ($setting->getIndexMode() == IndexMode::MODE_SINGLE_ONLY && $count >= 2) {
                $index = false;
            }

            if ($setting->getFollowMode() == IndexMode::MODE_NEVER) {
                $follow = false;
            }
            elseif ($setting->getFollowMode() == IndexMode::MODE_SINGLE_ONLY && $count >= 2) {
                $follow = false;
            }
        }

        if (!$index) {
            $robots = preg_replace('/\w*index/i', 'noindex', $robots);
        }
        if (!$follow) {
            $robots = preg_replace('/\w*follow/i', 'nofollow', $robots);
        }

        $pageConfig->setRobots($robots);
    }
}
