<?php
	namespace aggregator;

	/**
	* 
	*/
	class IntegrateContentToNewsEngine extends \AggregatorEngine
	{
		
		public function insertCacheDataToNewsDB() {
			$aggregators = $this->getAllPublishedAggregators();

			while ($aggregators->next()) {
				if ($aggregators->addToNewsModul == '1') {
					$newsArchiveIds = deserialize($aggregators->selectNewsArchive);
					foreach ($newsArchiveIds as $newsArchiveId) {
												
						$fileName = TL_ROOT.'/system/modules/aggregator/cache/'.$aggregators->id.'.json.cache';
						if (file_exists($fileName)) {

							$fp = fopen($fileName, 'r');
							$cacheContent = fread($fp, filesize($fileName));
							fclose($fp);
							$content = json_decode($cacheContent);

							$entryIds = array();
							foreach ($content as $entry) {

								$videoClass = '';
								if ($entry->item->type == 'video') {
									$videoClass = " video-thumb";
								}

								$imageUrl = '';
								$addImage = 0;
								if (!empty($entry->item->picture)) {
									$addImage = 1;
									$imageUrl = $entry->item->picture;
								}

								$teaser = '<p>'.$entry->item->message.'</p><a href="'.$entry->item->url.'" title="zum Newseintrag" target="_blank" class="external-link'.$videoClass.'"></a>';
								$shortText = \StringUtil::substr($teaser, 50);

								$author = $this->Database->query('SELECT id FROM tl_user WHERE admin = 1 LIMIT 1');

								$olderPost = $this->Database->query('SELECT * FROM tl_news WHERE alias = "facebook-post-'.$entry->id.'" AND pid = '.$newsArchiveId.' LIMIT 1');
								if ($olderPost->numRows < 1) {
									$this->Database->prepare('INSERT INTO tl_news (id, pid, tstamp, headline, alias, author, date, time, teaser, published, source, url, target, addImage, imageUrl) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
										->execute($this->getNewId('tl_news'), $newsArchiveId, time(), $shortText, 'facebook-post-'.$entry->id, $author->id, $entry->timestamp, $entry->timestamp, $teaser, 1,'external', 
											$entry->item->url, 1, $addImage, $imageUrl);
								} else {
									$this->Database->prepare('UPDATE tl_news SET tstamp = ?, headline = ?, teaser = ?, imageUrl = ?, addImage = ? WHERE id = ?')
									->execute(time(), $shortText, $teaser, $entry->item->picture, $addImage, $olderPost->id);
								}

								$entryIds[] = 'facebook-post-'.$entry->id;
							}

							$this->Database->query('DELETE FROM tl_news WHERE alias LIKE \'facebook-post-%\' AND alias NOT IN (\''.implode("', '", $entryIds).'\') AND pid = '.$newsArchiveId);
						}
					}
				}
			}
		}

		private function getAllPublishedAggregators() {
			return $this->Database->execute('SELECT * FROM tl_aggregator WHERE published = 1');
		}

        private function getNewId($tableName) {
            $dbRes = $this->Database->prepare("SELECT MAX( id ) + 1 as id FROM ".$tableName)->execute()->fetchAssoc();
            if ($dbRes["id"] == null) {
                $dbRes["id"] = 1;
            }
            
            return $dbRes["id"];
        }

	}




?>