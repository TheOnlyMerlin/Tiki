-- tiki_convert_myisam_to_innodb.sql
-- Script to convert a Tiki database from MyISAM to InnoDB
-- Make sure you take a full backup and disable the MySQL fulltext search option in Tiki before running this script
--

-- DROP all FULLTEXT indexes
-- 	If you are running MySQL 5.6 or later, InnoDB supports fulltext indexes.
--	Then this section should be removed / commented out
ALTER TABLE tiki_articles DROP INDEX ft;
ALTER TABLE tiki_blog_posts DROP INDEX ft;
ALTER TABLE tiki_blogs DROP INDEX ft;
ALTER TABLE tiki_calendar_items DROP INDEX ft;
ALTER TABLE tiki_comments DROP INDEX ft;
ALTER TABLE tiki_directory_sites DROP INDEX ft;
ALTER TABLE tiki_faq_questions DROP INDEX ft;
ALTER TABLE tiki_faqs DROP INDEX ft;
ALTER TABLE tiki_files DROP INDEX ft;
ALTER TABLE tiki_galleries DROP INDEX ft;
ALTER TABLE tiki_images DROP INDEX ft;
ALTER TABLE tiki_pages DROP INDEX ft;
ALTER TABLE tiki_tracker_item_fields DROP INDEX ft;

-- Convert engine type to InnoDB
ALTER TABLE `messu_messages` ENGINE=InnoDB;
ALTER TABLE `messu_archive` ENGINE=InnoDB;
ALTER TABLE `messu_sent` ENGINE=InnoDB;
ALTER TABLE `sessions` ENGINE=InnoDB;
ALTER TABLE `tiki_actionlog` ENGINE=InnoDB;
ALTER TABLE `tiki_actionlog_params` ENGINE=InnoDB;
ALTER TABLE `tiki_articles` ENGINE=InnoDB;
ALTER TABLE `tiki_article_types` ENGINE=InnoDB;
ALTER TABLE `tiki_banners` ENGINE=InnoDB;
ALTER TABLE `tiki_banning` ENGINE=InnoDB;
ALTER TABLE `tiki_banning_sections` ENGINE=InnoDB;
ALTER TABLE `tiki_blog_activity` ENGINE=InnoDB;
ALTER TABLE `tiki_blog_posts` ENGINE=InnoDB;
ALTER TABLE `tiki_blog_posts_images` ENGINE=InnoDB;
ALTER TABLE `tiki_blogs` ENGINE=InnoDB;
ALTER TABLE `tiki_calendar_categories` ENGINE=InnoDB;
ALTER TABLE `tiki_calendar_recurrence` ENGINE=InnoDB;
ALTER TABLE `tiki_calendar_items` ENGINE=InnoDB;
ALTER TABLE `tiki_calendar_locations` ENGINE=InnoDB;
ALTER TABLE `tiki_calendar_roles` ENGINE=InnoDB;
ALTER TABLE `tiki_calendars` ENGINE=InnoDB;
ALTER TABLE `tiki_calendar_options` ENGINE=InnoDB;
ALTER TABLE `tiki_categories` ENGINE=InnoDB;
ALTER TABLE `tiki_objects` ENGINE=InnoDB;
ALTER TABLE `tiki_categorized_objects` ENGINE=InnoDB;
ALTER TABLE `tiki_category_objects` ENGINE=InnoDB;
ALTER TABLE `tiki_object_ratings` ENGINE=InnoDB;
ALTER TABLE `tiki_category_sites` ENGINE=InnoDB;
ALTER TABLE `tiki_chat_channels` ENGINE=InnoDB;
ALTER TABLE `tiki_chat_messages` ENGINE=InnoDB;
ALTER TABLE `tiki_chat_users` ENGINE=InnoDB;
ALTER TABLE `tiki_comments` ENGINE=InnoDB;
ALTER TABLE `tiki_content` ENGINE=InnoDB;
ALTER TABLE `tiki_content_templates` ENGINE=InnoDB;
ALTER TABLE `tiki_content_templates_sections` ENGINE=InnoDB;
ALTER TABLE `tiki_cookies` ENGINE=InnoDB;
ALTER TABLE `tiki_copyrights` ENGINE=InnoDB;
ALTER TABLE `tiki_directory_categories` ENGINE=InnoDB;
ALTER TABLE `tiki_directory_search` ENGINE=InnoDB;
ALTER TABLE `tiki_directory_sites` ENGINE=InnoDB;
ALTER TABLE `tiki_dsn` ENGINE=InnoDB;
ALTER TABLE `tiki_dynamic_variables` ENGINE=InnoDB;
ALTER TABLE `tiki_extwiki` ENGINE=InnoDB;
ALTER TABLE `tiki_faq_questions` ENGINE=InnoDB;
ALTER TABLE `tiki_faqs` ENGINE=InnoDB;
ALTER TABLE `tiki_featured_links` ENGINE=InnoDB;
ALTER TABLE `tiki_file_galleries` ENGINE=InnoDB;
ALTER TABLE `tiki_files` ENGINE=InnoDB;
ALTER TABLE `tiki_file_drafts` ENGINE=InnoDB;
ALTER TABLE `tiki_forum_attachments` ENGINE=InnoDB;
ALTER TABLE `tiki_forum_reads` ENGINE=InnoDB;
ALTER TABLE `tiki_forums` ENGINE=InnoDB;
ALTER TABLE `tiki_forums_queue` ENGINE=InnoDB;
ALTER TABLE `tiki_forums_reported` ENGINE=InnoDB;
ALTER TABLE `tiki_galleries` ENGINE=InnoDB;
ALTER TABLE `tiki_galleries_scales` ENGINE=InnoDB;
ALTER TABLE `tiki_group_inclusion` ENGINE=InnoDB;
ALTER TABLE `tiki_group_watches` ENGINE=InnoDB;
ALTER TABLE `tiki_history` ENGINE=InnoDB;
ALTER TABLE `tiki_hotwords` ENGINE=InnoDB;
ALTER TABLE `tiki_html_pages` ENGINE=InnoDB;
ALTER TABLE `tiki_html_pages_dynamic_zones` ENGINE=InnoDB;
ALTER TABLE `tiki_images` ENGINE=InnoDB;
ALTER TABLE `tiki_images_data` ENGINE=InnoDB;
ALTER TABLE `tiki_language` ENGINE=InnoDB;
ALTER TABLE `tiki_link_cache` ENGINE=InnoDB;
ALTER TABLE `tiki_links` ENGINE=InnoDB;
ALTER TABLE `tiki_live_support_events` ENGINE=InnoDB;
ALTER TABLE `tiki_live_support_message_comments` ENGINE=InnoDB;
ALTER TABLE `tiki_live_support_messages` ENGINE=InnoDB;
ALTER TABLE `tiki_live_support_modules` ENGINE=InnoDB;
ALTER TABLE `tiki_live_support_operators` ENGINE=InnoDB;
ALTER TABLE `tiki_live_support_requests` ENGINE=InnoDB;
ALTER TABLE `tiki_logs` ENGINE=InnoDB;
ALTER TABLE `tiki_mail_events` ENGINE=InnoDB;
ALTER TABLE `tiki_mailin_accounts` ENGINE=InnoDB;
ALTER TABLE `tiki_menu_languages` ENGINE=InnoDB;
ALTER TABLE `tiki_menu_options` ENGINE=InnoDB;
ALTER TABLE `tiki_menus` ENGINE=InnoDB;
ALTER TABLE `tiki_minical_events` ENGINE=InnoDB;
ALTER TABLE `tiki_minical_topics` ENGINE=InnoDB;
ALTER TABLE `tiki_modules` ENGINE=InnoDB;
ALTER TABLE `tiki_newsletter_subscriptions` ENGINE=InnoDB;
ALTER TABLE `tiki_newsletter_groups` ENGINE=InnoDB;
ALTER TABLE `tiki_newsletter_included` ENGINE=InnoDB;
ALTER TABLE `tiki_newsletter_pages` ENGINE=InnoDB;
ALTER TABLE `tiki_newsletters` ENGINE=InnoDB;
ALTER TABLE `tiki_page_footnotes` ENGINE=InnoDB;
ALTER TABLE `tiki_pages` ENGINE=InnoDB;
ALTER TABLE `tiki_pageviews` ENGINE=InnoDB;
ALTER TABLE `tiki_poll_objects` ENGINE=InnoDB;
ALTER TABLE `tiki_poll_options` ENGINE=InnoDB;
ALTER TABLE `tiki_polls` ENGINE=InnoDB;
ALTER TABLE `tiki_preferences` ENGINE=InnoDB;
ALTER TABLE `tiki_private_messages` ENGINE=InnoDB;
ALTER TABLE `tiki_programmed_content` ENGINE=InnoDB;
ALTER TABLE `tiki_quiz_question_options` ENGINE=InnoDB;
ALTER TABLE `tiki_quiz_questions` ENGINE=InnoDB;
ALTER TABLE `tiki_quiz_results` ENGINE=InnoDB;
ALTER TABLE `tiki_quiz_stats` ENGINE=InnoDB;
ALTER TABLE `tiki_quiz_stats_sum` ENGINE=InnoDB;
ALTER TABLE `tiki_quizzes` ENGINE=InnoDB;
ALTER TABLE `tiki_received_articles` ENGINE=InnoDB;
ALTER TABLE `tiki_received_pages` ENGINE=InnoDB;
ALTER TABLE `tiki_referer_stats` ENGINE=InnoDB;
ALTER TABLE `tiki_related_categories` ENGINE=InnoDB;
ALTER TABLE `tiki_rss_modules` ENGINE=InnoDB;
ALTER TABLE `tiki_rss_feeds` ENGINE=InnoDB;
ALTER TABLE `tiki_search_stats` ENGINE=InnoDB;
ALTER TABLE `tiki_secdb` ENGINE=InnoDB;
ALTER TABLE `tiki_semaphores` ENGINE=InnoDB;
ALTER TABLE `tiki_sent_newsletters` ENGINE=InnoDB;
ALTER TABLE `tiki_sent_newsletters_errors` ENGINE=InnoDB;
ALTER TABLE `tiki_sessions` ENGINE=InnoDB;
ALTER TABLE `tiki_sheet_layout` ENGINE=InnoDB;
ALTER TABLE `tiki_sheet_values` ENGINE=InnoDB;
ALTER TABLE `tiki_sheets` ENGINE=InnoDB;
ALTER TABLE `tiki_shoutbox` ENGINE=InnoDB;
ALTER TABLE `tiki_shoutbox_words` ENGINE=InnoDB;
ALTER TABLE `tiki_structure_versions` ENGINE=InnoDB;
ALTER TABLE `tiki_structures` ENGINE=InnoDB;
ALTER TABLE `tiki_submissions` ENGINE=InnoDB;
ALTER TABLE `tiki_suggested_faq_questions` ENGINE=InnoDB;
ALTER TABLE `tiki_survey_question_options` ENGINE=InnoDB;
ALTER TABLE `tiki_survey_questions` ENGINE=InnoDB;
ALTER TABLE `tiki_surveys` ENGINE=InnoDB;
ALTER TABLE `tiki_tags` ENGINE=InnoDB;
ALTER TABLE `tiki_theme_control_categs` ENGINE=InnoDB;
ALTER TABLE `tiki_theme_control_objects` ENGINE=InnoDB;
ALTER TABLE `tiki_theme_control_sections` ENGINE=InnoDB;
ALTER TABLE `tiki_topics` ENGINE=InnoDB;
ALTER TABLE `tiki_tracker_fields` ENGINE=InnoDB;
ALTER TABLE `tiki_tracker_item_attachments` ENGINE=InnoDB;
ALTER TABLE `tiki_tracker_item_fields` ENGINE=InnoDB;
ALTER TABLE `tiki_tracker_item_field_logs` ENGINE=InnoDB;
ALTER TABLE `tiki_tracker_items` ENGINE=InnoDB;
ALTER TABLE `tiki_tracker_options` ENGINE=InnoDB;
ALTER TABLE `tiki_trackers` ENGINE=InnoDB;
ALTER TABLE `tiki_untranslated` ENGINE=InnoDB;
ALTER TABLE `tiki_user_answers` ENGINE=InnoDB;
ALTER TABLE `tiki_user_answers_uploads` ENGINE=InnoDB;
ALTER TABLE `tiki_user_assigned_modules` ENGINE=InnoDB;
ALTER TABLE `tiki_user_bookmarks_folders` ENGINE=InnoDB;
ALTER TABLE `tiki_user_bookmarks_urls` ENGINE=InnoDB;
ALTER TABLE `tiki_user_login_cookies` ENGINE=InnoDB;
ALTER TABLE `tiki_user_mail_accounts` ENGINE=InnoDB;
ALTER TABLE `tiki_user_menus` ENGINE=InnoDB;
ALTER TABLE `tiki_user_modules` ENGINE=InnoDB;
ALTER TABLE `tiki_user_notes` ENGINE=InnoDB;
ALTER TABLE `tiki_user_postings` ENGINE=InnoDB;
ALTER TABLE `tiki_user_preferences` ENGINE=InnoDB;
ALTER TABLE `tiki_user_quizzes` ENGINE=InnoDB;
ALTER TABLE `tiki_user_taken_quizzes` ENGINE=InnoDB;
ALTER TABLE `tiki_user_tasks_history` ENGINE=InnoDB;
ALTER TABLE `tiki_user_tasks` ENGINE=InnoDB;
ALTER TABLE `tiki_user_votings` ENGINE=InnoDB;
ALTER TABLE `tiki_user_watches` ENGINE=InnoDB;
ALTER TABLE `tiki_userfiles` ENGINE=InnoDB;
ALTER TABLE `tiki_userpoints` ENGINE=InnoDB;
ALTER TABLE `tiki_webmail_contacts` ENGINE=InnoDB;
ALTER TABLE `tiki_webmail_contacts_groups` ENGINE=InnoDB;
ALTER TABLE `tiki_webmail_messages` ENGINE=InnoDB;
ALTER TABLE `tiki_wiki_attachments` ENGINE=InnoDB;
ALTER TABLE `tiki_zones` ENGINE=InnoDB;
ALTER TABLE `tiki_download` ENGINE=InnoDB;
ALTER TABLE `users_grouppermissions` ENGINE=InnoDB;
ALTER TABLE `users_groups` ENGINE=InnoDB;
ALTER TABLE `users_objectpermissions` ENGINE=InnoDB;
ALTER TABLE `users_permissions` ENGINE=InnoDB;
ALTER TABLE `users_usergroups` ENGINE=InnoDB;
ALTER TABLE `users_users` ENGINE=InnoDB;
ALTER TABLE `tiki_integrator_reps` ENGINE=InnoDB;
ALTER TABLE `tiki_integrator_rules` ENGINE=InnoDB;
ALTER TABLE `tiki_translated_objects` ENGINE=InnoDB;
ALTER TABLE `tiki_score` ENGINE=InnoDB;
ALTER TABLE `tiki_users_score` ENGINE=InnoDB;
ALTER TABLE `tiki_file_handlers` ENGINE=InnoDB;
ALTER TABLE `tiki_stats` ENGINE=InnoDB;
ALTER TABLE `tiki_registration_fields` ENGINE=InnoDB;
ALTER TABLE `tiki_actionlog_conf` ENGINE=InnoDB;
ALTER TABLE `tiki_freetags` ENGINE=InnoDB;
ALTER TABLE `tiki_freetagged_objects` ENGINE=InnoDB;
ALTER TABLE `tiki_contributions` ENGINE=InnoDB;
ALTER TABLE `tiki_contributions_assigned` ENGINE=InnoDB;
ALTER TABLE `tiki_webmail_contacts_ext` ENGINE=InnoDB;
ALTER TABLE `tiki_webmail_contacts_fields` ENGINE=InnoDB;
ALTER TABLE `tiki_pages_translation_bits` ENGINE=InnoDB;
ALTER TABLE `tiki_pages_changes` ENGINE=InnoDB;
ALTER TABLE `tiki_minichat` ENGINE=InnoDB;
ALTER TABLE `tiki_profile_symbols` ENGINE=InnoDB;
ALTER TABLE `tiki_feature` ENGINE=InnoDB;
ALTER TABLE `tiki_schema` ENGINE=InnoDB;
ALTER TABLE `tiki_semantic_tokens` ENGINE=InnoDB;
ALTER TABLE `tiki_webservice` ENGINE=InnoDB;
ALTER TABLE `tiki_webservice_template` ENGINE=InnoDB;
ALTER TABLE `tiki_groupalert` ENGINE=InnoDB;
ALTER TABLE `tiki_sent_newsletters_files` ENGINE=InnoDB;
ALTER TABLE `tiki_sefurl_regex_out` ENGINE=InnoDB;
ALTER TABLE `tiki_plugin_security` ENGINE=InnoDB;
ALTER TABLE `tiki_user_reports` ENGINE=InnoDB;
ALTER TABLE `tiki_user_reports_cache` ENGINE=InnoDB;
ALTER TABLE `tiki_perspectives` ENGINE=InnoDB;
ALTER TABLE `tiki_perspective_preferences` ENGINE=InnoDB;
ALTER TABLE `tiki_transitions` ENGINE=InnoDB;
ALTER TABLE `tiki_auth_tokens` ENGINE=InnoDB;
ALTER TABLE `metrics_assigned` ENGINE=InnoDB;
ALTER TABLE `metrics_metric` ENGINE=InnoDB;
ALTER TABLE `metrics_tab` ENGINE=InnoDB;
ALTER TABLE `tiki_file_backlinks` ENGINE=InnoDB;
ALTER TABLE `tiki_payment_requests` ENGINE=InnoDB;
ALTER TABLE `tiki_payment_received` ENGINE=InnoDB;
ALTER TABLE `tiki_discount` ENGINE=InnoDB;
ALTER TABLE `tiki_translations_in_progress` ENGINE=InnoDB;
ALTER TABLE `tiki_rss_items` ENGINE=InnoDB;
ALTER TABLE `tiki_object_attributes` ENGINE=InnoDB;
ALTER TABLE `tiki_rating_configs` ENGINE=InnoDB;
ALTER TABLE `tiki_rating_obtained` ENGINE=InnoDB;
ALTER TABLE `tiki_object_relations` ENGINE=InnoDB;
ALTER TABLE `tiki_todo` ENGINE=InnoDB;
ALTER TABLE `tiki_todo_notif` ENGINE=InnoDB;
ALTER TABLE `tiki_url_shortener` ENGINE=InnoDB;
ALTER TABLE `tiki_invite` ENGINE=InnoDB;
ALTER TABLE `tiki_invited` ENGINE=InnoDB;
ALTER TABLE `tiki_credits` ENGINE=InnoDB;
ALTER TABLE `tiki_credits_usage` ENGINE=InnoDB;
ALTER TABLE `tiki_credits_types` ENGINE=InnoDB;
ALTER TABLE `tiki_acct_account` ENGINE=InnoDB;
ALTER TABLE `tiki_acct_bankaccount` ENGINE=InnoDB;
ALTER TABLE `tiki_acct_book` ENGINE=InnoDB;
ALTER TABLE `tiki_acct_item` ENGINE=InnoDB; 
ALTER TABLE `tiki_acct_journal` ENGINE=InnoDB;
ALTER TABLE `tiki_acct_stack` ENGINE=InnoDB; 
ALTER TABLE `tiki_acct_stackitem`ENGINE=InnoDB ;
ALTER TABLE `tiki_acct_statement` ENGINE=InnoDB;
ALTER TABLE `tiki_acct_tax` ENGINE=InnoDB ;
ALTER TABLE `tiki_queue` ENGINE=InnoDB;
ALTER TABLE `tiki_cart_inventory_hold` ENGINE=InnoDB;
ALTER TABLE `tiki_source_auth` ENGINE=InnoDB;
ALTER TABLE `tiki_connect` ENGINE=InnoDB;
ALTER TABLE `tiki_areas` ENGINE=InnoDB;
ALTER TABLE `tiki_page_references` ENGINE=InnoDB;
ALTER TABLE `tiki_db_status` ENGINE=InnoDB;
ALTER TABLE `tiki_mail_queue` ENGINE=InnoDB;
ALTER TABLE `tiki_workspace_templates` ENGINE=InnoDB;
ALTER TABLE `tiki_user_mailin_struct` ENGINE=InnoDB;
ALTER TABLE `tiki_activity_stream` ENGINE=InnoDB;
ALTER TABLE `tiki_activity_stream_mapping` ENGINE=InnoDB;
ALTER TABLE `tiki_activity_stream_rules` ENGINE=InnoDB;


