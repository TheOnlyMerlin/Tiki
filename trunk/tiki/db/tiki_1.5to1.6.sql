## THIS FILE IS JUST A HELP FOR DEVELOPERS IT SHOULDNT BE USED IN A 1.5 DISTRIBUTION

### Inter-user messages

INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_messages','messu','Can use the messaging system');
INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_broadcast','messu','Can boradcast messages');

drop table if exists messu_messages;
create table messu_messages(
  msgId integer(14) not null auto_increment,
  user varchar(200) not null,
  user_from varchar(200) not null,
  user_to text,
  user_cc text,
  user_bcc text,
  subject varchar(255),
  body text,
  hash char(32),
  date integer(14),
  isRead char(1),
  isReplied char(1),
  isFlagged char(1),
  priority integer(2),
  primary key(msgId)
);


alter table tiki_sessions add user varchar(200);

INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_admin_mailin','tiki','Can admin mail-in accounts');

### Mailin
drop table if exists tiki_mailin_accounts;
create table tiki_mailin_accounts (
  accountId integer(12) not null auto_increment,
  user varchar(200) not null,
  account varchar(50) not null,
  pop varchar(255),
  port integer(4),
  username varchar(100),
  pass varchar(100),
  active char(1),
  type varchar(40),
  smtp varchar(255),
  useAuth char(1),
  smtpPort integer(4),
  primary key(accountId)
);


### Tiki structures permissions
INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_edit_structures','wiki','Can create and edit structures');

### Cache for wiki pages
alter table tiki_pages add cache longblob;
alter table tiki_pages add cache_timestamp integer(14);





### DIRECTORIES BEGIN

INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_admin_directory','directory','Can admin the directory');
INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_view_directory','directory','Can use the directory');
INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_admin_directory_cats','directory','Can admin directory categories');
INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_admin_directory_sites','directory','Can admin directory sites');
INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_submit_link','directory','Can submit sites to the directory');
INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_autosubmit_link','directory','Submited links are valid');
INSERT INTO users_permissions(permName,type,permDesc) VALUES ('tiki_p_validate_links','directory','Can validate submited links');


drop table if exists tiki_directory_categories;
create table tiki_directory_categories(
  categId integer(10) not null auto_increment,
  parent integer(10),
  name varchar(240),
  description text,
  childrenType char(1),
  sites integer(10),
  viewableChildren integer(4),
  allowSites char(1),
  showCount char(1),
  editorGroup varchar(200),
  hits integer(12),
  primary key(categId)
);

drop table if exists tiki_directory_sites;
create table tiki_directory_sites(
  siteId integer(14) not null auto_increment,
  name varchar(240),
  description text,
  url varchar(255),
  country varchar(255),
  hits integer(12),
  isValid char(1),
  created integer(14),
  lastModif integer(14), 
  cache longblob,
  cache_timestamp integer(14),
  primary key(siteId)
);

drop table if exists tiki_category_sites;
create table tiki_category_sites(
  categId integer(10) not null,
  siteId integer(14) not null,
  primary key(categId,siteId)
);

drop table if exists tiki_related_categories;
create table tiki_related_categories(
  categId integer(10) not null,
  relatedTo integer(10) not null,
  primary key(categId,relatedTo)
);

drop table if exists tiki_directory_search;
create table tiki_directory_search(
  term varchar(250) not null,
  hits integer(14),
  primary key(term)
);

### DIRECTORIES END

### FULLTEXT SEARCH BEGIN

alter table tiki_pages modify data text;
create fulltext index ft on tiki_pages (pagename,data);
create fulltext index ft on tiki_galleries (name,description);
create fulltext index ft on tiki_faqs (title,description);
create fulltext index ft on tiki_faq_questions (question,answer);
create fulltext index ft on tiki_images (name,description);
create fulltext index ft on tiki_comments (title,data);
create fulltext index ft on tiki_files (name,description);
create fulltext index ft on tiki_blogs (title,description);
alter table tiki_articles modify body text;
create fulltext index ft on tiki_articles (title,heading,body);
create fulltext index ft on tiki_blog_posts (data);

### FULLTEXT SEARCH END

alter table users_permissions add level varchar(80);
## LEVELS
## 1-anonymous users
## 2-registered users
## 3-editors&friends
## 4-admins
UPDATE users_permissions set level='editors' where permName='tiki_p_edit_structures ';
UPDATE users_permissions set level='registered' where permName='tiki_p_messages';
UPDATE users_permissions set level='admin' where permName='tiki_p_broadcast';
UPDATE users_permissions set level='admin' where permName='tiki_p_admin_mailin';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_directory';
UPDATE users_permissions set level='basic' where permName='tiki_p_view_directory';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_directory_cats';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_directory_sites';
UPDATE users_permissions set level='basic' where permName='tiki_p_submit_link';
UPDATE users_permissions set level='editors' where permName='tiki_p_autosubmit_link';
UPDATE users_permissions set level='editors' where permName='tiki_p_validate_links';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_galleries';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_file_galleries';
UPDATE users_permissions set level='editors' where permName='tiki_p_create_file_galleries';
UPDATE users_permissions set level='registered' where permName='tiki_p_upload_files';
UPDATE users_permissions set level='basic' where permName='tiki_p_download_files';
UPDATE users_permissions set level='basic' where permName='tiki_p_post_comments';
UPDATE users_permissions set level='basic' where permName='tiki_p_read_comments';
UPDATE users_permissions set level='editors' where permName='tiki_p_remove_comments';
UPDATE users_permissions set level='registered' where permName='tiki_p_vote_comments';
UPDATE users_permissions set level='admin' where permName='tiki_p_admin';
UPDATE users_permissions set level='basic' where permName='tiki_p_edit';
UPDATE users_permissions set level='basic' where permName='tiki_p_view';
UPDATE users_permissions set level='editors' where permName='tiki_p_remove';
UPDATE users_permissions set level='registered' where permName='tiki_p_rollback';
UPDATE users_permissions set level='editors' where permName='tiki_p_create_galleries';
UPDATE users_permissions set level='registered' where permName='tiki_p_upload_images';
UPDATE users_permissions set level='editors' where permName='tiki_p_use_HTML';
UPDATE users_permissions set level='editors' where permName='tiki_p_create_blogs';
UPDATE users_permissions set level='registered' where permName='tiki_p_blog_post';
UPDATE users_permissions set level='editors' where permName='tiki_p_blog_admin';
UPDATE users_permissions set level='editors' where permName='tiki_p_edit_article';
UPDATE users_permissions set level='editors' where permName='tiki_p_remove_article';
UPDATE users_permissions set level='basic' where permName='tiki_p_read_article';
UPDATE users_permissions set level='basic' where permName='tiki_p_submit_article';
UPDATE users_permissions set level='editors' where permName='tiki_p_edit_submission';
UPDATE users_permissions set level='editors' where permName='tiki_p_remove_submission';
UPDATE users_permissions set level='editors' where permName='tiki_p_approve_submission';
UPDATE users_permissions set level='admin' where permName='tiki_p_edit_templates';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_dynamic';
UPDATE users_permissions set level='admin' where permName='tiki_p_admin_banners';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_wiki';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_cms';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_categories';
UPDATE users_permissions set level='registered' where permName='tiki_p_send_pages';
UPDATE users_permissions set level='registered' where permName='tiki_p_sendme_pages';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_received_pages';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_forum';
UPDATE users_permissions set level='basic' where permName='tiki_p_forum_post';
UPDATE users_permissions set level='basic' where permName='tiki_p_forum_post_topic';
UPDATE users_permissions set level='basic' where permName='tiki_p_forum_read';
UPDATE users_permissions set level='registered' where permName='tiki_p_forum_vote';
UPDATE users_permissions set level='basic' where permName='tiki_p_read_blog';
UPDATE users_permissions set level='basic' where permName='tiki_p_view_image_gallery';
UPDATE users_permissions set level='basic' where permName='tiki_p_view_file_gallery';
UPDATE users_permissions set level='editors' where permName='tiki_p_edit_comments';
UPDATE users_permissions set level='basic' where permName='tiki_p_vote_poll';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_chat';
UPDATE users_permissions set level='basic' where permName='tiki_p_chat';
UPDATE users_permissions set level='basic' where permName='tiki_p_topic_read';
UPDATE users_permissions set level='basic' where permName='tiki_p_play_games';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_games';
UPDATE users_permissions set level='editors' where permName='tiki_p_edit_cookies';
UPDATE users_permissions set level='basic' where permName='tiki_p_view_stats';
UPDATE users_permissions set level='registered' where permName='tiki_p_create_bookmarks';
UPDATE users_permissions set level='registered' where permName='tiki_p_configure_modules';
UPDATE users_permissions set level='registered' where permName='tiki_p_cache_bookmarks';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_faqs';
UPDATE users_permissions set level='basic' where permName='tiki_p_view_faqs';
UPDATE users_permissions set level='editors' where permName='tiki_p_send_articles';
UPDATE users_permissions set level='registered' where permName='tiki_p_sendme_articles';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_received_articles';
UPDATE users_permissions set level='editors' where permName='tiki_p_view_referer_stats';
UPDATE users_permissions set level='basic' where permName='tiki_p_wiki_attach_files';
UPDATE users_permissions set level='editors' where permName='tiki_p_wiki_admin_attachments';
UPDATE users_permissions set level='basic' where permName='tiki_p_wiki_view_attachments';
UPDATE users_permissions set level='editors' where permName='tiki_p_batch_upload_images';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_drawings';
UPDATE users_permissions set level='basic' where permName='tiki_p_edit_drawings';
UPDATE users_permissions set level='basic' where permName='tiki_p_view_html_pages';
UPDATE users_permissions set level='editors' where permName='tiki_p_edit_html_pages';
UPDATE users_permissions set level='basic' where permName='tiki_p_view_shoutbox';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_shoutbox';
UPDATE users_permissions set level='basic' where permName='tiki_p_post_shoutbox';
UPDATE users_permissions set level='basic' where permName='tiki_p_suggest_faq';
UPDATE users_permissions set level='editors' where permName='tiki_p_edit_content_templates';
UPDATE users_permissions set level='editors' where permName='tiki_p_use_content_templates';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_quizzes';
UPDATE users_permissions set level='basic' where permName='tiki_p_take_quiz';
UPDATE users_permissions set level='basic' where permName='tiki_p_view_quiz_stats';
UPDATE users_permissions set level='editors' where permName='tiki_p_view_user_results';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_newsletters';
UPDATE users_permissions set level='basic' where permName='tiki_p_subscribe_newsletters';
UPDATE users_permissions set level='editors' where permName='tiki_p_subscribe_email';
UPDATE users_permissions set level='registered' where permName='tiki_p_use_webmail';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_surveys';
UPDATE users_permissions set level='basic' where permName='tiki_p_take_survey';
UPDATE users_permissions set level='basic' where permName='tiki_p_view_survey_stats';
UPDATE users_permissions set level='registered' where permName='tiki_p_modify_tracker_items';
UPDATE users_permissions set level='basic' where permName='tiki_p_comment_tracker_items';
UPDATE users_permissions set level='registered' where permName='tiki_p_create_tracker_items';
UPDATE users_permissions set level='editors' where permName='tiki_p_admin_trackers';
UPDATE users_permissions set level='basic' where permName='tiki_p_view_trackers';
UPDATE users_permissions set level='registered' where permName='tiki_p_attach_trackers';
UPDATE users_permissions set level='basic' where permName='tiki_p_upload_picture';
UPDATE users_permissions set level='editors' where permName='tiki_p_batch_upload_files';

CREATE FULLTEXT INDEX ft ON tiki_directory_sites (name,description);