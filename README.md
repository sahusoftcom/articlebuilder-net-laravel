Articlebuilder.Net Laravel Version: 1.0
==========================

Service Provider of ArticleBuilder.net API & XMLRPClient Wordpress for Laravel PHP Framework [ [Packagist] ]

[Packagist]: <https://packagist.org/packages/sahusoftcom/articlebuilder-net-laravel>

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
  
```php
SahusoftCom\ArticleBuilder\ArticleBuilderProvider::class
```

## How to use

1. You should use the `ArtileBuilderService` to access its function
2. Pass `username` & `passsword` parameter in ArticleBuilderService

For example:

```php
<?php
namespace App;
 
use SahusoftCom\ArticleBuilderService;
use SahusoftCom\XMLRPClientWordpress;

class NewService
{	
	// Example to use ArticleBuilder Service
	public function firstMethod()
	{
		$object = new ArticleBuilderService($username, $password);
		$object->authenticate();
	}
	
	// Example to use XMLRPClientWordpress Service
	public function secondMethod()
	{
		$object = new XMLRPClientWordpress($xmlrpccurl, $username, $password);
		$blogId = $object->createPost($title, $body, $category, $keywords, $encoding);
	}

```

## Article Builder Service Functions

1.	`authenticate`

	`function`: authenticate()
	
	`require`:	*function does not require any parameter.*
	
	Returns: 

		[Variable]   	[Value]
			success		true
			session		(a unique session id)

	*Session ID is not required to be saved or to be passed in every call.*

2.	`buildArticle` :

	*Build Article function authenticates user and fetches desired article.*

	`function`: article($dataArray)

	`require`:	*dataArray having folllowing keys and values*
	```
	[Key]			[Value]
	action			buildArticle
	subtopics		OPTIONAL (the subtopics to include in)
	category		(the category to build an article in)
	wordcount		(the target wordcount (min = 300, max = 1000)
	lsireplacement	(set to 1 if you want automatic LSI replacement performed currency on the article)
	superspun		(1 to use Super Spun content, 2 to use Expanded Super Spun Content, 0 to use unspun content)
	spintogether	OPTIONAL (if the value is 1, the number of articles specified in 'count' will be generated & spun together in one big document.)
	counts 			OPTIONAL (the number of documents to create and spin together)
	customkeys 		OPTIONAL (use custom keyword replacement)
	customkeyslist	OPTIONAL (list of custom keyword replacements, separated by a line break)
	spin 			OPTIONAL (0 = No[default], 1 = Yes -- spin content using) the BestSpinner API
	phrasesonly		OPTIONAL (0 = No[default], 1 = Yes -- only spin phrases in the article with TBS the BestSpinner API
	generatenow		OPTIONAL (0 = No [default], 1= Yes -- return a randomly spun version of content)
	paracount		OPTIONAL (0 = Don't try to keep paragraph counts the same [default], 1 = Make sure all articles have the same number of pargraphs
	```

	IMPORTANT NOTE: *If you choose to spin multiple articles together, it costs one quota point for each article generated. That is, if you set 'spintogether' as 1 and set a 'count' of 5, it will cost you 5 daily quota points.*
	```
	Returns:
		[Variable]   	[Value]
			output 		(article)
			success		true
			session		(a unique session id)
	```

3.	`injectContent` :

	*Inject Content function authenticates user and performs the content injection.*

	`function`: article($dataArray)

	`require`:	*dataArray having folllowing keys and values*
	```
	[Key]					[Value]
	action					injectArticle
	article 				(the article to inject content into)
	category				(the category to build an article in)
	keywords 				OPTIONAL (to get tips containing specific keywords)
	volume					(amount of content to add: 1 = A Lot, 2 = Quit A Bit, 3 = A Little)
	style 					(1 = Inside The Content, 2 = As Sidebar "Tips", 3 = Inside and Sidebar, 4 = In-Line Callout, 5 = Inside and Callout)
	superspun				(1 to use Super Spun content, 2 to use Expanded Super Spun content, 0 to use unspun content -- not yet available for all categories)
	sidebarBackgroundColor	OPTIONAL (the HTML background color of the tips sidebar; default is "FFFFCC")
	sidebarCaption 			OPTIONAL (the caption to use in the sidebar; default is "TIP!")
	sidebarCaptionColor		OPTIONAL (the foreground color of the caption; default is "red")
	sidebarTipColor 		OPTIONAL (the foreground color of the tip text; default is "black")
	spin 					OPTIONAL (0 = No [default], 1 = Yes -- spin content using The Best Spinner API)
	phrasesonly				OPTIONAL(0 = No [default], 1 = Yes -- only spin phrases in the article with TBS)
	generatenow 			OPTIONAL(0 = No [default], 1 = Yes -- return a randomly spun version of content)
	```

3.	`superSpun` :

	*Generates an article from a randomly selected super-spun document in the specified category.*

	`function`: article($dataArray)

	`require`:	*dataArray having folllowing keys and values*
	```
		[Key]				[Value]
		action				superSpun
		category			(the category to generate an article for)
	```

4. `getTip` :
	
	*Returns one tip from the given category. Very useful as blog post comments or other shorter backlink texts.*

	`function`: article($dataArray)

	`require`: *dataArray having following keys and values*
	```
	[Key]				[Value]
	action				getTip
	category			(the category to generate an article for)
	keywords			(attempt to return a tip containing the provided keywords)
	superspun			(1 to use Super Spun Content, 2 for Expanded Super Spun, 0 for all content)
	spin				OPTIONAL (0 = No [default], 1 = Yes -- spin content using The Best Spinner API)
	phrasesonly			OPTIONAL(0 = No [default], 1 = Yes -- only spin phrases in the article with TBS)
	generatenow			OPTIONAL(0 = No [default], 1 = Yes -- return a randomly spun version of content)
	```

5. `blogAdd` :

	*Adds a new configured blog to the user's account. If a blog matching the description already exists, its details are updated.*

	`function`: addDeleteBlog($dataArray)

	`require`: *dataArray having following keys and values*
	```
	[Key]				[Value]
	action				blogAdd
	description			(a unique description for the blog)
	url					(the blog URL)
	username			(the blog username)
	password			(the blog password)
	```

6. `blogDelete` :

	*Deletes a blog (and all associated auto-posting jobs for the blog) from the user's account.*

	`function`: addDeleteBlog($dataArray)

	`require`: *dataArray having following keys and values*
	```
	[Key]			[Value]
	action			blogDelete
	description		(the description of the blog to delete)
	```

7. `createBlogPostJob` :

	*Create's an auto-posting job for the passed blog in the user's account. The job ID is returned in the output parameter, and is required for deleting the job or creating auto-posts on demand (using the doAutoPost API call).*

	`function`: blogPostJob($dataArray)

	`require`: *dataArray having following keys and values*
	```
	[Key]			[Value]
	action 			createBlogPostJob
	blog			(the description of the blog to create the job for)
	category		(the category to post an article from -- multiple categories can be used by separating the categories with a pipe character (|). If multiple categories are provided, one will be randomly selected to post from each time the job gets called)
	blogcategory	(the blog category to put the post in--use the full category name)
	wordcountmin	(the minimum number of words the posted article should have: 300 is the absolute minimum, 1000 is the absolute maximum)
	wordcountmax	(the maximum number of words the posted article should have: 300 is the absolute minimum, 1000 is the absolute maximum)
	frequency		(an integer value representing the number of seconds in between posts -- minimum is 28800, or about three posts per day. Note that the posting time will not be exact, as it is adjusted automatically to cause posts to be more random in appearance)
	genericresource	OPTIONAL (0 = no generic resource box [default], 1 = use generic resource box)
	genericlinks	OPTIONAL (required if genericresource is 1 -- see auto-posting details page for full description of this option)
	lsireplacement	OPTIONAL (0 = no LSI replacement [default], 1 = use LSI replacement in supported categories)
	addheadings		OPTIONAL (0 = don't add headings to the post [default], 1 = add headings to the post)
	addimages		OPTIONAL (0 = don't add an image to the post [default], 1 = add an image to the post)
	addyoutube		OPTIONAL (0 = don't add a youtube video to the post [default], 1 = add an youtube video to the post)
	addinjection	OPTIONAL (0 = don't inject additional content into the post [default], 1 = inject additional content)
	addclickbank	OPTIONAL (0 = don't add Clickbank ad links to the post [default], 1 = add Clickbank ad links to the post)
	cbusername		OPTIONAL (required if addclickbank = 1 -- ClickBank username to use in CB ad links)
	customkeys		OPTIONAL (0 = don't perform custom keyword replacement [default], 1 = perform custom keyword replacement)
	customkeyslist	OPTIONAL (required if customkeys = 1 -- data for custom keyword replacement -- see auto-posting details page for full description of this option)
	injectstyle		OPTIONAL (1 = inside the content, 2 = as sidebar "tips" [default], 3 = both inside and sidebar, 4 = as in-line "callouts", 5 = both inside and callouts)
	injectsidebar	OPTIONAL (description of user-saved sidebar configuration to format injected content with)
	injectqty		OPTIONAL (1 = A lot, 2 = Quite a bit, 3 = A little [default])
	resource		OPTIONAL (resource box, nested spinning supported)
	comments		OPTIONAL (0 = don't allow comments on the post [default], 1 = allow comments on the post)
	pingbacks		OPTIONAL (0 = don't allow pingbacks on the post [default], 1 = allow pingbacks on the post)
	draft			OPTIONAL (0 = immediately publish the post [default], 1 = post as a draft)
	```

8. `deleteBlogPostJob` :

	*Deletes an auto-posting job from the user's account.*

	`function`: blogPostJob($dataArray)

	`require`: *dataArray having following keys and values*
	```
	[Key]			[Value]
	action			deleteBlogPostJob
	id				(the blog posting job id)
	```

9. `doAutoPost` :

	*Runs the specified auto-posting job on demand, which will post content to the configured blog. The posted url returned by XMLRPC is returned in the output variable if successful.*

	`function`: blogPostJob($dataArray)

	`require`: *dataArray having following keys and values*
	```
	[Key]			[Value]
	action			doAutoPost
	id				(the blog posting job id to run)
	```

10. `createUniquePostJob`

	*Create's a unique content auto-posting job for the passed blog in the user's account. The job ID is returned in the output parameter, and is required for deleting the job or creating auto-posts on demand (using the doUniqueAutoPost API call).*

	`function`: unqiuePostJob($dataArray)

	`require`: *dataArray having following keys and values*

	```
	[Key]			[Value]
	action			createUniquePostJob
	blog			(the description of the blog to create the job for)
	apikey			(your iNeedArticles.com API key)
	keywords		(the keywords to order articles for -- multiple keywords can be used by separating the keywords with a newline character. If multiple keywords are provided, one will be randomly selected to post from each time the job gets called)
	exactkeys		(0 = Do not require exact keywords in ordered articles [Default], 1 = Require exact keywords in ordered articles)
	bestwriters		(0 = Any iNeedArticles writer is allowed [default], 1 = only 4 or 5 star writers allowed (more expensive))
	extraresearch	(0 = No extra research fee [default], 1 = Add extra research fee (more in-depth articles, but more expensive))
	blogcategory	(the blog category to put the post in--use the full category name)
	wordcountmin	(the minimum number of words the posted article should have: 100 is the absolute minimum, 1000 is the absolute maximum)
	wordcountmax	(the maximum number of words the posted article should have: 100 is the absolute minimum, 1000 is the absolute maximum)
	frequency		(an integer value representing the number of seconds in between posts -- minimum is 28800, or about three posts per day. Note that the posting time will not be exact, as it is adjusted automatically to cause posts to be more random in appearance)
	genericresource	OPTIONAL (0 = no generic resource box [default], 1 = use generic resource box)
	genericlinks	OPTIONAL (required if genericresource is 1 -- see auto-posting details page for full description of this option)
	addimages		OPTIONAL (0 = don't add an image to the post [default], 1 = add an image to the post)
	addyoutube		OPTIONAL (0 = don't add a youtube video to the post [default], 1 = add an youtube video to the post)
	resource		OPTIONAL (resource box, nested spinning supported)
	comments		OPTIONAL (0 = don't allow comments on the post [default], 1 = allow comments on the post)
	pingbacks		OPTIONAL (0 = don't allow pingbacks on the post [default], 1 = allow pingbacks on the post)
	draft			OPTIONAL (0 = immediately publish the post [default], 1 = post as a draft)
	```

10. `deleteUniquePostJob`

	*Deletes a unique content auto-posting job from the user's account.*

	`function`: unqiuePostJob($dataArray)

	`require`: *dataArray having following keys and values*
	```
	[Key]			[Value]
	action			deleteUniquePostJob
	id				(the unique content posting job id)
	```

11. `doUniqueAutoPost`

	*Runs the specified unique content auto-posting job on demand, which will post content to the configured blog. The posted url returned by XMLRPC is returned in the output variable if successful. Keep in mind that there have to be completed articles available from iNeedArticles.com or the call will fail.*

	`function`: unqiuePostJob($dataArray)

	`require`: *dataArray having following keys and values*
	```
	[Key]			[Value]
	action			doUniqueAutoPost
	id				(the unique content posting job id to run)
	```

12. `categories`

	`function`: categories()
	```
	Returns:
		[Key]		[Value]
		success		true
		output		(the category list--PHP array or XML table)
	```

13. `apiQueries`

	`function`:	apiQueries()
	```
	Returns:
		[Key]			[Value]
		success			true
		apiQueries		(the number of queries that have been performed today)
		maxApiQueries	(the number of queries that the authenticated user can make to the API per day)
	```

14. `apiTipQueries`

	`function`: apiTipQueries()
	```
	Returns:
		[Key]				[Value]
		success				true
		apiTipQueries		(the number of getTip queries that have been performed today)
		maxApiTipQueries	(the number of getTip queries that the authenticated user can make to the API per day)
	```

## XMLRPClientWordpress Service Functions

1.	`createPost`

	`function`: createPost($title, $body, $category, $keywords, $encoding)
	
	`require`:	
	
	```
	$title 		title of the article
	$body 		body of the article
	$category 	categories (array) of the article (optional) 
	$keywords 	keyword of the article (optional)
	$encoding 	by default its UTF-8 encoding (optional)

	Returns:
		blogid
	```

2.	`getPost`
	
	`function`: getPost($blogId)

	`require`:

	```
	$blogId 	blog id of the specific blog

	Returns:
		blog details (containing permalink, etc.)

	```

3.	`displayAuthors`

	`funtion`: displayAuthors($blogId)

	`require`:
	```
	$blogId 	(optional) blog id

	Returns:
		blog authors detail
	```

