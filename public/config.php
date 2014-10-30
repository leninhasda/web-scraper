<?php 

// only for testing
debug(0);
function debug($x) {
	ini_set('display_errors',$x);
	ini_set('display_startup_errors',$x);
	error_reporting(0);
}

require_once 'database.php';
require_once 'content.php';
require_once 'simple_html_dom.php';

// scraping config
$config['num_page'] = 3; // number of pages to be scraped around
$config['url'] = 'http://www.indeed.com/resumes/nurse/in-Aylesbury?co=GB&radius=15&sort=date&start=';
$config['base_url'] = 'http://www.indeed.com';

// start scraping
function start_scraping() 
{
	global $config;
	$count = 0;
	$page = 0;
	$offset = 0;

	// loop through all search pages
	for($page; $page < $config['num_page'];) {

		// set offset
		$config['url'] .= $offset;

		// get the search result
		$search = file_get_html($config['url']);

		// check for next page
		$hasNext = $search->find('div#pagination a.next')[0];
		if($hasNext) {
			$page++;
			$offset += 50;
		}

		if($search) {

			// find the result list
			$search_result = $search->find('.resultsList')[0];

			// loop through each list
			foreach ($search_result->children as $item) {
				// find the name and link
				$theLink = $item->find('div.app_name a')[0];
				$name = $theLink->innertext();
				$link = html_entity_decode($config['base_url'].$theLink->getAttribute('href'));

				// check for duplicate entry
				if( ! Content::check_duplicate($name, $link)) {
					// get the resume
					$resume = file_get_html($link);

					if($resume) {
						$resume = $resume->find("div#resume")[0];
						$resume = $resume->__toString();

						// add it to database
						$content = new Content;
						$content->name = $name;
						$content->link = $link;
						$content->resume = addslashes($resume);
						if($content->save()) {
							$count++;
						}

						// delay execution for a sec
						sleep(rand(1,2));
					}
				}
			}
		}
	}
	return $count;
}
