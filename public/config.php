<?php 

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once 'database.php';
require_once 'contents.php';
require_once 'simple_html_dom.php';

// scraping config
$config['num_page'] = 5; // number of pages to be scraped around
$config['url'] = 'http://www.indeed.com/resumes/nurse/in-Aylesbury?co=GB&radius=15&sort=date';
$config['base_url'] = 'http://www.indeed.com/';

// start scraping
function start_scraping() 
{
	global $config;

	// get the search result
	$search = file_get_html($config['url']);

	// find the result list
	$search_result = $search->find('.resultsList')[0];

	// loop through each list 
	foreach ($search_result->children as $item) {
		
		$theLink = $item->find('div.app_name a')[0];
		// var_dump(get_class_methods($theLink));
		echo $theLink->innertext();
		echo '<br>';
		echo $theLink->getAttribute('href');
		// echo '<a href="">'.$theLink.inner'</a>';
		// var_dump($theLink);

		break;
		// echo $item->find('.app_name')->children()->find('a').innerhtml;
	}
	return $search_result;
}
