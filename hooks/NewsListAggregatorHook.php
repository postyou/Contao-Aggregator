<?php

class NewsListAggregatorHook {

	public function parseFacebookPosts($objTemplate, $arrRow, $objModule) {
		
		if($objModule instanceof \Contao\ModuleNews) {

			$objTemplate->type=$arrRow['type'];
			$objTemplate->plattform=$arrRow['plattform'];
			$objTemplate->text_only_mode=$objModule->text_only_mode==="1";
            $objTemplate->link=$arrRow['link'];
			$objTemplate->link_text=
				self::renderLinkContent($objTemplate->link,
										$objTemplate->text_only_mode,
										$objTemplate->plattform,
										$objTemplate->type,
                                        $arrRow['imageUrl']
                );
            if ($objModule->messageLength === '*' || empty($arrRow['teaser'])) {
                $objTemplate->teaser = $arrRow['teaser'];
            } else {
                $objTemplate->teaser = String::substrHtml($arrRow['teaser'], intval($objModule->messageLength.'...'));
            }

			if (strpos($arrRow['alias'], 'facebook-post') !== false) {
				$objTemplate->picture  =
					array('img' => array('src' => $arrRow['imageUrl'], 'srcset' => ''), 'sources' => array());
				$objTemplate->addImage = $arrRow['addImage'];
				$objTemplate->class .= " ".$arrRow['plattform'];
				$objTemplate->href = $arrRow['url'];
				$objTemplate->attributes .= 'target="_blank"';
			} else {
				$objTemplate->class .= ' news';
			}

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
	
    private  function renderLinkSimple($url,$title=""){
        if(empty($title))
            $title=$this->get_title($url);
        return "<a href='".$url."' target='_blank'>".$title."</a>";
    }
    
	public  function renderLinkContent($url,$textOnlyModeOn,$plattform,$type,$imageUrl){
        if(isset($url) && !empty($url) && $tpye=="link"){
            if($textOnlyModeOn)
                return $this->renderLinkSimple($url);
            else{
                return $this->renderSpecial($url, $plattform, $type, $imageUrl);
            }
        }
		return "";
		
		
	}
	
	public function renderSpecial($url,$plattform,$type,$imageUrl=null){
        $strBuffer="";
        $title=$this->get_title($url);
        if(isset($imageUrl))
            $strBuffer= "<a href='".$url."' target='_blank'>".
            \Contao\Image::getHtml($imageUrl,$title).
            $title."</a>";
        else
            return $this->renderLinkSimple($url,$title);
        return $strBuffer;

    }

    private function get_title($url){
        $parse=parse_url($url);
        $str=$parse["host"];
        $tmp = @file_get_contents($url);
        if(isset($tmp) && !empty($tmp)){
            $tmp = trim(preg_replace('/\s+/', ' ', $tmp)); // supports line breaks inside <title>
            preg_match("/\<title\>(.*)\<\/title\>/i",$tmp,$title); // ignore case
            $str= $title[1];
        }
        return $str;
    }
}