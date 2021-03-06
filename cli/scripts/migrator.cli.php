<?php
	// This should not be in use anymore
	exit("error: database already migrated\r\n");
	$objDb = new mysqli('localhost', 'root', null, 'qcodo_website_old');

	$objResult = $objDb->query('SELECT * FROM person ORDER BY id');
	while (QDataGen::DisplayWhileTask('Migrating People', $objResult->num_rows)) {
		$objRow = $objResult->fetch_array();
		$objPerson = new Person();
		$objPerson->PersonTypeId = $objRow['person_type_id'];
		$objPerson->Username = $objRow['username'];
		$objPerson->SetPassword($objRow['password']);
		$objPerson->FirstName = $objRow['first_name'];
		$objPerson->LastName = $objRow['last_name'];
		$objPerson->Email = $objRow['email'];
		$objPerson->PasswordResetFlag = false;
		$objPerson->DisplayRealNameFlag = $objRow['display_real_name_flag'];
		$objPerson->DisplayEmailFlag = $objRow['display_email_flag'];
		$objPerson->OptInFlag = $objRow['opt_in_flag'];
		$objPerson->DonatedFlag = $objRow['donated_flag'];
		$objPerson->Location = $objRow['location'];
		$objPerson->Url = $objRow['url'];
		$objPerson->RegistrationDate = new QDateTime($objRow['registration_date']);

		if ($objRow['country_id']) {
			$objCountryResult = $objDb->query('SELECT * FROM country WHERE id=' . $objRow['country_id']);
			$objCountryRow = $objCountryResult->fetch_array();
			$objPerson->Country = Country::LoadByCode($objCountryRow['code']);
		}

		$objPerson->RefreshDisplayName();
		$objPerson->Save();
		
		if ($objPerson->Id != $objRow['id']) {
			Person::GetDatabase()->NonQuery('UPDATE person SET id=' . $objRow['id'] . ' WHERE id=' . $objPerson->Id);
		}
	}

	$intNewTopicLinkIdArray[3591] = 7;
	$intNewTopicLinkIdArray[3578] = 7;
	$intNewTopicLinkIdArray[3547] = 7;
	$intNewTopicLinkIdArray[3455] = 7;
	$intNewTopicLinkIdArray[3242] = 7;
	$intNewTopicLinkIdArray[3244] = 7;
	
	$objResult = $objDb->query('SELECT * FROM topic ORDER BY id');
	while (QDataGen::DisplayWhileTask('Migrating Topics', $objResult->num_rows)) {
		$objRow = $objResult->fetch_array();
		$objTopic = new Topic();
		if (array_key_exists(intval($objRow['id']), $intNewTopicLinkIdArray))
			$objTopic->TopicLinkId = $intNewTopicLinkIdArray[intval($objRow['id'])];
		else
			$objTopic->TopicLinkId = $objRow['forum_id'] - 1;
		$objTopic->Name = $objRow['name'];
		$objTopic->PersonId = $objRow['person_id'];
		$objTopic->Save();

		if ($objTopic->Id != $objRow['id']) {
			Topic::GetDatabase()->NonQuery('UPDATE topic SET id=' . $objRow['id'] . ' WHERE id=' . $objTopic->Id);
			Topic::GetDatabase()->NonQuery('ALTER TABLE topic AUTO_INCREMENT=' . ($objTopic->Id + 1));
		}
	}


	$objResult = $objDb->query('SELECT * FROM message ORDER BY id');
	while (QDataGen::DisplayWhileTask('Migrating Messages', $objResult->num_rows)) {
		$objRow = $objResult->fetch_array();
		$objMessage = new Message();
		$objMessage->TopicLinkId = $objRow['forum_id'] - 1;
		$objMessage->TopicId = $objRow['topic_id'];
		$objMessage->PersonId = $objRow['person_id'];
		$objMessage->ReplyNumber = Message::QueryCount(QQ::Equal(QQN::Message()->TopicId, $objRow['topic_id'])) + 1;
		$objMessage->PostDate = new QDateTime($objRow['post_date']);

		$strContent = $objRow['message'];
		$strContent = str_replace("\r\n", "\n", $strContent);
		$strContent = str_replace("\n", "\r\n", $strContent);

		$strContent = str_replace('<b>', '*', $strContent);
		$strContent = str_replace('</b>', '*', $strContent);
		$strContent = str_replace('<i>', '+', $strContent);
		$strContent = str_replace('</i>', '+', $strContent);
		$strContent = str_replace('<u>', '_', $strContent);
		$strContent = str_replace('</u>', '_', $strContent);

		while (strpos($strContent, "\r\n<code>") !== false) $strContent = str_replace("\r\n<code>", '<code>', $strContent);
		while (strpos($strContent, "<code>\r\n") !== false) $strContent = str_replace("<code>\r\n", '<code>', $strContent);
		while (strpos($strContent, "\r\n</code>") !== false) $strContent = str_replace("\r\n</code>", '</code>', $strContent);
		while (strpos($strContent, "</code>\r\n") !== false) $strContent = str_replace("</code>\r\n", '</code>', $strContent);
		$strContent = str_replace('<code>', "\r\n\r\nbc.\r\n", $strContent);
		$strContent = str_replace('</code>', "\r\n.bc\r\n\r\n", $strContent);

		$objMessage->Message = trim($strContent);
		$objMessage->RefreshCompiledHtml();
		$objMessage->Save();

		if ($objMessage->Id != $objRow['id']) {
			Message::GetDatabase()->NonQuery('UPDATE message SET id=' . $objRow['id'] . ' WHERE id=' . $objMessage->Id);
			Message::GetDatabase()->NonQuery('ALTER TABLE message AUTO_INCREMENT=' . ($objMessage->Id + 1));
		}
	}


	$objResult = $objDb->query('SELECT * FROM counter ORDER BY id');
	while (QDataGen::DisplayWhileTask('Migrating Counters', $objResult->num_rows)) {
		$objRow = $objResult->fetch_array();
		$objCounter = new Counter();
		$objCounter->Filename = $objRow['filename'];
		$objCounter->Token = $objRow['token'];
		$objCounter->Counter = $objRow['counter'];
		$objCounter->Save();

		if ($objCounter->Id != $objRow['id']) {
			Counter::GetDatabase()->NonQuery('UPDATE Counter SET id=' . $objRow['id'] . ' WHERE id=' . $objCounter->Id);
		}
	}


	$objResult = $objDb->query('SELECT * FROM article ORDER BY id');
	while (QDataGen::DisplayWhileTask('Migrating Articles', $objResult->num_rows)) {
		$objRow = $objResult->fetch_array();
		switch ($objRow['article_section_id']) {
			case 1:
				$strPath = '/article/getting_started/';
				break;
			case 2:
				$strPath = '/article/harnessing/';
				break;
			case 3:
				$strPath = '/article/background/';
				break;
			default:
				throw new Exception('HERE');
		}
		switch ($objRow['id']) {
			case 1:
				$strPath .= 'installing';
				break;
			case 2:
				$strPath .= 'quickstart_codegen';
				break;
			case 3:
				$strPath .= 'quickstart_qforms';
				break;
			case 4:
				$strPath .= 'adding_to_qforms';
				break;
			case 5:
				$strPath = null;
				break;
			case 6:
				$strPath .= 'metaprogramming';
				break;
			default:
				throw new Exception('HERE');
		}
		
		if ($strPath) {
			$objWikiItem = WikiItem::CreateNewItem($strPath, WikiItemType::Page);

			$strContent = $objRow['description'] . "\r\n\r\n";
			$strContent .= $objRow['article'];

			$strContent = str_replace("\r\n", "\n", $strContent);
			$strContent = str_replace("\n", "\r\n", $strContent);
			
			$strContent = str_replace('<b>', '*', $strContent);
			$strContent = str_replace('</b>', '*', $strContent);
			$strContent = str_replace('<i>', '+', $strContent);
			$strContent = str_replace('</i>', '+', $strContent);

			$strContent = str_replace('<hr/>', "\r\n\r\n", $strContent);
			$strContent = str_replace('<li>', "* ", $strContent);
			$strContent = str_replace("</li>\r\n", "</li>", $strContent);
			$strContent = str_replace("</li>", "\r\n", $strContent);
			$strContent = str_replace('<ul>', "\r\n", $strContent);
			$strContent = str_replace('</ul>', "\r\n", $strContent);

			$strContent = str_replace('<code_title>', '*', $strContent);
			$strContent = str_replace('</code_title>', "*\r\n", $strContent);

			$strContent = str_replace('<code_snippet>', "bc.", $strContent);
			$strContent = str_replace('</code_snippet>', ".bc", $strContent);

			$objWikiPage = new WikiPage();
			$objWikiPage->Content = trim($strContent);
			$objWikiPage->CompileHtml();

			$objWikiItem->CreateNewVersion($objRow['title'], $objWikiPage, 'Save', array(), Person::Load(1), new QDateTime($objRow['post_date']));
		}
	}

	$objResult = $objDb->query('SELECT * FROM download ORDER BY id');
	$arrDownloads = array();
	$arrDownloads[2] = array();
	$arrDownloads[3] = array();
	$arrDownloads[4] = array();
	while (QDataGen::DisplayWhileTask('Migrating Downloads', $objResult->num_rows)) {
		$objRow = $objResult->fetch_array();
		$strFilePath = __DOCROOT__ . '/../old_website/' . $objRow['id'] . '/' . $objRow['filename'];
		
		switch ($objRow['download_category_id']) {
			case 1:
				$strPath = null;
				break;
			case 2:
				$strPath = '/old_downloads/qform_controls/';
				break;
			case 3:
				$strPath = '/old_downloads/other/';
				break;
			case 4:
				$strPath = '/old_downloads/language_files/';
				break;
			default:
				throw new Exception('HERE');
		}

		if ($strPath && file_exists($strFilePath)) {
			$strPath .= $objRow['name'];
			$strPath = WikiItem::SanitizeForPath($strPath, $intWikiItemTypeId);

			// See if the download exists
			$objWikiItem = WikiItem::LoadByPathWikiItemTypeId($strPath, WikiItemType::File);
			if (!$objWikiItem) {
				$objWikiItem = WikiItem::CreateNewItem($strPath, WikiItemType::File);
			}

			// Create the Parameters for Save
			$objWikiFile = new WikiFile();
			if ($objRow['parent_download_id']) {
				$objParentResult = $objDb->query('SELECT * FROM download WHERE id=' . $objRow['parent_download_id']);
				$objParentRow = $objParentResult->fetch_array();

				$objWikiFile->Description = trim($objParentRow['description']);
			} else {
				$objWikiFile->Description = trim($objRow['description']);
			}
			$objWikiFile->DownloadCount = $objRow['download_count'];

			$arrMethodParameters = array($strFilePath, $objRow['filename']);

			$objWikiVersion = $objWikiItem->CreateNewVersion(trim($objRow['name']), $objWikiFile, 'SaveFile', $arrMethodParameters,
				Person::Load($objRow['person_id']), new QDateTime($objRow['post_date']));
			$arrDownloads[$objRow['download_category_id']][$objWikiItem->Id] = true;
		}
	}
	
	print 'Generating Old Downloads landing pages... ';
	for ($intIndex = 2; $intIndex <= 4; $intIndex++) {
		switch ($intIndex) {
			case 2:
				$strType = 'QForm Controls';
				$strPath = '/old_downloads/qform_controls';
				break;
			case 3:
				$strType = 'Other';
				$strPath = '/old_downloads/other';
				break;
			case 4:
				$strType = 'Langauge Files';
				$strPath = '/old_downloads/language_files';
				break;
		}
		
		$strName = sprintf('Downloads from Old Qcodo.com Website in "%s"', $strType);
		$strContent = sprintf('These are user-contributed downloads from the old *Qcodo.com* website in "%s", placed here for historical/archive purposes.  ', $strType);
		$strContent .= 'For new user-submitted contributions, be sure to check out the "User Contributions":http://www.qcodo.com/qpm/ section of the website.  "View All Old Downloads":wiki:/old_downloads';
		$strContent .= "\r\n\r\n";
		
		$objWikiItemArray = WikiItem::QueryArray(QQ::In(QQN::WikiItem()->Id, array_keys($arrDownloads[$intIndex])), QQ::OrderBy(QQN::WikiItem()->CurrentName));
		foreach ($objWikiItemArray as $objWikiItem) {
			$strContent .= sprintf('file. %s', $objWikiItem->Path);
			$strContent .= "\r\n\r\n";
		}

		$objWikiItem = WikiItem::CreateNewItem($strPath, WikiItemType::Page);
		$objWikiPage = new WikiPage();
		$objWikiPage->Content = trim($strContent);
		$objWikiPage->CompileHtml();
		$objWikiItem->CreateNewVersion($strName, $objWikiPage, 'Save', array(), Person::Load(1), null);
	}
	
	$objWikiItem = WikiItem::CreateNewItem('/old_downloads', WikiItemType::Page);
	$strContent = 'These are user-contributed downloads from the old *Qcodo.com* website, placed here for historical/archive purposes.  ';
	$strContent .= 'For new user-submitted contributions, be sure to check out the "User Contributions":http://www.qcodo.com/qpm/ section of the website.';
	$strContent .= "\r\n\r\n";
	$strContent .= 'h2. "QForm Controls":wiki:/old_downloads/qform_controls';
	$strContent .= "\r\n\r\n";
	$strContent .= 'Custom Qform class files are typically installed in wwwroot/includes/qform, inside qform_objects';
	$strContent .= "\r\n\r\n";
	$strContent .= 'h2. "Laguage Files":wiki:/old_downloads/language_files';
	$strContent .= "\r\n\r\n";
	$strContent .= 'A place to share language files and language file updates for Internationalized Qcodo. | These are files that should be placed within the core at /includes/qcodo/i18n.  As files are uploaded and perfected, and as authors grant permission, we will put them into the core in future releases.';
	$strContent .= "\r\n\r\n";
	$strContent .= 'h2. "Other":wiki:/old_downloads/other';
	$strContent .= "\r\n\r\n";
	$strContent .= 'For other Community-Contributed classes, utilities, and code-snippets';
	$objWikiPage = new WikiPage();
	$objWikiPage->Content = trim($strContent);
	$objWikiPage->CompileHtml();
	$objWikiItem->CreateNewVersion('Downloads from Old Qcodo.com Website', $objWikiPage, 'Save', array(), Person::Load(1), null);
	print "Done.\r\n";

	QDataGen::DisplayForEachTaskStart($strTitle = 'Refreshing Topic Stats', Topic::CountAll());
	foreach (Topic::LoadAll() as $objTopic) {
		QDataGen::DisplayForEachTaskNext($strTitle);
		$objTopic->RefreshStats();
	}
	QDataGen::DisplayForEachTaskEnd($strTitle);

	QDataGen::DisplayForEachTaskStart($strTitle = 'Refreshing TopicLink Stats', TopicLink::CountAll());
	foreach (TopicLink::LoadAll() as $objTopicLink) {
		QDataGen::DisplayForEachTaskNext($strTitle);
		$objTopicLink->RefreshStats();
	}
	QDataGen::DisplayForEachTaskEnd($strTitle);

	$objResult = $objDb->query('SELECT * FROM email_topic_person_assn');
	while (QDataGen::DisplayWhileTask('Migrating email_topic_person_assn', $objResult->num_rows)) {
		$objRow = $objResult->fetch_array();
		try {
			Topic::GetDatabase()->NonQuery('INSERT INTO email_topic_person_assn(topic_id, person_id) VALUES (' . $objRow['topic_id'] . ',' . $objRow['person_id'] . ');');
		} catch (QMySqliDatabaseException $objExc) {}
	}
	
	$objResult = $objDb->query('SELECT * FROM read_topic_person_assn');
	while (QDataGen::DisplayWhileTask('Migrating read_topic_person_assn', $objResult->num_rows)) {
		$objRow = $objResult->fetch_array();
		try {
			Topic::GetDatabase()->NonQuery('INSERT INTO read_topic_person_assn(topic_id, person_id) VALUES (' . $objRow['topic_id'] . ',' . $objRow['person_id'] . ');');
		} catch (QMySqliDatabaseException $objExc) {}
	}
?>