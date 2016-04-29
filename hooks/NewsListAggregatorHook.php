<?php

class NewsListAggregatorHook {

	public function parseFacebookPosts($objTemplate, $arrRow, $objModule) {
		
		if (strpos($arrRow['alias'], 'facebook-post') !== false) {
			$objTemplate->picture = array('img'=>array('src'=> $arrRow['imageUrl'], 'srcset'=>''), 'sources'=>array());
			$objTemplate->addImage = $arrRow['addImage'];
			$objTemplate->class .= ' facebook';
		} else {
			$objTemplate->class .= ' news';
		}

	}

	
}