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
	public function curlPost($data, &$info) 
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $this->url);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->curlPostData($data));
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_REFERER, $this->url);
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
		if ( $dataArray['action'] != 'superSpun' || $dataArray['action'] != 'buildArticle' || $dataArray['action'] != 'injectContent'  || $dataArray['action'] != 'getTip' ) {
			return ['success' => false, 'error' => 'Action Not Found!'];
		}

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
			
			if( $this->action == 'superSpun' ) {
				
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
			$buildOutput = $this->curlPost($inputArray, $info);
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

		$authOutput = $this->curlPost($inputArray, $info);
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
			$buildOutput = $this->curlPost($inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);

			# Build Output
			$output = [];
			$output['apiQueries'] = $buildOutput['output'];
			$output['session'] = $authOutput['session'];
			$output['success'] = true;

			#build Input Array
			$inputArray['action'] = 'apiMaxQueries';

			# Max Count of Api Queries
			$buildOutput = $this->curlPost($inputArray, $info);
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
			$buildOutput = $this->curlPost($inputArray, $info);
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
			$buildOutput = $this->curlPost($inputArray, $info);
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

			# Building Output
			$buildOutput = $this->curlPost($inputArray, $info);
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
		} elseif ( $dataArray['action'] == 'blogDelete' ) {
			//
		} else {
			return ['success' => false, 'error' => 'Action Not Found!'];
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

			# Building Output
			$buildOutput = $this->curlPost($inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);	
			$buildOutput['session'] = $inputArray['session'];

			return $buildOutput;
		}	
	}

	# Action : createBlogPostJob, deleteBlogPostJob & doAutoPostJob
	public function blogPostJob($dataArray)
	{
		if ( $dataArray['action'] == 'createBlogPostJob' ) {

			if ( empty($dataArray['category']) && !isset($dataArray['category']) )
				return ['success' => false, 'error' => 'Category is required for Create Blog Post Job!'];
			if ( empty($dataArray['blogcategory']) && !isset($dataArray['blogcategory']) )
				return ['success' => false, 'error' => 'Blog Category is required for Create Blog Post Job!'];

		} elseif ( $dataArray['action'] == 'deleteBlogPostJob' ) {

			if ( empty($dataArray['id']) && !isset($dataArray['id']) )
				return ['success' => false, 'error' => 'Blog Post Job ID is required for Delete!'];

		} elseif ( $dataArray['action'] == 'doAutoPost' ) { 

			if ( empty($dataArray['id']) && !isset($dataArray['id']) )
				return ['success' => false, 'error' => 'Blog Post Job ID is required for running Do Auto Post!'];

		} else {
			return ['success' => false, 'error' => 'Action Not Found!'];
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

			if ( $this->action == 'createBlogPostJob' ) {
				
				$inputArray['category'] = $dataArray['category'];
				$inputArray['blog'] = $dataArray['blog'];
				$inputArray['blogcategory'] = $dataArray['blogcategory'];
				$inputArray['wordcountmin'] = $dataArray['wordcountmin'] < 300 ? 300 : $dataArray['wordcountmin'];
				$inputArray['wordcountmax'] = $dataArray['wordcountmax'] > 1000 ? 1000 : $dataArray['wordcountmax'];
				$inputArray['blogcategory'] = $dataArray['blogcategory'];
				$inputArray['frequency'] = $dataArray['frequency'] < 28800 ? 28800 : $dataArray['frequency'];
				
				if ( !empty($dataArray['genericresource']) && isset($dataArray['genericresource']) ) {

					$inputArray['genericresource'] = $dataArray['genericresource'] ? $dataArray['genericresource'] : 0;
					if ( !empty($dataArray['genericlinks']) && isset($dataArray['genericlinks']) && $input['genericresource'] == 1 ) {
						$inputArray['genericlinks'] = $dataArray['genericlinks'] ? $dataArray['genericlinks'] : 0;
					}
				}

				if ( !empty($dataArray['lsireplacement']) && isset($dataArray['lsireplacement']) )
					$inputArray['lsireplacement'] = $dataArray['lsireplacement'] > 0 ? 1 : 0;

				if ( !empty($dataArray['addheadings']) && isset($dataArray['addheadings']) )
					$inputArray['addheadings'] = $dataArray['addheadings'] > 0 ? 1 : 0;

				if ( !empty($dataArray['addimages']) && isset($dataArray['addimages']) )
					$inputArray['addimages'] = $dataArray['addimages'] > 0 ? 1 : 0;

				if ( !empty($dataArray['addyoutube']) && isset($dataArray['addyoutube']) )
					$inputArray['addyoutube'] = $dataArray['addyoutube'] > 0 ? 1 : 0;

				if ( !empty($dataArray['addinjection']) && isset($dataArray['addinjection']) )
					$inputArray['addinjection'] = $dataArray['addinjection'] > 0 ? 1 : 0;

				if ( !empty($dataArray['addclickbank']) && isset($dataArray['addclickbank']) ) {
					$inputArray['addclickbank'] = $dataArray['addclickbank'] > 0 ? 1 : 0;
					if ( !empty($dataArray['cbusername']) && isset($dataArray['cbusername']) && $inputArray['addclickbank'] == 1 )
						$inputArray['cbusername'] = $dataArray['cbusername'];
				}

				if ( !empty($dataArray['addinjection']) && isset($dataArray['addinjection']) )
					$inputArray['addinjection'] = $dataArray['addinjection'] > 0 ? 1 : 0;

				if ( !empty($dataArray['customkeys']) && isset($dataArray['customkeys']) )
					$inputArray['customkeys'] = $dataArray['customkeys'];

				if ( !empty($dataArray['customkeyslist']) && isset($dataArray['customkeyslist']) )
					$inputArray['customkeyslist'] = $dataArray['customkeyslist'];

				if ( !empty($dataArray['injectstyle']) && isset($dataArray['injectstyle']) )
					$inputArray['injectstyle'] = $dataArray['injectstyle'];

				if ( !empty($dataArray['injectsidebar']) && isset($dataArray['injectsidebar']) )
					$inputArray['injectsidebar'] = $dataArray['injectsidebar'];

				if ( !empty($dataArray['injectqty']) && isset($dataArray['injectqty']) )
					$inputArray['injectqty'] = $dataArray['injectqty'];

				if ( !empty($dataArray['resource']) && isset($dataArray['resource']) )
					$inputArray['resource'] = $dataArray['resource'];

				if ( !empty($dataArray['comments']) && isset($dataArray['comments']) )
					$inputArray['comments'] = $dataArray['comments'] > 0 ? 1 : 0;

				if ( !empty($dataArray['pingbacks']) && isset($dataArray['pingbacks']) )
					$inputArray['pingbacks'] = $dataArray['pingbacks'] > 0 ? 1 : 0;

				if ( !empty($dataArray['draft']) && isset($dataArray['draft']) )
					$inputArray['draft'] = $dataArray['draft'] > 0 ? 1 : 0;
			
			} elseif ( $this->action == 'deleteBlogPostJob' || $this->action = 'doAutoPost' ) {
				$inputArray['id'] = $dataArray['id'];
			}

			# Building Output
			$buildOutput = $this->curlPost($inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);	
			$buildOutput['session'] = $inputArray['session'];

			return $buildOutput;
		}	
	}

	# Action : createUniquePostJob, deleteUniquePostJob & doUniqueAutoPost
	public function unqiuePostJob($dataArray)
	{
		if ( $dataArray['action'] == 'createUniquePostJob' ) {

			if ( empty($dataArray['apikey']) && !isset($dataArray['apikey']) )
				return ['success' => false, 'error' => 'API Key (iNeedArticles.com) is required for Create Unique Post Job!'];

			if ( empty($dataArray['blog']) && !isset($dataArray['blog']) )
				return ['success' => false, 'error' => 'Blog Description is required for Create Unique Post Job!'];

			if ( empty($dataArray['keyword']) && !isset($dataArray['keyword']) )
				return ['success' => false, 'error' => 'Keyword is required for Create Unique Post Job!'];

		} elseif ( $dataArray['action'] == 'deleteUniquePostJob' ) {

			if ( empty($dataArray['id']) && !isset($dataArray['id']) )
				return ['success' => false, 'error' => 'Unique Post Job ID is required for Delete!'];

		} elseif ( $dataArray['action'] == 'doUniqueAutoPost' ) { 

			if ( empty($dataArray['id']) && !isset($dataArray['id']) )
				return ['success' => false, 'error' => 'Unique Post Job ID is required for running Do Unique Auto Post!'];
			
		} else {
			return ['success' => false, 'error' => 'Action Not Found!'];
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

			if ( $this->action == 'createUniquePostJob' ) {
				
				$inputArray['blog'] = $dataArray['blog'];
				$inputArray['apikey'] = $dataArray['apikey'];
				$inputArray['keywords'] = $dataArray['keywords'];

				if ( isset($inputArray['exactkeys']) && !empty($inputArray['exactkeys']) )
					$inputArray['exactkeys'] = $dataArray['exactkeys'] > 0 ? 1 : 0;

				if ( isset($inputArray['bestwriters']) && !empty($inputArray['bestwriters']) )
					$inputArray['bestwriters'] = $dataArray['bestwriters'] > 0 ? 1 : 0;

				if ( isset($inputArray['extrasearch']) && !empty($inputArray['extrasearch']) )
					$inputArray['extrasearch'] = $dataArray['extrasearch'] > 0 ? 1 : 0;

				$inputArray['blogcategory'] = $dataArray['blogcategory'];
				$inputArray['wordcountmin'] = $dataArray['wordcountmin'] < 300 ? 300 : $dataArray['wordcountmin'];
				$inputArray['wordcountmax'] = $dataArray['wordcountmax'] > 1000 ? 1000 : $dataArray['wordcountmax'];
				$inputArray['blogcategory'] = $dataArray['blogcategory'];
				$inputArray['frequency'] = $dataArray['frequency'] < 28800 ? 28800 : $dataArray['frequency'];
				
				if ( !empty($dataArray['genericresource']) && isset($dataArray['genericresource']) ) {

					$inputArray['genericresource'] = $dataArray['genericresource'] ? $dataArray['genericresource'] : 0;
					if ( !empty($dataArray['genericlinks']) && isset($dataArray['genericlinks']) && $input['genericresource'] == 1 ) {
						$inputArray['genericlinks'] = $dataArray['genericlinks'] ? $dataArray['genericlinks'] : 0;
					}
				}

				if ( !empty($dataArray['addimages']) && isset($dataArray['addimages']) )
					$inputArray['addimages'] = $dataArray['addimages'] > 0 ? 1 : 0;

				if ( !empty($dataArray['addyoutube']) && isset($dataArray['addyoutube']) )
					$inputArray['addyoutube'] = $dataArray['addyoutube'] > 0 ? 1 : 0;

				if ( !empty($dataArray['resource']) && isset($dataArray['resource']) )
					$inputArray['resource'] = $dataArray['resource'];

				if ( !empty($dataArray['comments']) && isset($dataArray['comments']) )
					$inputArray['comments'] = $dataArray['comments'] > 0 ? 1 : 0;

				if ( !empty($dataArray['pingbacks']) && isset($dataArray['pingbacks']) )
					$inputArray['pingbacks'] = $dataArray['pingbacks'] > 0 ? 1 : 0;

				if ( !empty($dataArray['draft']) && isset($dataArray['draft']) )
					$inputArray['draft'] = $dataArray['draft'] > 0 ? 1 : 0;
			
			} elseif ( $this->action == 'deleteUniquePostJob' || $this->action = 'doUniqueAutoPost' ) {
				$inputArray['id'] = $dataArray['id'];
			}

			# Building Output
			$buildOutput = $this->curlPost($inputArray, $info);
			$buildOutput = json_decode($buildOutput, true);	
			$buildOutput['session'] = $inputArray['session'];

			return $buildOutput;
		}	
	}

}