<?php
namespace SahusoftCom\ArticleBuilder;

class ArticleBuilderService
{
	# Article Builder DotNet Api Requires UserName & Password for Queries.
	public function __construct($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
		$this->format = 'json';
		$this->url = 'http://articlebuilder.net/api.php';
		$this->action = '';
	}

	# Curl Post Request with Api URL & Parameter(s) Data Array
	public function curlPost($url, $data, &$info) 
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->curlPostData($data));
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_REFERER, $url);
	    $html = trim(curl_exec($ch));
	    curl_close($ch);

	    return $html;
	}

	# Making Request Api Parameter
	public function curlPostData($dataArray) 
	{
	    $options = "";
	    foreach ( $dataArray as $key => $value ) {
	        $options .= "$key=" . urlencode($value) . "&";
	    }

	    return $options;
	}

	# Inject Article or Build Article
	public function article($dataArray)
	{
		if ( !isset($dataArray['action']) || empty($dataArray['action']) )
			return [
					'success' 	=> false,
					'error'		=> 'Action is required! (buildArticle/injectArticle)'
				];

		if ( !isset($dataArray['category']) || empty($dataArray['category']) )
			return [
					'success' => false,
					'error' => 'Category is required'
				];

		$authOutput = $this->authenticate();
		if ( $authOutput['success'] ) {

			# Action to Build Article
			$this->action = $dataArray['action'];

			# Success - Session
			$session = $authOutput['session'];

			# Build Input Parameter Array for BuildingArticle
			$inputArray = [];
			$inputArray['session'] = $session;
			$inputArray['action'] = $this->action;
			$inputArray['format'] = $this->format;
			$inputArray['category'] = $dataArray['category'];
			
			if ( isset($dataArray['supersun']) && !empty($dataArray['supersun']) )
				$inputArray['supersun'] = $dataArray['supersun'] > 1 ? $dataArray['supersun'] : 1;

			$inputArray['spin'] = !empty($dataArray['spin']) ? $dataArray['spin'] : 0;
			$inputArray['phrasesonly'] = !empty($dataArray['phrasesonly']) ? $dataArray['phrasesonly'] : 0;
			$inputArray['generatenow'] = !empty($dataArray['generatenow']) ? $dataArray['generatenow'] : 0;

			if ( $this->action == 'buildArticle' ) {

				if ( isset($dataArray['subtopics']) || !empty($dataArray['subtopics']) )
					$inputArray['subtopics'] = $dataArray['subtopics'];

				$inputArray['wordcount'] = !empty($dataArray['wordcount']) ? $dataArray['wordcount'] : 500;

				if ( isset($data['lsireplacement']) && !empty($dataArray['lsireplacement']) )
					$inputArray['lsireplacement'] = $dataArray['lsireplacement'] > 0 ? $dataArray['lsireplacement'] : 1;

				if ( isset($data['spintogether']) && !empty($dataArray['spintogether']) ) {
					$inputArray['spintogether'] = $dataArray['spintogether'] > 0 ? $dataArray['spintogether'] : 1;
					$inputArray['count'] = !empty($dataArray['count']) ? $dataArray['count'] : 1;
				}

				if ( isset($data['customkeys']) && !empty($dataArray['customkeys']) )
					$inputArray['customkeys'] = $dataArray['customkeys'];

				if ( isset($data['customkeyslist']) && !empty($dataArray['customkeyslist']) )
					$inputArray['customkeyslist'] = $dataArray['customkeyslist'];

				if ( isset($data['paracount']) && !empty($dataArray['paracount']) )
					$inputArray['paracount'] = $dataArray['paracount'] > 0 ? $dataArray['paracount'] : 0;

			} else {

				$inputArray['article'] = $dataArray['article'];

				if ( isset($data['keywords']) && !empty($dataArray['keywords']) )
					$inputArray['keywords'] = $dataArray['keywords'];

				if ( isset($data['volume']) && !empty($dataArray['volume']) )
					$inputArray['volume'] = $dataArray['volume'] > 1 ? $dataArray['volume'] : 1;

				if ( isset($data['style']) && !empty($dataArray['style']) )
					$inputArray['style'] = $dataArray['style'] > 1 ? $dataArray['style'] : 1;

				if ( isset($data['sidebarBackgroundColor']) && !empty($dataArray['sidebarBackgroundColor']) )
					$inputArray['sidebarBackgroundColor'] = $dataArray['sidebarBackgroundColor'];

				if ( isset($data['sidebarCaption']) && !empty($dataArray['sidebarCaption']) )
					$inputArray['sidebarCaption'] = $dataArray['sidebarCaption'];

				if ( isset($data['sidebarCaptionColor']) && !empty($dataArray['sidebarCaptionColor']) )
					$inputArray['sidebarCaptionColor'] = $dataArray['sidebarCaptionColor'];

				if ( isset($data['sidebarTipColor']) && !empty($dataArray['sidebarTipColor']) )
					$inputArray['sidebarTipColor'] = $dataArray['sidebarTipColor'];

				if ( isset($data['sidebarBackgroundColor']) && !empty($dataArray['sidebarBackgroundColor']) )
					$inputArray['sidebarBackgroundColor'] = $dataArray['sidebarBackgroundColor'];
			}

			# Request Api for Building Article
			$buildOutput = $this->curlPost($this->url, $inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);
			$buildOutput['session'] = $session;

			return $buildOutput;

		} else
			return $authOutput;
	}	

	# Athenticate User 
	public function authenticate()
	{	
		# Action to Login
		$this->action = 'authenticate';

		# Building Input Array..
		$inputArray = [];
		$inputArray['username'] = $this->username;
		$inputArray['password'] = $this->password;
		$inputArray['format'] = isset($this->format) ? $this->format : 'json';
		$inputArray['action'] = $this->action;

		$authOutput = $this->curlPost($this->url, $inputArray, $info);
		$authOutput = json_decode($authOutput, true);

		return $authOutput;
	}
}