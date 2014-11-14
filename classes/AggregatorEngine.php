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

class AggregatorEngine extends \Backend{
		
	public function getInstagramId($str, $obj)
	{
		global $GLOBALS;
		$str = ltrim($str, '@');
		if (isset($GLOBALS['TL_CONFIG']['aggregator_instagram_client_id']) && $GLOBALS['TL_CONFIG']['aggregator_instagram_client_id'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_instagram_client_secret']) && $GLOBALS['TL_CONFIG']['aggregator_instagram_client_secret'] != '')
		{
			$data = $this->fetchUrl('https://api.instagram.com/v1/users/search?q='.$str.'&count=1&client_id='.$GLOBALS['TL_CONFIG']['aggregator_instagram_client_id']);
			if (count($data['data']) == 1)
			{
				return $data['data'][0]['id'];
			} else {
				throw new \Exception($GLOBALS['TL_LANG']['ERR']['profileDoesNotExist']);
			}
		} else {
			$this->log($GLOBALS['TL_LANG']['ERR']['noInstagramCredentials'], 'AggregatorEngine getInstagramId()',TL_ERROR);
			throw new \Exception($GLOBALS['TL_LANG']['ERR']['noInstagramCredentials']);
		}
	}
	
	public function getInstagramName($str, $obj)
	{
		global $GLOBALS;
		if (isset($GLOBALS['TL_CONFIG']['aggregator_instagram_client_id']) && $GLOBALS['TL_CONFIG']['aggregator_instagram_client_id'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_instagram_client_secret']) && $GLOBALS['TL_CONFIG']['aggregator_instagram_client_secret'] != '')
		{
			$data = $this->fetchUrl('https://api.instagram.com/v1/users/'.trim($str).'?client_id='.$GLOBALS['TL_CONFIG']['aggregator_instagram_client_id']);
			return $data['data']['username'];
		} else {
			$this->log($GLOBALS['TL_LANG']['ERR']['noInstagramCredentials'], 'AggregatorEngine getInstagramName()',TL_ERROR);
		}
	}
	
	public function validateHashtag($str, $obj)
	{
		$str = ltrim($str, '@');
		if (preg_match('/^[a-zA-Z0-9_]+$/', $str))
		{
			return $str;
		} else {
			throw new \Exception($GLOBALS['TL_LANG']['ERR']['wrongHashtag']);
		}
	}
	
	public function getFacebookAlias($str, $obj)
	{
		global $GLOBALS;
		if (isset($GLOBALS['TL_CONFIG']['aggregator_facebook_app_id']) && $GLOBALS['TL_CONFIG']['aggregator_facebook_app_id'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_faceboook_app_secret']) && $GLOBALS['TL_CONFIG']['aggregator_faceboook_app_secret'] != '')
		{	
			$data = $this->fetchUrl('https://graph.facebook.com/v2.1/'.$str.'?access_token='.$GLOBALS['TL_CONFIG']['aggregator_facebook_app_id'].'|'.$GLOBALS['TL_CONFIG']['aggregator_faceboook_app_secret']);
			if ($data['error']['code'] == 803)
			{
				$this->log($GLOBALS['TL_LANG']['ERR']['aliasDoesNotExist'], 'AggregatorEngine getFacebookAlias()',TL_ERROR);
			} else if($data['error']['code'])
			{
				$this->log($GLOBALS['TL_LANG']['ERR']['wrongFacebookCredentials'], 'AggregatorEngine getFacebookAlias()',TL_ERROR);
			} else {
				return $data['username'];
			}
		} else {
			$this->log($GLOBALS['TL_LANG']['ERR']['noFacebookCredentials'], 'AggregatorEngine getFacebookAlias()',TL_ERROR);
		}
	}
	
	public function getFacebookId($str, $obj)
	{
		global $GLOBALS;
		$str = ltrim($str, '@');
		if (isset($GLOBALS['TL_CONFIG']['aggregator_facebook_app_id']) && $GLOBALS['TL_CONFIG']['aggregator_facebook_app_id'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_faceboook_app_secret']) && $GLOBALS['TL_CONFIG']['aggregator_faceboook_app_secret'] != '')
		{	
			$data = $this->fetchUrl('https://graph.facebook.com/v2.1/'.$str.'?access_token='.$GLOBALS['TL_CONFIG']['aggregator_facebook_app_id'].'|'.$GLOBALS['TL_CONFIG']['aggregator_faceboook_app_secret']);
			if ($data['error']['code'] == 803)
			{
				throw new \Exception($GLOBALS['TL_LANG']['ERR']['aliasDoesNotExist']);
			} else if($data['error']['code'] == 190)
			{
				throw new \Exception($GLOBALS['TL_LANG']['ERR']['wrongFacebookCredentials']);
			} else {
				return $data['id'];
			}
		} else {
			$this->log($GLOBALS['TL_LANG']['ERR']['noFacebookCredentials'], 'AggregatorEngine getFacebookId()',TL_ERROR);
			throw new \Exception($GLOBALS['TL_LANG']['ERR']['noFacebookCredentials']);
		}
	}
	
	public function checkTwitterAlias($str, $obj)
	{
		global $GLOBALS;
		$str = ltrim($str, '@');
		if (isset($GLOBALS['TL_CONFIG']['aggregator_twitter_api_key']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_api_key'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_twitter_api_secret']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_api_secret'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_twitter_access_token']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_twitter_access_token_secret']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token_secret'] != '')
		{
			$settings = array(
    			'oauth_access_token' => $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token'],
    			'oauth_access_token_secret' => $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token_secret'],
    			'consumer_key' => $GLOBALS['TL_CONFIG']['aggregator_twitter_api_key'],
    			'consumer_secret' => $GLOBALS['TL_CONFIG']['aggregator_twitter_api_secret']
			);
			$twitter = new TwitterAPIExchange($settings);
			$url = 'https://api.twitter.com/1.1/users/show.json';
			$getfield = '?screen_name='.$str;
			$requestMethod = 'GET';
			$response = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
			if (isset($response['errors']))
			{
				throw new \Exception($GLOBALS['TL_LANG']['ERR']['profileDoesNotExist']);
			} else {
				return $str;
			}
		} else {
			$this->log($GLOBALS['TL_LANG']['ERR']['noTwitterCredentials'], 'AggregatorEngine checkTwitterAlias()',TL_ERROR);
			throw new \Exception($GLOBALS['TL_LANG']['ERR']['noTwitterCredentials']);
		}
	}
	
	public function convertToSeconds($str, $obj)
	{
		
		return $str*60;
	}
	
	public function convertToMinutes($str, $obj)
	{
		return $str/60;
	}
	
	private function fetchUrl($url, $header=false)
	{
		$ch = curl_init();
		if ($header)
		{
			var_dump($header);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		return json_decode(curl_exec($ch), true);
	}
	
	public function getAllChannels()
	{
		$arrForms = array();
		$objForms = $this->Database->execute("SELECT * FROM tl_aggregator ORDER BY title");
		
		
		while ($objForms->next())
		{
			if ($objForms->type == 'facebookUser' || $objForms->type == 'facebookHashtag')
			{
				if ($objForms->type == 'facebookUser')
				{
					$arrForms[$objForms->id] = '[Facebook] '.$objForms->title;
				}
				else if ($objForms->type == 'facebookHashtag')
				{
					$arrForms[$objForms->id] = '[Facebook] '.$objForms->title;
				}
			}
			else if ($objForms->type == 'twitterUser' || $objForms->type == 'twitterHashtag')
			{
				if ($objForms->type == 'twitterUser')
				{
					$arrForms[$objForms->id] = '[Twitter] '.$objForms->title;
				}
				else if ($objForms->type == 'twitterHashtag')
				{
					$arrForms[$objForms->id] = '[Twitter] '.$objForms->title;
				}
			}
			else if ($objForms->type == 'instagramUser' || $objForms->type == 'instagramHashtag')
			{
				if ($objForms->type == 'instagramUser')
				{
					$arrForms[$objForms->id] = '[Instagram] '.$objForms->title;
				}
				else if ($objForms->type == 'instagramHashtag')
				{
					$arrForms[$objForms->id] = '[Instagram] '.$objForms->title;
				}
			}
			else if ($objForms->type == 'rssFeed')
			{
				$arrForms[$objForms->id] = '[RSS-Feed] '.$objForms->title;
			}
		}

		return $arrForms;
	}
	
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $this->import('BackendUser', 'User');
 
        if (strlen($this->Input->get('tid')))
        {
            $this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 0));
            $this->redirect($this->getReferer());
        }
 
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_aggregator::published', 'alexf'))
        {
            return '';
        }
 
        $href .= '&amp;id='.$this->Input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row[''];
 
        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }
 
        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
    }
	
	public function toggleVisibility($intId, $blnPublished)
	{
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_aggregator::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'AggregatorEngine toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
		
		$this->createInitialVersion('tl_aggregator', $intId);
		
    	if (is_array($GLOBALS['TL_DCA']['tl_aggregator']['fields']['published']['save_callback']))
    	{
        	foreach ($GLOBALS['TL_DCA']['tl_aggregator']['fields']['published']['save_callback'] as $callback)
        	{
            	$this->import($callback[0]);
            	$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
        	}
    	}
 
    	$this->Database->prepare("UPDATE tl_aggregator SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")->execute($intId);
    	$this->createNewVersion('tl_aggregator', $intId);
	}
	
	public function checkForUpdates()
	{
		global $GLOBALS;
		$globalBadwordList = explode(',', str_replace(' ', '', $GLOBALS['TL_CONFIG']['aggregator_blacklist']));
		$allActiveJobs = $this->Database->execute("SELECT * FROM tl_aggregator WHERE published = 1 ORDER BY lastUpdate;");
		
		$facebookApi = true;
		$twitterApi = true;
		$instagramApi = true;
		
		while ($allActiveJobs->next())
		{
			$plattform = str_replace(array('User', 'Hashtag'), '', $allActiveJobs->type);
			
			if(isset($GLOBALS['TL_CONFIG']['aggregator_'.$plattform.'_cache']))
			{
				$duration = $GLOBALS['TL_CONFIG']['aggregator_'.$plattform.'_cache'];
				
				if($allActiveJobs->cache != 0)
				{
					$duration = $allActiveJobs->cache;
				}
				
			} else {
				
				switch ($plattform)
				{
					case 'facebook':
						$duration = 300;
						break;
					case 'twitter':
						$duration = 600;
						break;
					case 'instagram':
						$duration = 300;
				}
				
			}
			
			if(time() >= $allActiveJobs->lastUpdate+$duration)
			{
				/* Create temporary Badword List */
				if($allActiveJobs->badwords != NULL || $allActiveJobs->badwords != '')
				{
					$localBadwordList = explode(',', str_replace(' ', '', $allActiveJobs->badwords));
					$currentBadwordList = array_unique(array_merge($globalBadwordList, $localBadwordList), SORT_REGULAR);
				}else{
					$currentBadwordList = $globalBadwordList;
				}
				
				switch($allActiveJobs->type)
				{
					case 'facebookUser':
					
						if($facebookApi)
						{
							
							if (isset($GLOBALS['TL_CONFIG']['aggregator_facebook_app_id']) && $GLOBALS['TL_CONFIG']['aggregator_facebook_app_id'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_faceboook_app_secret']) && $GLOBALS['TL_CONFIG']['aggregator_faceboook_app_secret'] != '')
							{
								$data = $this->fetchUrl('https://graph.facebook.com/v2.1/'.urlencode($allActiveJobs->facebookUser).'/posts/?access_token='.$GLOBALS['TL_CONFIG']['aggregator_facebook_app_id'].'|'.$GLOBALS['TL_CONFIG']['aggregator_faceboook_app_secret']);
								
								if ($data['error']['code'] == 4)
								{
									$facebookApi = false;
									$this->log($GLOBALS['TL_LANG']['ERR']['maximumRate'], 'AggregatorEngine checkForUpdates()',TL_ERROR);
								} else {
									if(count($data['data']) > 0)
									{
										$this->parseDataToCache($data['data'], $allActiveJobs->id, $currentBadwordList, 'facebook');
									}
								}
								
							} else {
								$facebookApi = false;
								$this->log($GLOBALS['TL_LANG']['ERR']['noFacebookCredentials'], 'AggregatorEngine checkForUpdates()',TL_ERROR);
							}
							
						}
						
						break;
						
					case 'twitterUser':
							if (isset($GLOBALS['TL_CONFIG']['aggregator_twitter_api_key']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_api_key'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_twitter_api_secret']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_api_secret'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_twitter_access_token']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_twitter_access_token_secret']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token_secret'] != '')
							{
								$settings = array(
    								'oauth_access_token' => $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token'],
    								'oauth_access_token_secret' => $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token_secret'],
    								'consumer_key' => $GLOBALS['TL_CONFIG']['aggregator_twitter_api_key'],
    								'consumer_secret' => $GLOBALS['TL_CONFIG']['aggregator_twitter_api_secret']
								);
								$twitter = new TwitterAPIExchange($settings);
								$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
								$getfield = '?screen_name='.urlencode($allActiveJobs->twitterUser).'&count=10';
								$requestMethod = 'GET';
								$response = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
								if (isset($response['errors']))
								{
									$this->log($GLOBALS['TL_LANG']['ERR']['noTwitterCredentials'], 'AggregatorEngine checkForUpdates()',TL_ERROR);
								} else {
									$this->parseDataToCache($response, $allActiveJobs->id, $currentBadwordList, 'twitter');
							}
						} else {
							$this->log($GLOBALS['TL_LANG']['ERR']['noTwitterCredentials'], 'AggregatorEngine checkForUpdates()',TL_ERROR);
						}
						break;
						
					case 'twitterHashtag':
						if (isset($GLOBALS['TL_CONFIG']['aggregator_twitter_api_key']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_api_key'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_twitter_api_secret']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_api_secret'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_twitter_access_token']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_twitter_access_token_secret']) && $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token_secret'] != '')
							{
								$settings = array(
    								'oauth_access_token' => $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token'],
    								'oauth_access_token_secret' => $GLOBALS['TL_CONFIG']['aggregator_twitter_access_token_secret'],
    								'consumer_key' => $GLOBALS['TL_CONFIG']['aggregator_twitter_api_key'],
    								'consumer_secret' => $GLOBALS['TL_CONFIG']['aggregator_twitter_api_secret']
								);
								$twitter = new TwitterAPIExchange($settings);
								$url = 'https://api.twitter.com/1.1/search/tweets.json';
								$getfield = '?q=%23'.urlencode($allActiveJobs->twitterHashtag).'%20-RT&count=10';
								$requestMethod = 'GET';
								$response = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
								if (isset($response['errors']))
								{
									$this->log($GLOBALS['TL_LANG']['ERR']['noTwitterCredentials'], 'AggregatorEngine checkForUpdates()',TL_ERROR);
								} else {
									$this->parseDataToCache($response['statuses'], $allActiveJobs->id, $currentBadwordList, 'twitter');
							}
						} else {
							$this->log($GLOBALS['TL_LANG']['ERR']['noTwitterCredentials'], 'AggregatorEngine checkForUpdates()',TL_ERROR);
						}
						break;
						
					case 'instagramUser':
						if (isset($GLOBALS['TL_CONFIG']['aggregator_instagram_client_id']) && $GLOBALS['TL_CONFIG']['aggregator_instagram_client_id'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_instagram_client_secret']) && $GLOBALS['TL_CONFIG']['aggregator_instagram_client_secret'] != '')
						{
							$data = $this->fetchUrl('https://api.instagram.com/v1/users/'.urlencode($allActiveJobs->instagramUser).'/media/recent?client_id='.$GLOBALS['TL_CONFIG']['aggregator_instagram_client_id'].'&count=10');
							var_dump($data);
							$this->parseDataToCache($data['data'], $allActiveJobs->id, $currentBadwordList, 'instagram');
						} else {
							$this->log($GLOBALS['TL_LANG']['ERR']['noInstagramCredentials'], 'AggregatorEngine checkForUpdates()',TL_ERROR);
						}
						break;
						
					case 'instagramHashtag':
						if (isset($GLOBALS['TL_CONFIG']['aggregator_instagram_client_id']) && $GLOBALS['TL_CONFIG']['aggregator_instagram_client_id'] != '' && isset($GLOBALS['TL_CONFIG']['aggregator_instagram_client_secret']) && $GLOBALS['TL_CONFIG']['aggregator_instagram_client_secret'] != '')
						{
							$data = $this->fetchUrl('https://api.instagram.com/v1/tags/'.urlencode($allActiveJobs->instagramHashtag).'/media/recent?client_id='.$GLOBALS['TL_CONFIG']['aggregator_instagram_client_id'].'&count=10');
							$this->parseDataToCache($data['data'], $allActiveJobs->id, $currentBadwordList, 'instagram');
						} else {
							$this->log($GLOBALS['TL_LANG']['ERR']['noInstagramCredentials'], 'tl_aggregator checkForUpdates()',TL_ERROR);
						}
						break;
				}
				$this->Database->prepare("UPDATE tl_aggregator SET lastUpdate=". time() ." WHERE id=?")->execute($allActiveJobs->id);
    			$this->createNewVersion('tl_aggregator', $allActiveJobs->id);
			}
			
		}
		
	}
	
	private function parseDataToCache($data, $fileId, $badwords, $type)
	{
		$cacheLibrary = array();
		switch($type)
		{
			case 'facebook':
				$count = 0;
				foreach($data as $item)
				{
					if(strpos($item['story'], 'likes') === false && strpos($item['story'], 'updated') === false && strpos($item['story'], 'shared') === false && strpos($item['story'], 'their own') === false && strpos($item['story'], 'commented') === false)
					{
						if($item['type'] == 'status' || $item['type'] == 'photo' || $item['type'] == 'link')
						{
							if($this->checkForBadwords($badwords, $item['message']))
							{
								$cacheLibrary[$count]['author']['name'] = $item['from']['name'];
								$cacheLibrary[$count]['author']['picture'] = 'https://graph.facebook.com/v2.1/'.$item['from']['id'].'/picture?type=square';
								$cacheLibrary[$count]['author']['url'] = 'https://www.facebook.com/'.$item['from']['id'];
								if(strlen($item['message']) > 160){
									$cacheLibrary[$count]['item']['message'] = substr($item['message'], 0, 160).'...';
								}else{
									$cacheLibrary[$count]['item']['message'] = $item['message'];
								}
								$cacheLibrary[$count]['item']['picture'] = str_replace('/s130x130', '', str_replace('_s', '_n', $item['picture']));
								if(strpos($cacheLibrary[$count]['item']['picture'], 'safe_image.php') !== false) {
									$parts = parse_url($cacheLibrary[$count]['item']['picture']);
									parse_str($parts['query'], $query);
									$cacheLibrary[$count]['item']['picture'] = $query['url'];
								}else{
									$ch = curl_init('https://graph.facebook.com/v2.1/'.$item['object_id'].'/picture?type=normal&access_token='.$GLOBALS['TL_CONFIG']['aggregator_facebook_app_id'].'|'.$GLOBALS['TL_CONFIG']['aggregator_faceboook_app_secret']);
									curl_exec($ch);
									$response = curl_getinfo($ch);
									$cacheLibrary[$count]['item']['picture'] = $response['redirect_url'];
									curl_close($ch);
								}
								$id = explode('_', $item['id']);
								$cacheLibrary[$count]['item']['url'] = 'https://www.facebook.com/'.$id[0].'/posts/'.$id[1];
								$cacheLibrary[$count]['item']['type'] = $item['type'];
								$cacheLibrary[$count]['timestamp'] = strtotime($item['created_time']);
								$cacheLibrary[$count]['plattform'] = 'facebook';
								$count++;
							}
						}
					}
					
				}
				$fp = fopen(TL_ROOT.'/system/modules/aggregator/cache/'.$fileId.'.json.cache', 'w');
				fwrite($fp, json_encode($cacheLibrary));
				fclose($fp);
				
			break;
			
			case 'twitter':
			$count = 0;
				foreach($data as $item)
				{
					if($this->checkForBadwords($badwords, $item['text']))
					{
						$cacheLibrary[$count]['author']['name'] = '@'.$item['user']['screen_name'];
						$cacheLibrary[$count]['author']['picture'] = $item['user']['profile_image_url'];
						$cacheLibrary[$count]['author']['url'] = 'https://www.twitter.com/'.$item['user']['screen_name'];
						$cacheLibrary[$count]['item']['message'] = $item['text'];
						$cacheLibrary[$count]['item']['url'] = 'https://www.twitter.com/'.$item['user']['screen_name'].'/status/'.$item['id_str'];
						$cacheLibrary[$count]['timestamp'] = strtotime($item['created_at']);
						$cacheLibrary[$count]['plattform'] = 'twitter';
						$cacheLibrary[$count]['id'] = $item['id_str'];
						$count++;
					}
				}
				$fp = fopen(TL_ROOT.'/system/modules/aggregator/cache/'.$fileId.'.json.cache', 'w');
				fwrite($fp, json_encode($cacheLibrary));
				fclose($fp);
				
			break;
			
			case 'instagram':
				$count = 0;
				foreach($data as $item)
				{
					if($this->checkForBadwords($badwords, $item['caption']['text']))
					{
						$cacheLibrary[$count]['author']['name'] = '@'.$item['caption']['from']['username'];
						$cacheLibrary[$count]['author']['picture'] = $item['caption']['from']['profile_picture'];
						$cacheLibrary[$count]['author']['url'] = 'http://www.instagram.com/'.$item['caption']['from']['username'];
						if($item['caption']['from']['username'] == ''){
							$cacheLibrary[$count]['author']['name'] = '@'.$item['user']['username'];
							$cacheLibrary[$count]['author']['picture'] = $item['user']['profile_picture'];
							$cacheLibrary[$count]['author']['url'] = 'http://www.instagram.com/'.$item['user']['username'];
						}
						if(strlen($item['caption']['text']) > 160){
							$cacheLibrary[$count]['item']['message'] = substr($item['caption']['text'], 0, 160).'...';
						}else{
							$cacheLibrary[$count]['item']['message'] = $item['caption']['text'];
						}
						$cacheLibrary[$count]['item']['url'] = $item['link'];
						$cacheLibrary[$count]['item']['picture'] = $item['images']['standard_resolution']['url'];
						$cacheLibrary[$count]['timestamp'] = $item['created_time'];
						$cacheLibrary[$count]['plattform'] = 'instagram';
						$count++;
					}
				}
				$fp = fopen(TL_ROOT.'/system/modules/aggregator/cache/'.$fileId.'.json.cache', 'w');
				fwrite($fp, json_encode($cacheLibrary));
				fclose($fp);
			break;
		}
		
	}
	
	private function checkForBadwords($badwords, $string)
	{
			if($bawords[0] != ''){
				foreach($badwords as $badword)
				{
					if(strpos($string, $badword) !== false)
					{
						return false;
					} else if(strpos($string, strtoupper($badword)) !== false)
					{
						return false;
					} else if(strpos($string, strtolower($badword)) !== false)
					{
						return false;
					} else if(strpos($string, ucfirst($badword)) !== false)
					{
						return false;
					}
				}
			}
			return true;
	}
}
