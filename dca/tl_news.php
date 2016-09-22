<?php

$GLOBALS['TL_DCA']['tl_news']['config']['onload_callback'][] = array('tl_news_aggregator', 'loadParentChildRecordCallback');


$GLOBALS['TL_DCA']['tl_news']['fields']['plattform'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_news']['plattform'],
    'default'                 => "contao",
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50',"doNotSaveEmpty"=>true,"disabled"=>true),
	'sql'                     => "varchar(64) NOT NULL default 'contao'"
);

$GLOBALS['TL_DCA']['tl_news']['fields']['type'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_news']['type'],
	'inputType'               => 'text',
    'default'                 => "status",
    'eval'                    => array('tl_class'=>'w50',"doNotSaveEmpty"=>true, "disabled"=>true),
	'sql'                     => "varchar(64) NOT NULL default 'status'"
);
$GLOBALS['TL_DCA']['tl_news']['fields']['link'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['link'],
	'inputType'               => 'text',
	'eval'                    => array("disabled"=>true, 'rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50 wizard'),
	'sql'                     => "varchar(255)"
);



class tl_news_aggregator extends \tl_news {

	private $parentChildRecordCallbackFunctionName;

	public function loadParentChildRecordCallback() {
		$GLOBALS['TL_DCA']['tl_news']['list']['operations']['edit']['button_callback'] = array('tl_news_aggregator', 'hideFacebookNewsOperations');
		$GLOBALS['TL_DCA']['tl_news']['list']['operations']['editheader']['button_callback'] = array('tl_news_aggregator', 'hideFacebookNewsOperations');
		$GLOBALS['TL_DCA']['tl_news']['list']['operations']['copy']['button_callback'] = array('tl_news_aggregator', 'hideFacebookNewsOperations');
		$GLOBALS['TL_DCA']['tl_news']['list']['operations']['cut']['button_callback'] = array('tl_news_aggregator', 'hideFacebookNewsOperations');
		$GLOBALS['TL_DCA']['tl_news']['list']['operations']['delete']['button_callback'] = array('tl_news_aggregator', 'hideFacebookNewsOperations');

		$this->parentChildRecordCallbackFunctionName = $GLOBALS['TL_DCA']['tl_news']['list']['sorting']['child_record_callback'][1];
		$GLOBALS['TL_DCA']['tl_news']['list']['sorting']['child_record_callback'] = array('tl_news_aggregator', 'addFacebookIcon');
	}

	public function hideFacebookNewsOperations($row, $href, $label, $title, $icon, $attributes) {
		if (strpos($row['alias'], 'facebook-post') !== false) {
			return ' ';
		}
		return ($this->User->isAdmin || !$row['admin']) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}

	public function addFacebookIcon($arrRow) {
		$functionName = $this->parentChildRecordCallbackFunctionName;
		$parentReturnValue = parent::$functionName($arrRow);

		if (strpos($arrRow['alias'], 'facebook-post') !== false) {
			return '<img title=\'facebook-news\' class="facebook-icon" src="system/modules/aggregator/assets/img/fb.png" alt="facebook-news"  />'.$parentReturnValue;
		}

		return $parentReturnValue;
	}
}