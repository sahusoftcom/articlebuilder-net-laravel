<?php
namespace App\Services;

class XMLRPClientWordPress
{
	public $XMLRPCURL = "";
	public $UserName = "";
	public $PassWord = "";

	public function __construct($xmlrpcurl, $username, $password)
	{
		$this->XMLRPCURL = $xmlrpcurl.'/xmlrpc.php';
	    $this->UserName  = $username;
	    $this->PassWord = $password;
	}

	public function sendRequest($requestName, $params)
	{
		$request = xmlrpc_encode_request($requestName, $params);
	    
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	    curl_setopt($ch, CURLOPT_URL, $this->XMLRPCURL);
	    curl_setopt($ch ,CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POSTREDIR, 3);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
	    $results = curl_exec($ch);
	    curl_close($ch);

	    return $results;
	}

	public function createPost($title, $body, $category='', $keywords='', $encoding='UTF-8')
	{	
		if ( empty($category) )
			$category = [];
		
		$title = htmlentities($title, ENT_NOQUOTES, $encoding);
	    $keywords = htmlentities($keywords, ENT_NOQUOTES, $encoding);
		 
	    $content = [
			        'title' => $title,
			        'description' => $body,
			        'mt_allow_comments' => 0,
			        'mt_allow_pings' => 0,
			        'post_type' => 'post',
			        'mt_keywords' => $keywords,
			        'categories' => $category
			    ];
	    
	    $params = array(0, $this->UserName, $this->PassWord, $content, true);
		 
	    return $this->sendRequest('metaWeblog.newPost', $params);
	}

	public function getPost($blogId)
	{	
		$params = array($blogId, $this->UserName, $this->PassWord);
		
	    return $this->sendRequest('metaWeblog.getPost', $params);
	}

	public function createPage($title, $body, $encoding='UTF-8')
	{
	    $title = htmlentities($title, ENT_NOQUOTES, $encoding);
	 
	    $content = [
			        'title' => $title,
			        'description' => $body
		    	];

	    $params = array(0, $this->UserName, $this->PassWord, $content, true);
	 
	    return $this->sendRequest('wp.newPage', $params);
	}

	public function displayAuthors($blogId = 0)
	{
		$params = [
					$blogId, 
					$this->UserName, 
					$this->PassWord
				];
	    
		return $this->send_request('wp.getAuthors',$params);
	}
}