<?php
namespace aggregator;

use Contao\Controller;
use Contao\Input;
use Contao\Message;
use Contao\String;
use Contao\System;

class IntegrateContentToNewsEngine extends \AggregatorEngine
{

	public function insertCacheDataToNewsDB($aggregarorId=0) {
		$aggregators = $this->getAllPublishedAggregators($aggregarorId);

		$stdAuthorID=1;
		$stdAuthor = $this->Database->query('SELECT id FROM tl_user WHERE admin = 1 LIMIT 1')->fetchAssoc();
		if($stdAuthor)
			$stdAuthorID=$stdAuthor["id"];

		$entryIds = array();

		if(isset($aggregators)) {
			while ($aggregators->next()) {
				if ($aggregators->addToNewsModul == '1') {
					$newsArchiveIds = deserialize($aggregators->selectNewsArchive);
					foreach ($newsArchiveIds as $newsArchiveId) {

						$fileName = TL_ROOT . '/system/modules/aggregator/cache/' . $aggregators->id . '.json.cache';
						if (file_exists($fileName)) {

							$fp           = fopen($fileName, 'r');
							$cacheContent = fread($fp, filesize($fileName));
							fclose($fp);
							$content = json_decode($cacheContent);


							foreach ($content as $entry) {

								$txtArr   = self::convertText($entry->item->message);

								$teaser   = $txtArr[1];
								$headline = $txtArr[0];
								if (!isset($txtArr[0]) || empty($txtArr[0])) {
									$headline = $entry->author->name;
								}
								$alias = 'facebook-post-' . $entry->id . '-' . $newsArchiveId;

								$authorid = $stdAuthorID;
								$author   = $this->Database->query(
									"SELECT id FROM tl_user WHERE admin = 1 AND name LIKE '%" . $entry->author->name
									. "%'LIMIT 1"
								)->fetchAssoc();
								if ($author) {
									$authorid = $author["id"];
								}
								$author = $authorid;

								$published = $entry->item->published ? "1" : "";
								$source    = 'external';
								$url       = $entry->item->url;
								$target    = 1;

								$imageUrl = '';
								$addImage = 0;
								if (isset($entry->item->picture) && !empty($entry->item->picture)) {
									$imageUrl = $entry->item->picture;
								}
								if (!empty($entry->item->picture) && $entry->item->type == "photo") {
									$addImage = 1;
								}
								$type      = $entry->item->type;
								$plattform = $entry->plattform;

								$olderPost = $this->Database->query(
									'SELECT * FROM tl_news WHERE alias = "' . $alias . '" LIMIT 1'
								);
								if ($olderPost->numRows < 1) {
									$this->Database->prepare(
										'INSERT INTO tl_news (id, pid, tstamp, headline, alias, author, date, time, teaser, published, source, url, target, addImage, imageUrl, link, type, plattform, articleId) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
									)
										->execute(
											$this->getNewId('tl_news'),
											$newsArchiveId,
											time(),
											$headline,
											$alias,
											$author,
											$entry->timestamp,
											$entry->timestamp,
											$teaser,
											$published,
											$source,
											$url,
											$target,
											$addImage,
											$imageUrl,
											$entry->item->link,
											$type,
											$plattform,
											$aggregators->id
										);
								} else {
									$this->Database->prepare(
										'UPDATE tl_news SET tstamp = ?, headline = ?, teaser = ?, imageUrl = ?, addImage = ?, published = ? WHERE id = ?'
									)
										->execute(
											time(),
											$headline,
											$teaser,
											$imageUrl,
											$addImage,
											$published,
											$olderPost->id
										);
								}

								$entryIds[] = $alias;
							}
							$this->Database->query(
								'DELETE FROM tl_news WHERE alias LIKE \'facebook-post-%\' AND alias NOT IN (\''
								. implode("', '", $entryIds) . '\') AND pid = ' . $newsArchiveId. ' AND articleId ='.$aggregators->id
							);
						}
					}
				}
			}
		}
	}

	private function getAllPublishedAggregators($id=0) {
		$ret=null;
		if($id>0)
			$ret=$this->Database->prepare('SELECT * FROM tl_aggregator WHERE id=? AND published = 1')->execute($id);
		else
			$ret=$this->Database->execute('SELECT * FROM tl_aggregator WHERE published = 1');
		if(!isset($ret) || !$ret){
			System::log("No published aggregator found!","IntegrateContentToNewsEngine::getAllPublishedAggregators",TL_ERROR);
			if(TL_MODE=="BE" && $id>0)
				Message::addError("Der Aggregator ist nicht veröffentlicht!");
		}
		return $ret;
	}

	private function getNewId($tableName) {
		$dbRes = $this->Database->prepare("SELECT MAX( id ) + 1 as id FROM ".$tableName)->execute()->fetchAssoc();
		if ($dbRes["id"] == null) {
			$dbRes["id"] = 1;
		}

		return $dbRes["id"];
	}

	public function manuelUpdate($dc=null){
        $id=\Input::get("id");
        if($id && isset($dc)){
            $dc->Database->prepare("Update tl_aggregator SET lastUpdate=0 WHERE id=?")->execute($id);
        }
		$this->checkForAggregatorUpdates($id);
		$this->insertCacheDataToNewsDB($id);
		if(isset($dc)){
			$url= Controller::addToUrl("",true,array("key"));
			Controller::redirect($url);
		}
	}

	public static function convertText($textStr,$stringMaxcount=70){
		$textStr = self::removeEmoticons($textStr);
        $retnArr=array(0=>"",1=>"");
        if(isset($textStr) && !empty($textStr)) {
            $retnArr = preg_split("/(?<=[.:!?] )/", $textStr,2,PREG_SPLIT_NO_EMPTY);
            if(strlen($retnArr[0])>$stringMaxcount){
                $newHead=\String::substrHtml($retnArr[0],$stringMaxcount,false);
                $add=substr($retnArr[0],strlen($newHead));
                $retnArr[1]= $add.$retnArr[1];
                $retnArr[0]= $newHead;
            }

        }
        return $retnArr;
	}

	public static function removeEmoticons($text) {
		$clean_text = "";

	    // Match Emoticons
	    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
	    $clean_text = preg_replace($regexEmoticons, '', $text);

	    // Match Miscellaneous Symbols and Pictographs
	    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
	    $clean_text = preg_replace($regexSymbols, '', $clean_text);

	    // Match Transport And Map Symbols
	    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
	    $clean_text = preg_replace($regexTransport, '', $clean_text);

	    // Match Miscellaneous Symbols
	    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
	    $clean_text = preg_replace($regexMisc, '', $clean_text);

	    // Match Dingbats
	    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
	    $clean_text = preg_replace($regexDingbats, '', $clean_text);

	    return $clean_text;
	}
}
