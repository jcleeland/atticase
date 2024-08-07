-- --------------------------------------------------------

--
-- Table structure for table `{prefix}attachments`
--

CREATE TABLE `{prefix}attachments` (
  `attachment_id` mediumint NOT NULL,
  `task_id` mediumint NOT NULL DEFAULT '0',
  `orig_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `file_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `file_desc` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `file_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `file_size` bigint NOT NULL DEFAULT '0',
  `file_date` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `added_by` mediumint NOT NULL DEFAULT '0',
  `date_added` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `attachments_module` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='List the names and locations of files attached to cases';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}category_notifications`
--

CREATE TABLE `{prefix}category_notifications` (
  `category_notifications_id` int NOT NULL,
  `category_id` int NOT NULL,
  `user_id` int NOT NULL,
  `notify_new` int NOT NULL,
  `notify_change` int NOT NULL,
  `notify_close` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Category based user notifications';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}comments`
--

CREATE TABLE `{prefix}comments` (
  `comment_id` mediumint NOT NULL,
  `task_id` mediumint NOT NULL DEFAULT '0',
  `date_added` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_id` mediumint NOT NULL DEFAULT '0',
  `comment_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_spent` int DEFAULT NULL,
  `cost` int DEFAULT NULL,
  `date_created` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Case notes';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}companion`
--

CREATE TABLE `{prefix}companion` (
  `related_id` mediumint NOT NULL,
  `this_task` mediumint NOT NULL DEFAULT '0',
  `related_task` mediumint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Companion case links';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}custom_fields`
--

CREATE TABLE `{prefix}custom_fields` (
  `custom_field_id` int NOT NULL,
  `custom_field_definition_id` int NOT NULL,
  `task_id` int NOT NULL,
  `custom_field_value` longtext COLLATE utf8mb4_unicode_ci,
  `custom_field_old_value` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Custom field data for cases';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}custom_field_definitions`
--

CREATE TABLE `{prefix}custom_field_definitions` (
  `custom_field_definition_id` int NOT NULL,
  `custom_field_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_field_visible` int NOT NULL,
  `custom_field_type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Custom field definitions';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}custom_field_lists`
--

CREATE TABLE `{prefix}custom_field_lists` (
  `custom_field_list_id` int NOT NULL,
  `custom_field_definition_id` int NOT NULL,
  `custom_field_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `custom_field_order` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Custom field list items';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}custom_texts`
--

CREATE TABLE `{prefix}custom_texts` (
  `custom_text_id` int NOT NULL,
  `modify_action` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_text_lang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_text` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Custom texts for various actions';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}emailtemplates`
--

CREATE TABLE `{prefix}emailtemplates` (
  `template_id` int NOT NULL,
  `project_id` int NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `subject` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Email templates for various actions';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}groups`
--

CREATE TABLE `{prefix}groups` (
  `group_id` mediumint NOT NULL,
  `group_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `group_desc` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_admin` mediumint NOT NULL DEFAULT '0',
  `can_open_jobs` mediumint NOT NULL DEFAULT '0',
  `can_modify_jobs` mediumint NOT NULL DEFAULT '0',
  `can_add_comments` mediumint NOT NULL DEFAULT '0',
  `can_attach_files` mediumint NOT NULL DEFAULT '0',
  `can_vote` mediumint NOT NULL DEFAULT '0',
  `group_open` mediumint NOT NULL DEFAULT '0',
  `restrict_versions` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Define user groups for AttiCase';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}history`
--

CREATE TABLE `{prefix}history` (
  `history_id` mediumint NOT NULL,
  `task_id` mediumint NOT NULL DEFAULT '0',
  `user_id` mediumint NOT NULL DEFAULT '0',
  `event_date` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `event_type` mediumint NOT NULL DEFAULT '0',
  `field_changed` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='History of changes to cases';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}invoices`
--

CREATE TABLE `{prefix}invoices` (
  `invoice_id` int NOT NULL,
  `task_id` int NOT NULL DEFAULT '0',
  `time_ids` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_generated` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `minutes` int NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Compiled and printed invoices for cases';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}list_category`
--

CREATE TABLE `{prefix}list_category` (
  `category_id` mediumint NOT NULL,
  `project_id` mediumint NOT NULL DEFAULT '0',
  `category_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `category_descrip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `list_position` mediumint NOT NULL DEFAULT '0',
  `show_in_list` mediumint NOT NULL DEFAULT '0',
  `category_owner` mediumint NOT NULL DEFAULT '0',
  `parent_id` mediumint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Department list for AttiCase';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}list_os`
--

CREATE TABLE `{prefix}list_os` (
  `os_id` mediumint NOT NULL,
  `project_id` mediumint NOT NULL DEFAULT '0',
  `os_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `list_position` mediumint NOT NULL DEFAULT '0',
  `show_in_list` mediumint NOT NULL DEFAULT '0',
  `os_description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='DEPRECATED - List of operating systems for AttiCase';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}list_resolution`
--

CREATE TABLE `{prefix}list_resolution` (
  `resolution_id` mediumint NOT NULL,
  `resolution_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `list_position` mediumint NOT NULL DEFAULT '0',
  `show_in_list` mediumint NOT NULL DEFAULT '0',
  `resolution_description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='List of resolutions for cases';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}list_tasktype`
--

CREATE TABLE `{prefix}list_tasktype` (
  `tasktype_id` mediumint NOT NULL,
  `tasktype_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `list_position` mediumint NOT NULL DEFAULT '0',
  `show_in_list` mediumint NOT NULL DEFAULT '0',
  `tasktype_description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='List of case types for AttiCase';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}list_tasktype_groups`
--

CREATE TABLE `{prefix}list_tasktype_groups` (
  `tasktype_groups_id` int NOT NULL,
  `tasktype_groups_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` int NOT NULL,
  `list_position` int NOT NULL,
  `hide_from_list` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Case type groups for AttiCase';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}list_tasktype_groups_links`
--

CREATE TABLE `{prefix}list_tasktype_groups_links` (
  `id` int NOT NULL,
  `tasktype_groups_id` int NOT NULL,
  `tasktype_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Links between for case types and case groups';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}list_unit`
--

CREATE TABLE `{prefix}list_unit` (
  `unit_id` int NOT NULL,
  `project_id` int NOT NULL DEFAULT '0',
  `unit_descrip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `list_position` int NOT NULL DEFAULT '0',
  `show_in_list` int NOT NULL DEFAULT '0',
  `category_owner` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='List of workplace or department units for AttiCase';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}list_version`
--

CREATE TABLE `{prefix}list_version` (
  `version_id` mediumint NOT NULL,
  `project_id` mediumint NOT NULL DEFAULT '0',
  `version_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `list_position` mediumint NOT NULL DEFAULT '0',
  `show_in_list` mediumint NOT NULL DEFAULT '0',
  `version_tense` mediumint NOT NULL DEFAULT '0',
  `is_enquiry` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Case groups for AttiCase';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}master`
--

CREATE TABLE `{prefix}master` (
  `link_id` mediumint NOT NULL,
  `master_task` mediumint NOT NULL DEFAULT '0',
  `servant_task` mediumint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master case links';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}member_cache`
--

CREATE TABLE `{prefix}member_cache` (
  `member` int NOT NULL,
  `subs_paid_to` datetime NOT NULL,
  `paying_emp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `joined` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pref_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified` int NOT NULL,
  `primary_key` mediumtext COLLATE utf8mb4_unicode_ci,
  `data` longblob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Member or client database';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}noticeboard`
--

CREATE TABLE `{prefix}noticeboard` (
  `id` int NOT NULL,
  `publish_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `published` int DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int NOT NULL,
  `allow_comments` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Noticeboard entries';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}noticeboard_comments`
--

CREATE TABLE `{prefix}noticeboard_comments` (
  `id` int NOT NULL,
  `noticeboard_id` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `hide_comment` int NOT NULL DEFAULT '0',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Noticeboard comments';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}notifications`
--

CREATE TABLE `{prefix}notifications` (
  `notify_id` mediumint NOT NULL,
  `task_id` mediumint NOT NULL DEFAULT '0',
  `user_id` mediumint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Case user notifications';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}payments`
--

CREATE TABLE `{prefix}payments` (
  `payment_id` int NOT NULL,
  `task_id` int NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT '0',
  `date_received` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `notes` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Invoice/Billing payment records';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}people`
--

CREATE TABLE `{prefix}people` (
  `id` int NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organisation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='People of Interest database';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}people_of_interest`
--

CREATE TABLE `{prefix}people_of_interest` (
  `id` int NOT NULL,
  `person_id` int NOT NULL,
  `task_id` int NOT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Linking table between cases and people of interest';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}prefs`
--

CREATE TABLE `{prefix}prefs` (
  `pref_id` mediumint NOT NULL,
  `pref_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pref_value` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pref_desc` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `pref_group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AttiCase system preferences';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}projects`
--

CREATE TABLE `{prefix}projects` (
  `project_id` mediumint NOT NULL,
  `project_title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `theme_style` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `show_logo` mediumint NOT NULL DEFAULT '0',
  `inline_images` mediumint NOT NULL DEFAULT '0',
  `default_cat_owner` mediumint NOT NULL DEFAULT '0',
  `intro_message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_is_active` mediumint NOT NULL DEFAULT '0',
  `visible_columns` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_email_check` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='DEPRECATED - Project list for AttiCase';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}registrations`
--

CREATE TABLE `{prefix}registrations` (
  `reg_id` mediumint NOT NULL,
  `reg_time` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `confirm_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='DEPRECATED - Registration table for new users';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}related`
--

CREATE TABLE `{prefix}related` (
  `related_id` mediumint NOT NULL,
  `this_task` mediumint NOT NULL DEFAULT '0',
  `related_task` mediumint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Related case entries';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}reminders`
--

CREATE TABLE `{prefix}reminders` (
  `reminder_id` mediumint NOT NULL,
  `task_id` mediumint NOT NULL DEFAULT '0',
  `to_user_id` mediumint NOT NULL DEFAULT '0',
  `from_user_id` mediumint NOT NULL DEFAULT '0',
  `start_time` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `how_often` mediumint NOT NULL DEFAULT '0',
  `last_sent` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `reminder_message` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='DEPRECATED - Scheduled reminders about cases';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}reports`
--

CREATE TABLE `{prefix}reports` (
  `report_id` int NOT NULL,
  `report_category_id` int NOT NULL DEFAULT '0',
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `inputs` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `input_names` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `outputs` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `output_names` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sql` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='SQL report definitions';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}report_categories`
--

CREATE TABLE `{prefix}report_categories` (
  `report_category_id` int NOT NULL,
  `project_id` int NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Report categories';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}strategy`
--

CREATE TABLE `{prefix}strategy` (
  `strategy_id` int NOT NULL,
  `task_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `comment_date` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `comment` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `acknowledged_date` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `acknowledged` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Strategy comments for cases';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}tasks`
--

CREATE TABLE `{prefix}tasks` (
  `task_id` mediumint NOT NULL,
  `attached_to_project` mediumint NOT NULL DEFAULT '0',
  `task_type` mediumint NOT NULL DEFAULT '0',
  `date_opened` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date_due` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `opened_by` mediumint NOT NULL DEFAULT '0',
  `is_closed` mediumint NOT NULL DEFAULT '0',
  `date_closed` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `closed_by` mediumint NOT NULL DEFAULT '0',
  `closure_comment` longtext COLLATE utf8mb4_unicode_ci,
  `item_summary` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `detailed_desc` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_status` mediumint NOT NULL DEFAULT '0',
  `assigned_to` mediumint NOT NULL DEFAULT '0',
  `resolution_reason` mediumint NOT NULL DEFAULT '1',
  `product_category` mediumint NOT NULL DEFAULT '0',
  `product_version` mediumint NOT NULL DEFAULT '0',
  `closedby_version` mediumint NOT NULL DEFAULT '0',
  `operating_system` mediumint NOT NULL DEFAULT '0',
  `task_severity` mediumint NOT NULL DEFAULT '0',
  `task_priority` mediumint NOT NULL DEFAULT '0',
  `last_edited_by` mediumint NOT NULL DEFAULT '0',
  `last_edited_time` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `percent_complete` mediumint NOT NULL DEFAULT '0',
  `member` int NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` mediumtext COLLATE utf8mb4_unicode_ci,
  `line_manager` mediumtext COLLATE utf8mb4_unicode_ci,
  `line_manager_ph` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `local_delegate` mediumtext COLLATE utf8mb4_unicode_ci,
  `local_delegate_ph` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `resolution_sought` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_restricted` mediumint NOT NULL DEFAULT '0',
  `closure_checklist` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `member_is_delegate` smallint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Case details';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}tasktype_checked`
--

CREATE TABLE `{prefix}tasktype_checked` (
  `checked_id` int NOT NULL,
  `checklist_id` int NOT NULL DEFAULT '0',
  `task_id` int NOT NULL DEFAULT '0',
  `date_checked` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Record of checklist items checked off for cases';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}tasktype_checklist`
--

CREATE TABLE `{prefix}tasktype_checklist` (
  `checklist_id` int NOT NULL,
  `tasktype_id` int NOT NULL DEFAULT '0',
  `item` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Special checks that are associated with different tasktypes';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}times`
--

CREATE TABLE `{prefix}times` (
  `time_id` int NOT NULL,
  `task_id` int DEFAULT NULL,
  `time` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `user_id` int DEFAULT NULL,
  `invoiced` int UNSIGNED DEFAULT '0',
  `invoice_date` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Timekeeping';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}users`
--

CREATE TABLE `{prefix}users` (
  `user_id` mediumint NOT NULL,
  `user_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_pass` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `real_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `group_in` mediumint NOT NULL DEFAULT '0',
  `jabber_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email_address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `notify_type` mediumint NOT NULL DEFAULT '0',
  `account_enabled` mediumint NOT NULL DEFAULT '0',
  `dateformat` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `dateformat_extended` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `default_task_view` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `default_version` int NOT NULL DEFAULT '0',
  `strategy_enabled` mediumint DEFAULT '0',
  `email_moderator` int NOT NULL DEFAULT '0',
  `self_notify` int NOT NULL DEFAULT '0',
  `notify_rate` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'D',
  `last_notice` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AttiCase user list';

-- --------------------------------------------------------

--
-- Table structure for table `{prefix}version_permissions`
--

CREATE TABLE `{prefix}version_permissions` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `version_id` int NOT NULL,
  `enabled` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='DEPRECATED -  Version permissions for user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `{prefix}attachments`
--
ALTER TABLE `{prefix}attachments`
  ADD PRIMARY KEY (`attachment_id`),
  ADD KEY `task_id-idx` (`task_id`) USING BTREE;

--
-- Indexes for table `{prefix}category_notifications`
--
ALTER TABLE `{prefix}category_notifications`
  ADD PRIMARY KEY (`category_notifications_id`);

--
-- Indexes for table `{prefix}comments`
--
ALTER TABLE `{prefix}comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `task_id-idx` (`task_id`) USING BTREE;

--
-- Indexes for table `{prefix}companion`
--
ALTER TABLE `{prefix}companion`
  ADD PRIMARY KEY (`related_id`);

--
-- Indexes for table `{prefix}custom_fields`
--
ALTER TABLE `{prefix}custom_fields`
  ADD PRIMARY KEY (`custom_field_id`);

--
-- Indexes for table `{prefix}custom_field_definitions`
--
ALTER TABLE `{prefix}custom_field_definitions`
  ADD PRIMARY KEY (`custom_field_definition_id`);

--
-- Indexes for table `{prefix}custom_field_lists`
--
ALTER TABLE `{prefix}custom_field_lists`
  ADD PRIMARY KEY (`custom_field_list_id`);

--
-- Indexes for table `{prefix}custom_texts`
--
ALTER TABLE `{prefix}custom_texts`
  ADD PRIMARY KEY (`custom_text_id`);

--
-- Indexes for table `{prefix}emailtemplates`
--
ALTER TABLE `{prefix}emailtemplates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `{prefix}groups`
--
ALTER TABLE `{prefix}groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `{prefix}history`
--
ALTER TABLE `{prefix}history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `event_date` (`event_date`),
  ADD KEY `task_id-idx` (`task_id`) USING BTREE;

--
-- Indexes for table `{prefix}invoices`
--
ALTER TABLE `{prefix}invoices`
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `{prefix}list_category`
--
ALTER TABLE `{prefix}list_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `{prefix}list_os`
--
ALTER TABLE `{prefix}list_os`
  ADD PRIMARY KEY (`os_id`);

--
-- Indexes for table `{prefix}list_resolution`
--
ALTER TABLE `{prefix}list_resolution`
  ADD PRIMARY KEY (`resolution_id`);

--
-- Indexes for table `{prefix}list_tasktype`
--
ALTER TABLE `{prefix}list_tasktype`
  ADD PRIMARY KEY (`tasktype_id`);

--
-- Indexes for table `{prefix}list_tasktype_groups`
--
ALTER TABLE `{prefix}list_tasktype_groups`
  ADD PRIMARY KEY (`tasktype_groups_id`);

--
-- Indexes for table `{prefix}list_tasktype_groups_links`
--
ALTER TABLE `{prefix}list_tasktype_groups_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasktype_group_id` (`tasktype_groups_id`,`tasktype_id`);

--
-- Indexes for table `{prefix}list_unit`
--
ALTER TABLE `{prefix}list_unit`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `{prefix}list_version`
--
ALTER TABLE `{prefix}list_version`
  ADD PRIMARY KEY (`version_id`);

--
-- Indexes for table `{prefix}master`
--
ALTER TABLE `{prefix}master`
  ADD PRIMARY KEY (`link_id`);

--
-- Indexes for table `{prefix}member_cache`
--
ALTER TABLE `{prefix}member_cache`
  ADD PRIMARY KEY (`member`);

--
-- Indexes for table `{prefix}noticeboard`
--
ALTER TABLE `{prefix}noticeboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{prefix}noticeboard_comments`
--
ALTER TABLE `{prefix}noticeboard_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{prefix}notifications`
--
ALTER TABLE `{prefix}notifications`
  ADD PRIMARY KEY (`notify_id`);

--
-- Indexes for table `{prefix}payments`
--
ALTER TABLE `{prefix}payments`
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `{prefix}people`
--
ALTER TABLE `{prefix}people`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{prefix}people_of_interest`
--
ALTER TABLE `{prefix}people_of_interest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `{prefix}prefs`
--
ALTER TABLE `{prefix}prefs`
  ADD PRIMARY KEY (`pref_id`);

--
-- Indexes for table `{prefix}projects`
--
ALTER TABLE `{prefix}projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `{prefix}registrations`
--
ALTER TABLE `{prefix}registrations`
  ADD PRIMARY KEY (`reg_id`);

--
-- Indexes for table `{prefix}related`
--
ALTER TABLE `{prefix}related`
  ADD PRIMARY KEY (`related_id`);

--
-- Indexes for table `{prefix}reminders`
--
ALTER TABLE `{prefix}reminders`
  ADD PRIMARY KEY (`reminder_id`);

--
-- Indexes for table `{prefix}reports`
--
ALTER TABLE `{prefix}reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `{prefix}report_categories`
--
ALTER TABLE `{prefix}report_categories`
  ADD PRIMARY KEY (`report_category_id`);

--
-- Indexes for table `{prefix}strategy`
--
ALTER TABLE `{prefix}strategy`
  ADD PRIMARY KEY (`strategy_id`);

--
-- Indexes for table `{prefix}tasks`
--
ALTER TABLE `{prefix}tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `member-idx` (`member`) USING BTREE,
  ADD KEY `member-is_closed-idx` (`member`,`is_closed`) USING BTREE,
  ADD KEY `assigned_to-idx` (`assigned_to`) USING BTREE,
  ADD KEY `assigned_to-date_due-is_closed-idx` (`assigned_to`,`date_due`,`is_closed`) USING BTREE;

--
-- Indexes for table `{prefix}tasktype_checked`
--
ALTER TABLE `{prefix}tasktype_checked`
  ADD PRIMARY KEY (`checked_id`);

--
-- Indexes for table `{prefix}tasktype_checklist`
--
ALTER TABLE `{prefix}tasktype_checklist`
  ADD PRIMARY KEY (`checklist_id`);

--
-- Indexes for table `{prefix}times`
--
ALTER TABLE `{prefix}times`
  ADD PRIMARY KEY (`time_id`);

--
-- Indexes for table `{prefix}users`
--
ALTER TABLE `{prefix}users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `{prefix}version_permissions`
--
ALTER TABLE `{prefix}version_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `{prefix}attachments`
--
ALTER TABLE `{prefix}attachments`
  MODIFY `attachment_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}category_notifications`
--
ALTER TABLE `{prefix}category_notifications`
  MODIFY `category_notifications_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}comments`
--
ALTER TABLE `{prefix}comments`
  MODIFY `comment_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}companion`
--
ALTER TABLE `{prefix}companion`
  MODIFY `related_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}custom_fields`
--
ALTER TABLE `{prefix}custom_fields`
  MODIFY `custom_field_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}custom_field_definitions`
--
ALTER TABLE `{prefix}custom_field_definitions`
  MODIFY `custom_field_definition_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}custom_field_lists`
--
ALTER TABLE `{prefix}custom_field_lists`
  MODIFY `custom_field_list_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}custom_texts`
--
ALTER TABLE `{prefix}custom_texts`
  MODIFY `custom_text_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}emailtemplates`
--
ALTER TABLE `{prefix}emailtemplates`
  MODIFY `template_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}groups`
--
ALTER TABLE `{prefix}groups`
  MODIFY `group_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}history`
--
ALTER TABLE `{prefix}history`
  MODIFY `history_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}invoices`
--
ALTER TABLE `{prefix}invoices`
  MODIFY `invoice_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}list_category`
--
ALTER TABLE `{prefix}list_category`
  MODIFY `category_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}list_os`
--
ALTER TABLE `{prefix}list_os`
  MODIFY `os_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}list_resolution`
--
ALTER TABLE `{prefix}list_resolution`
  MODIFY `resolution_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}list_tasktype`
--
ALTER TABLE `{prefix}list_tasktype`
  MODIFY `tasktype_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}list_tasktype_groups`
--
ALTER TABLE `{prefix}list_tasktype_groups`
  MODIFY `tasktype_groups_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}list_tasktype_groups_links`
--
ALTER TABLE `{prefix}list_tasktype_groups_links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}list_unit`
--
ALTER TABLE `{prefix}list_unit`
  MODIFY `unit_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}list_version`
--
ALTER TABLE `{prefix}list_version`
  MODIFY `version_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}master`
--
ALTER TABLE `{prefix}master`
  MODIFY `link_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}noticeboard`
--
ALTER TABLE `{prefix}noticeboard`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}noticeboard_comments`
--
ALTER TABLE `{prefix}noticeboard_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}notifications`
--
ALTER TABLE `{prefix}notifications`
  MODIFY `notify_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}payments`
--
ALTER TABLE `{prefix}payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}people`
--
ALTER TABLE `{prefix}people`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}people_of_interest`
--
ALTER TABLE `{prefix}people_of_interest`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}prefs`
--
ALTER TABLE `{prefix}prefs`
  MODIFY `pref_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}projects`
--
ALTER TABLE `{prefix}projects`
  MODIFY `project_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}registrations`
--
ALTER TABLE `{prefix}registrations`
  MODIFY `reg_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}related`
--
ALTER TABLE `{prefix}related`
  MODIFY `related_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}reminders`
--
ALTER TABLE `{prefix}reminders`
  MODIFY `reminder_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}reports`
--
ALTER TABLE `{prefix}reports`
  MODIFY `report_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}report_categories`
--
ALTER TABLE `{prefix}report_categories`
  MODIFY `report_category_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}strategy`
--
ALTER TABLE `{prefix}strategy`
  MODIFY `strategy_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}tasks`
--
ALTER TABLE `{prefix}tasks`
  MODIFY `task_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}tasktype_checked`
--
ALTER TABLE `{prefix}tasktype_checked`
  MODIFY `checked_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}tasktype_checklist`
--
ALTER TABLE `{prefix}tasktype_checklist`
  MODIFY `checklist_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}times`
--
ALTER TABLE `{prefix}times`
  MODIFY `time_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}users`
--
ALTER TABLE `{prefix}users`
  MODIFY `user_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `{prefix}version_permissions`
--
ALTER TABLE `{prefix}version_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

