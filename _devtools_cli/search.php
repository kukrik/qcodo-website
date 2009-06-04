#!/usr/local/bin/php
<?php
	require(dirname(__FILE__) . '/cli_prepend.inc.php');

	// report the index
	$objIndex = new Zend_Search_Lucene(__SEARCH_INDEXES__ . '/forum_topics');
	print "Index contains " . $objIndex->count() . " documents.\r\n\r\n";
	
	if ($_SERVER['argc'] != 2) exit("error: specify a search term\r\n");
	$strSearchQuery = $_SERVER['argv'][1];
	
	$objTopicArray = Topic::LoadArrayBySearch($strSearchQuery);

	print 'Search for "' . $strSearchQuery . '" returned ' . count($objTopicArray) . " topics\r\n\r\n";

	foreach ($objTopicArray as $objTopic) {
		print '[' . $objTopic->Id . '] - ' . $objTopic->Name . "\r\n";
	}
//	foreach ($hits as $hit) {
//		echo $hit->title."\n";
//		echo "\tScore: ".sprintf('%.2f', $hit->score)."\n";
//		echo "\t".$hit->id."\n\n";
//	}
?>