<?php

class NewsListAggregatorHook {

	public function parseFacebookPosts($objTemplate, $arrRow, $objModule) {
		
		if (strpos($arrRow['alias'], 'facebook-post') !== false) {
			$objTemplate->picture = array('img'=>array('src'=> $arrRow['imageUrl'], 'srcset'=>''), 'sources'=>array());
			$objTemplate->addImage = $arrRow['addImage'];
			$objTemplate->class .= ' facebook';
			$objTemplate->href = $arrRow['url'];
			$objTemplate->link = $arrRow['url'];
		} else {
			$objTemplate->class .= ' news';
		}

	}

	public function filterFacebookPosts($newsArchives, $blnFeatured, $limit, $offset, $thisModule) {
		$filteredNews = array();
		if ($thisModule->hideFacebookNews) {
			$objArchive = \NewsModel::findBy(array('pid IN ('.implode(',', deserialize($newsArchives)).') AND published = 1 AND alias NOT LIKE "facebook-post%" ORDER BY time desc LIMIT '.$limit), null);
			return $objArchive;
		}

		return $newsArchives;
	}
}