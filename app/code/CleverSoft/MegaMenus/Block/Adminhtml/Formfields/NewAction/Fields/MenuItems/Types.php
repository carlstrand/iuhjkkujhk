<?php
/**
 * @category    CleverSoft
 * @package     CleverMegaMenus
 * @copyright   Copyright © 2017 CleverSoft., JSC. All Rights Reserved.
 * @author 		ZooExtension.com
 * @email       magento.cleversoft@gmail.com
 */

namespace CleverSoft\MegaMenus\Block\Adminhtml\Formfields\NewAction\Fields\MenuItems;
use \Magento\Framework\App\ObjectManager;
class Types extends \Magento\Backend\Block\Template
{
	protected $_assetRepo;
	protected $_itemTypes;
	
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		array $data = [])
    {
		$this->_assetRepo = $context->getAssetRepository();
		$this->_itemTypes = [];

        parent::__construct($context, $data);
    }
	protected function _getWidthClass(){
		$widthClass[] = ['label' => '-', 'value' => ''];
		for($i = 1; $i <= 24; $i++){
			$widthClass[] = ['label' => __('Width %1 %2: %3 px',$i,str_repeat('&nbsp;',ceil(4/(floor($i/10)+1))),round(($i/24)*1200)), 'value' => $i];
		}
		return $widthClass;
	}
	public function _construct(){
		parent::_construct();
		$types = [
			[
				'title' => __('Pages'),
				'name' => 'page',
				'placeholder' => __('<i class="fa fa-file-text-o"></i>'),
				'content' =>$this->getPagesMenu()
			],
			[
				'title' => __('Custom Link'),
				'name' => 'link',
				'placeholder' => __('<i class="fa fa-link"></i>'),
				'content' =>[
					['title' => __('Menu Item Title'), 'name' => 'label', 'type' => 'text'],
					['title' => __('Menu Item URL'), 'name' => 'url', 'type' => 'text'],
					['title' => __('Custom CSS Class'), 'name' => 'class', 'type' => 'text'],
					['title' => __('Menu Icon Item'), 'type' => 'label', 'value' => 'For example <i class="fa fa-diamond"></i> Diamond' ],
					['title' => __('Icon Item use'), 'name' => 'icon_type', 'type' => 'dropdown',
						'values' => [
							['label' => __('Fontawesome icons'), 'value' => '0'],
                            ['label' => __('Cleverfont icons'), 'value' => '2'],
							['label' => __('Select from library'), 'value' => '1']

						],
						'action' => 'clevermenu.switchIconChooser(this);'
					],
					['title' => __(''), 'name' => 'icon_font', 'type' => 'icon'],
					['title' => __(''), 'name' => 'icon_img', 'type' => 'image', 'style' => 'display:none', 'button_text' => __('Image Icons Library'), 'description' => 'Recommended size: at least 32px &times 32px'],
                    ['title' => __(''), 'name' => 'clever_icon_font', 'type' => 'clever_icon', 'style' => 'display:none', 'button_text' => __('Clever Icons Library')]
				]
			],
			[
				'title' => __('Grid Dropdown'),
				'name' => 'text',
				'placeholder' => __('<i class="fa fa-file-code-o"></i>'),
				'content' => [
					['title' => __('Custom CSS Class'), 'name' => 'class', 'type' => 'text'],
					['title' => __('Custom CSS Inline Style'), 'name' => 'style', 'type' => 'text',  'placeholder' => 'padding:20px; margin:30px;'],
					['title' => __('Grid Dropdown'), 'type' => 'heading'],
					['title' => __('Layout'), 'name' => 'layout', 'type' => 'layout',
						'layouts' => [[1],[1,1],[1,1,1],[1,1,1,1],[1,1,1,1,1,1],[1,2],[2,1],[1,1,2],[2,1,1],[1,2,1],[1,1,1,1,2],[2,1,1,1,1]]],
					['title' => __(''), 'name' => 'content', 'type' => 'editor', 'columns' => 1, 'value' => ''],
					['title' => __('<span style="margin-top: 12.5px;display: inline-block;">Dropdown Background Image</span>'), 'type' => 'label',
						'value' => 'For example <a class="full-view-img" data-href="'.$this->getImageUrl('menu/background.jpg').'" onclick="clevermenu.viewfull(this)" href="javascript:void(0)"><img src="'.$this->getImageUrl('menu/background_small.jpg').'" /></a> <a class="full-view-link" onclick="clevermenu.viewfull(this)" data-href="'.$this->getImageUrl('menu/background.jpg').'" href="javascript:void(0)">'.__('Click to view example').'</a>' ],
					['title' => __('Image'), 'name' => 'background', 'type' => 'image' ],
					['title' => __('Position'), 'name' => 'bg_position', 'type' => 'dropdown',
						'values' => [
							['label' => __('Left - Top'), 'value' => 'left_top'],
							['label' => __('Left - Bottom'), 'value' => 'left_bottom'],
							['label' => __('Right - Top'), 'value' => 'right_top'],
							['label' => __('Right - Bottom'), 'value' => 'right_bottom'],
						]
					],
					['title' => __('X (px)'), 'name' => 'bg_position_x', 'type' => 'text', 'value' => '0'],
					['title' => __('Y (px)'), 'name' => 'bg_position_y', 'type' => 'text', 'value' => '0'],
				]
			],
			[
				'title' => __('Categories List'),
				'name' => 'category',
				'placeholder' => __('<i class="fa fa-th-list"></i>'),
				'content' => [
					['title' => __('Menu Item Title'), 'name' => 'label', 'type' => 'text'],
					['title' => __('URL'), 'name' => 'url', 'type' => 'text'],
					['title' => __('Parent Cat ID'), 'name' => 'category', 'type' => 'category'],
					['title' => __('Custom CSS Class'), 'name' => 'class', 'type' => 'text'],
					['title' => __('Display Type'), 'name' => 'display_type', 'type' => 'dropdown',
						'values' => [
							['label' => __('Show categories list as a drop down menu of item title'), 'value' => 0],
							['label' => __('Show categories list just below item title'), 'value' => 1]
						]
					],
					['title' => __('Menu Icon Item'), 'type' => 'label', 'value' => 'For example <i class="fa fa-diamond"></i> Diamond' ],
					['title' => __(''), 'name' => 'icon_type', 'type' => 'dropdown',
						'values' => [
							['label' => __('Fontawesome icons'), 'value' => '0'],
                            ['label' => __('Cleverfont icons'), 'value' => '2'],
							['label' => __('Select from library'), 'value' => '1'],
						],
						'action' => 'clevermenu.switchIconChooser(this);'
					],
					['title' => __(''), 'name' => 'icon_font', 'type' => 'icon'],
					['title' => __(''), 'name' => 'icon_img', 'type' => 'image', 'style' => 'display:none', 'button_text' => __('Image Icons Library'), 'description' => 'Recommended size: at least 32px &times 32px'],
                    ['title' => __(''), 'name' => 'clever_icon_font', 'type' => 'clever_icon', 'style' => 'display:none', 'button_text' => __('Clever Icons Library')]
				]
			],
		];
		$this->setItemTypes($types);
	}
	public function getMediaUrl($url = ''){
		return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$url;
	}
	public function addNewType($type){
		array_push($this->_itemTypes,$type);
		return $this;
	}

	public function getPagesMenu() {
		$object_manager = \Magento\Framework\App\ObjectManager::getInstance();
		$collection  = $object_manager->create('Magento\Cms\Model\ResourceModel\Page\Collection');
		$pages = $collection->toOptionIdArray();
		$html = array();
		foreach ($pages as $page) {
			$html[]= ['title' =>__($page['label']), 'name' => 'page_selections', 'type' => 'checkbox','value'=>$page['value']];
		}
		$add_content  = array();
		$add_content = [['title' => __('Menu Icon Item'), 'type' => 'label', 'value' => 'For example <i class="fa fa-diamond"></i> Diamond' ],
			['title' => __('Icon Item use'), 'name' => 'icon_type', 'type' => 'dropdown',
				'values' => [
					['label' => __('Fontawesome icons'), 'value' => '0'],
                    ['label' => __('Cleverfont icons'), 'value' => '2'],
					['label' => __('Select from library'), 'value' => '1']

				],
				'action' => 'clevermenu.switchIconChooser(this);'
			],
			['title' => __(''), 'name' => 'icon_font', 'type' => 'icon'],
			['title' => __(''), 'name' => 'icon_img', 'type' => 'image', 'style' => 'display:none', 'button_text' => __('Image Icons Library'), 'description' => 'Recommended size: at least 32px &times 32px'],
            ['title' => __(''), 'name' => 'clever_icon_font', 'type' => 'clever_icon', 'style' => 'display:none', 'button_text' => __('Clever Icons Library')]];
		return array_merge($html,$add_content);
	}

	public function setItemTypes($types){
		$this->_itemTypes = $types;
		return $this;	
	}
	public function getItemTypes(){
		return $this->_itemTypes;	
	}
	public function getItemTypesJson(){
		return json_encode($this->_itemTypes);
	}
	
	public function getColumnTemplates(){
		return
		[
			[
				'title' => __('Aplly for All'),
				'type' => 'heading'	
			],
			[
				'title' => __(''),
				'type' => 'layout01',
				'image' => $this->getImageUrl('layout_extension/itp.jpg'),
				'col' => 1
			],
			[
				'title' => __(''),
				'type' => 'layout03',
				'image' => $this->getImageUrl('layout_extension/tp.jpg'),
				'col' => 1
			],
			[
				'title' => __(''),
				'type' => 'layout05',
				'image' => $this->getImageUrl('layout_extension/tpi.jpg'),
				'col' => 1
			],
			[
				'title' => __(''),
				'type' => 'layout02',
				'image' => $this->getImageUrl('layout_extension/itl.jpg'),
				'col' => 1
			],

			[
				'title' => __(''),
				'type' => 'layout04',
				'image' => $this->getImageUrl('layout_extension/tl.jpg'),
				'col' => 1
			],

			[
				'title' => __(''),
				'type' => 'layout06',
				'image' => $this->getImageUrl('layout_extension/tli.jpg'),
				'col' => 1
			]
		];
	}
	public function getImageUrl($path){
		return $this->_assetRepo->getUrl('CleverSoft_MegaMenus/images/'.$path);
	}
}
?>