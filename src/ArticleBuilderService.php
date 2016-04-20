<?php
namespace SahusoftCom\ArticleBuilder;

class ArticleBuilderService
{
	public function __construct($username, $password)
	{
		
		$this->username 	= 	$username;
		$this->password 	= 	$password;
		$this->format 		= 	'json';
		$this->url 			= 	'http://articlebuilder.net/api.php';

	}

	public function curlPost($url, $data, &$info) 
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, self::curlPostData($data));
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_REFERER, $url);
	    $html = trim(curl_exec($ch));
	    curl_close($ch);

	    return $html;

	}

	public function curlPostData($data) 
	{
	    $fdata = "";
	    foreach ( $data as $key => $val ) {
	        $fdata .= "$key=" . urlencode($val) . "&";
	    }

	    return $fdata;

	}

	public function buildArticle($username, $password, $dataArray)
	{
		$postAuthenticate = self::authenticate($username, $password);
		$article = $postAuthenticate['output'];

		return $article;
	}	

	public function authenticate()
	{	
		# Building Input Array..
		$input = [];
		$input['username'] = $this->username;
		$input['password'] = $this->password;
		$input['format'] = isset($this->format) ? $this->format : 'json';
		$input['action'] = 'authenticate';

		$output = self::curlPost($this->url, $input, $info);
		$output = json_decode($output, true);

		return $output;
	}
}