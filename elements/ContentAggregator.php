<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2014 Leo Feyer
 * 
 * @package   aggregator 
 * @author    Johannes Terhürne
 * @license   MIT License
 * @copyright Johannes Terhürne 2014 
 */

namespace Aggregator;
 
class ContentAggregator extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_aggregator';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### AGGREGATOR ###';
			$objTemplate->title = $this->headline;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$channels = unserialize($this->channels);
		
		$channelData = array();
		
		$count = 0;
		foreach($channels as $channel)
		{
			if(file_exists(TL_ROOT.'/system/modules/aggregator/cache/'.$channel.'.json.cache'))
			{
				$data = json_decode(file_get_contents(TL_ROOT.'/system/modules/aggregator/cache/'.$channel.'.json.cache'), true);
				if($count == 0)
				{
					$channelData = $data;
				} else {
					$channelData = array_merge($channelData, $data);
				}
				$count++;
			}
		}
		
		$timestamps = array();
		
		if(count($channelData) > 0){
			foreach ($channelData as $channel)
			{
				$timestamps[] = $channel['timestamp'];
			}
		
			array_multisort($timestamps, SORT_DESC, $channelData);
		
			$output = array();
				
			for($i=0; $i<$this->numPosts; $i++)
			{
				$output[$i] = $channelData[$i];
			}
			
			$this->Template->items = $this->parseItems($output);
		}else{
			$this->Template->empty = $GLOBALS['TL_LANG']['MSC']['noAggregatorData'];
		}
	}
	
	protected function parseItems($arrItems)
	{
		$limit = count($arrItems);

		if ($limit < 1)
		{
			return array();
		}

		$count = 0;
		$newArrItems = array();
		
		foreach($arrItems as $item)
		{
			$newArrItems[] = $this->parseItem($item, ((++$count == 1) ? ' first' : '') . (($count == $limit) ? ' last' : '') . ((($count % 2) == 0) ? ' odd' : ' even'), $count);
		}

		return $newArrItems;
	}
	
	protected function parseItem($arrItem,  $strClass='', $intCount=0)
	{
		global $objPage;
		
		if($arrItem != NULL){
		
			$objTemplate = new \FrontendTemplate('aggregator_'.$arrItem['plattform']);
		
			$objTemplate->authorLink = $arrItem['author']['url'];
			$objTemplate->authorPicture = $arrItem['author']['picture'];
			$objTemplate->authorName = $arrItem['author']['name'];
			$objTemplate->class = $strClass;
			$objTemplate->date = \Date::parse($objPage->datimFormat, $arrItem['timestamp']);
			$objTemplate->datetime = $objTemplate->datetime = date('Y-m-d\TH:i:sP', $arrItem['timestamp']);
			$objTemplate->teaser = $arrItem['item']['message'];
			$objTemplate->hasImage = $arrItem['item']['picture'] ? true : false;
			$objTemplate->imgUrl = $arrItem['item']['picture'];
			$objTemplate->imgAlt = substr($arrItem['item']['message'], 0, 24);
			$objTemplate->more = sprintf('<a href="%s" target="_blank" title="%s">%s</a>', $arrItem['item']['url'], specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], substr($arrItem['item']['message'], 0, 12)), true), $GLOBALS['TL_LANG']['MSC']['more']);
			$objTemplate->link = $arrItem['item']['url'];
		
			switch($arrItem['plattform']){
				case 'facebook':
					$actionArray = array(
						array(
							'title'	=>	$GLOBALS['TL_LANG']['MSC']['facebookShare'],
							'link'	=> 'https://www.facebook.com/sharer/sharer.php?u='.$arrItem['item']['url']
						)	
					);
					$objTemplate->actions = $this->generateActions($actionArray);
				break;
				case 'twitter':
					$actionArray = array(
						array(
							'title'	=>	$GLOBALS['TL_LANG']['MSC']['twitterReply'],
							'link'	=> 'https://twitter.com/intent/tweet?in_reply_to='.$arrItem['id']
						),
						array(
							'title'	=>	$GLOBALS['TL_LANG']['MSC']['twitterRetweet'],
							'link'	=> 'https://twitter.com/intent/retweet?tweet_id='.$arrItem['id']
						),
						array(
							'title'	=>	$GLOBALS['TL_LANG']['MSC']['twitterFavorite'],
							'link'	=> 'https://twitter.com/intent/favorite?tweet_id='.$arrItem['id']
						)	
					);
					$objTemplate->actions = $this->generateActions($actionArray);
					break;
				case 'instagram':
					$objTemplate->actions = NULL;
					break;
			}	
	
			return $objTemplate->parse();
		}else{
			return '';
		}
	}
	
	protected function generateActions($actionArray){
		$html = '';
		foreach($actionArray as $action){
			$html .= '<li><a href="'.$action['link'].'" onclick="'."window.open(this.href,'','width=640,height=380,modal=yes,left=100,top=50,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no');return false".'" target="_blank">'.$action['title'].'</a></li>';
		}
		return $html;
	}
}

