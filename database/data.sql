INSERT INTO `forum` VALUES 
	(2,2,'General Discussion',0,'Forum for any topic of interest to the Qcodo community, including questions about the framework itself.',null),
	(3,3,'Bugs and Issues',0,'This forum is specific to reporting bugs and issues with the framework.',null),
	(4,7,'Feature Requests',0,'Forum discussing feature requests for the framework.',null),
	(5,1,'Official Blog',1,'This is a blog of current Qcodo and Qcodo website development activities.  Official Qcodo announcements will also be posted here.',null),
	(6,6,'Getting Started, Installation and Setup',0,'For questions, issues and topics that specifically deal with getting started with Qcodo (e.g. installation, configuration, subfoldering, etc.)',null),
	(7,5,'Examples Site and \"Qcodo Manual\" Project',0,'For anything regarding the <a href=\"http://examples.qcodo.com/\" class=\"link_body\">Examples Site</a> or the <a href=\"http://qcodo.kri-soft.be/\" class=\"link_body\">\"Qcodo Manual\" Project</a>.',null),
	(8,5,'QForge and Qcodo Hosting',0,'For anything regarding <a class=\"link_body\" href=\"http://qforge.qcodo.com/\">QForge</a> or <a class=\"link_body\" href=\"http://www.qcodohosting.org/\">Qcodo Hosting</a>',null),
	(9,4,'Qcodo Jobs Board',0,'For Qcodo-related job openings, contracting opportunities and developers for hire.','2009-03-26 17:45:53');

INSERT INTO person_type VALUES (1,'Administrator');
INSERT INTO person_type VALUES (2,'Moderator');
INSERT INTO person_type VALUES (3,'Contributor');
INSERT INTO person_type VALUES (4,'Forums User');

INSERT INTO `download_category` VALUES
	(1,1,'Official Releases',1,'For Official Qcodo Releases','For the latest DEVELOPMENT snapshot, please go to the <a href=\"/support/\" class=\"link_body\">Support</a> page.',null),
	(2,2,'Qform Controls',0,'For Community-Contributed Qform Classes','Custom Qform class files are typically installed in wwwroot/includes/qform, inside qform_objects',null),
	(3,4,'Other',0,'For other Community-Contributed classes, utilities, and code-snippets','',null),
	(4,3,'Language Files',0,'A place to share language files and language file updates for Internationalized Qcodo.','These are files that should be placed within the core at /includes/qcodo/i18n.  As files are uploaded and perfected, and as authors grant permission, we will put them into the core in future releases.',null);

INSERT INTO article_section VALUES(1,'Getting Started');
INSERT INTO article_section VALUES(2,'Harnessing the Power of Qcodo');
INSERT INTO article_section VALUES(3,'Advanced Topics');

INSERT INTO person(person_type_id, username, first_name, last_name, email, password) VALUES (1, 'mikeho', 'Mike', 'Ho', 'mike@quasidea.com', '67a1e09bb1f83f5007dc119c14d663aa');