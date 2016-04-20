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

	# Action : Inject Article or Build Article
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

		# Authenticate & get Session ID
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
			
			if( $this->action == 'superSun' ) {
				
				//

			} else {

				if ( isset($dataArray['superspun']) && !empty($dataArray['superspun']) )
					$inputArray['superspun'] = $dataArray['superspun'] > 1 ? $dataArray['superspun'] : 1;

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

				} elseif ( $this->action == 'injectContent' ) {

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
				
				}  elseif ( $this->action == 'getTip' ) {

					if ( isset($data['keywords']) && !empty($dataArray['keywords']) )
						$inputArray['keywords'] = $dataArray['keywords'];
				}
			}

			# Request Api for Building Article
			$buildOutput = $this->curlPost($this->url, $inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);
			$buildOutput['session'] = $session;

			return $buildOutput;

		} else
			return $authOutput;
	}	

	# Action : Athenticate User 
	public function authenticate()
	{	
		# Action to Login
		$this->action = 'authenticate';

		# Building Input Array.
		$inputArray = [];
		$inputArray['username'] = $this->username;
		$inputArray['password'] = $this->password;
		$inputArray['format'] = isset($this->format) ? $this->format : 'json';
		$inputArray['action'] = $this->action;

		$authOutput = $this->curlPost($this->url, $inputArray, $info);
		$authOutput = json_decode($authOutput, true);

		return $authOutput;
	}

	# Action : apiQueries & apiMaxQueries
	public function apiQueries()
	{
		# Authenticate & get Session ID
		$authOutput = $this->authenticate();
		if ( $authOutput['success'] ) {

			# Building Input Array.
			$inputArray = [];
			$inputArray['action'] = 'apiQueries';
			$inputArray['session'] = $authOutput['session'];
			$inputArray['format'] = $this->format;

			# Use Count of Api Queries
			$buildOutput = $this->curlPost($this->url, $inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);

			# Build Output
			$output = [];
			$output['apiQueries'] = $buildOutput['output'];
			$output['session'] = $authOutput['session'];
			$output['success'] = true;

			#build Input Array
			$inputArray['action'] = 'apiMaxQueries';

			# Max Count of Api Queries
			$buildOutput = $this->curlPost($this->url, $inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);

			$output['apiMaxQueries'] = $buildOutput['output'];

			return $output;
		}
	}

	# Action : apiTipQueries & apiMaxTipQueries
	public function apiTipQueries()
	{
		# Authenticate & get Session ID
		$authOutput = $this->authenticate();
		if ( $authOutput['success'] ) {

			# Building Input Array.
			$inputArray = [];
			$this->action = 'apiTipQueries';
			$inputArray['action'] = $this->action;
			$inputArray['session'] = $authOutput['session'];
			$inputArray['format'] = $this->format;

			# Use Count of Api Tip Queries
			$buildOutput = $this->curlPost($this->url, $inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);

			# Build Output
			$output = [];
			$output['apiTipQueries'] = $buildOutput['output'];
			$output['session'] = $authOutput['session'];
			$output['success'] = true;

			#build Input Array
			$this->action = 'apiMaxTipQueries';
			$inputArray['action'] = $this->action;

			# Max Count of Api Tip Queries
			$buildOutput = $this->curlPost($this->url, $inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);

			$output['apiMaxTipQueries'] = $buildOutput['output'];

			return $output;
		}
	}

	# Action : categories
	public function categories()
	{
		# Authenticate & get Session ID
		$authOutput = $this->authenticate();
		if ( $authOutput['success'] ) {

			# Building Input Array.
			$inputArray = [];
			$this->action = 'categories';
			$inputArray['action'] = $this->action;
			$inputArray['session'] = $authOutput['session'];
			$inputArray['format'] = $this->format;

			# Use Count of Api Tip Queries
			$buildOutput = $this->curlPost($this->url, $inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);	
			$buildOutput['session'] = $inputArray['session'];

			return $buildOutput;
		}
	}

	# Action : blogAdd & blogDelete
	public function addDeleteBlog($dataArray)
	{
		# Check cases for blogAdd Action
		if( $dataArray['action'] == 'blogAdd' ) {
			if ( empty($dataArray['url']) || !isset($dataArray['url']) ) {
				return ['success' => false, 'error' => 'URL is required to Add Blog'];
			} elseif ( empty($dataArray['username']) || !isset($dataArray['username']) ) {
				return ['success' => false, 'error' => 'Username is required to Add Blog'];
			} elseif ( empty($dataArray['password']) || !isset($dataArray['password']) ) {
				return ['success' => false, 'error' => 'Password is required to Add Blog'];
			}
		}

		# Authenticate & get Session ID
		$authOutput = $this->authenticate();
		if ( $authOutput['success'] ) {

			# Building Input Array.
			$inputArray = [];
			$this->action = $dataArray['action'];
			$inputArray['action'] = $this->action;
			$inputArray['session'] = $authOutput['session'];
			$inputArray['format'] = $this->format;
			$inputArray['description'] = $dataArray['description'];

			if ( $this->action == 'blogAdd' ) {
				$inputArray['url'] = $dataArray['url'];
				$inputArray['username'] = $dataArray['username'];
				$inputArray['password'] = $dataArray['password'];
			}

			# Use Count of Api Tip Queries
			$buildOutput = $this->curlPost($this->url, $inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);	
			$buildOutput['session'] = $inputArray['session'];

			return $buildOutput;
		}	
	}

}