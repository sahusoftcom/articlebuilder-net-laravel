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

	public function curlPostData($data) 
	{
	    $options = "";
	    foreach ( $data as $key => $value ) {
	        $options .= "$key=" . urlencode($value) . "&";
	    }

	    return $options;
	}

	public function buildArticle($category, $subtopics = null)
	{
		$authOutput = $this->authenticate($this->username, $this->password);
		if ( $authOutput['success'] ) {

			# Action to Build Article
			$this->action = 'buildArticle';

			# Success - Session
			$session = $authOutput['session'];

			# Build Input Array for BuildingArticle
			$input = [];
			$input['format'] = $this->format;
			$input['action'] = $this->action;
			$input['session'] = $session;
			$input['category'] = $category;
			$input['wordcount'] = 500;
			$input['lsireplacement'] = 1;
			$input['supersun'] = 1;
			if ( isset($subtopics) )
				$input['subtopics'] = $subtopics;

			# Request Api for Building Article
			$buildOutput = $this->curlPost($this->url, $input, $info);
			$buildOutput = json_decode($buildOutput, true);
			$buildOutput['session'] = $session;

			return $buildOutput;

		} else
			return $authOutput;

		die;


		return $article;
	}	

	public function authenticate()
	{	
		# Action to Login
		$this->action = 'authenticate';

		# Building Input Array..
		$input = [];
		$input['username'] = $this->username;
		$input['password'] = $this->password;
		$input['format'] = isset($this->format) ? $this->format : 'json';
		$input['action'] = $this->action;

		$authOutput = $this->curlPost($this->url, $input, $info);
		$authOutput = json_decode($authOutput, true);

		return $authOutput;
	}
}