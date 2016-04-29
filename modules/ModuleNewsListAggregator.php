<?php


class ModuleNewsListAggregator extends \ModuleNewsList {

	public function generate() {

		$filteredNews = array();
		if ($this->hideFacebookNews) {
			$objArchive = \NewsArchiveModel::findMultipleByIds(deserialize($this->news_archives));
			// var_dump($objArchive);
			if ($objArchive !== null)
			{
				while ($objArchive->next())
				{
					if (strpos($objArchive->alias, 'facebook-post') !== false) {
						continue;
					}
					$filteredNews[] = $objArchive;
				}
			}
			$this->news_archives = $filteredNews;
		}
		
		return parent::generate();
	}

}