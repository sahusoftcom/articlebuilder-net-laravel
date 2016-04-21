Articlebuilder.Net Laravel Version: 1.0
==========================

Service Provider of ArticleBuilder.net API for Laravel PHP Framework

## Installation

Type the following command in your project directory

`composer require sahusoftcom/articlebuilder-net-laravel`

OR

Add the following line to the `require` section of `composer.json`:

```json
{
    "require": {
        "sahusoftcom/articlebuilder-net-laravel": "dev-master"
    }
}
```

## Setup

In `/config/app.php`, add the following to `providers`:
  
```
SahusoftCom\ArticleBuilder\ArticleBuilderProvider::class
```

## How to use

1. You should use the `ArtileBuilderService` to access its function
2. Pass `username` & `passsword` parameter in ArticleBuilderService

For example:

```
<?php
namespace App;
 
use SahusoftCom\ArticleBuilderService;

class NewService
{
	public function start()
	{
		$object = new ArticleBuilderService($username, $password);
		$object->authenticate();
		.
		.
		.
		.
```

## Functions

1.	`authenticate`

	`function`: authenticate()
	
	`require`:	function does not require any parameter.
	
	Returns: 

		[Variable]   	[Value]
			success		true
			session		(a unique session id)

	Session ID is not required to be saved or to be passed in every call.

2.	`buildArticle` :

	Build Article function authenticates user and fetches desired article.

	`function`: article($dataArray)

	`require`:	dataArray having folllowing keys and values

	  [Key] 	   		    [Value]        											   
	  action    	    buildArticle										   	       
	  subtopics    	    OPTIONAL (the subtopics to include in)					   
	  category	 	    (the category to build an article in) 					   
	  wordcount 	    (the target wordcount (min = 300, max = 1000) 			   
	  lsireplacement    (set to 1 if you want automatic LSI replacement
	  					performed currency on the article) 								   
	  superspun         (1 to use Super Spun content, 2 to use Expanded Super
	  					Spun Content, 0 to use unspun content)						   
	  spintogether      OPTIONAL (if the value is 1, the number of articles 
	  					specified in 'count' will be generated & spun together 
	  					in  one big document.)										   
	  count 		    OPTIONAL (the number of documents to create and spin 
	  					together)						  						   
	  customkeys	    OPTIONAL (use custom keyword replacement) 				   
	  customkeyslist    OPTIONAL (list of custom keyword replacements, 
	  					separated by a line break)											   
	  spin 			    OPTIONAL (0 = No[default], 1 = Yes -- spin content 
	  					using) the BestSpinner API 										   
	  phrasesonly 	    OPTIONAL (0 = No[default], 1 = Yes -- only spin
	  					phrases in the article with TBS the BestSpinner API 										   
	  generatenow	    OPTIONAL (0 = No [default], 1= Yes -- return a 
	  					randomly spun version of content) 							       
	  paracount  	    OPTIONAL (0 = Don't try to keep paragraph counts the 
	  					same [default], 1 = Make sure all articles have the same number of pargraphs										   
IMPORTANT NOTE: If you choose to spin multiple articles together, it costs one quota point for each article generated. That is, if you set 'spintogether' as 1 and set a 'count' of 5, it will cost you 5 daily quota points.

	Returns:
		[Variable]   	[Value]
			output 		(article)
			success		true
			session		(a unique session id)
	







