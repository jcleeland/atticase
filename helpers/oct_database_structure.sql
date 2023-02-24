-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 22, 2023 at 04:48 PM
-- Server version: 8.0.32-0ubuntu0.22.04.2
-- PHP Version: 8.1.2-1ubuntu2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `casetracker3`
--

-- --------------------------------------------------------

--
-- Table structure for table `oct_attachments`
--

CREATE TABLE `oct_attachments` (
  `attachment_id` mediumint NOT NULL,
  `task_id` mediumint NOT NULL DEFAULT '0',
  `orig_name` varchar(255) NOT NULL DEFAULT '',
  `file_name` varchar(30) NOT NULL DEFAULT '',
  `file_desc` varchar(100) NOT NULL DEFAULT '',
  `file_type` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
  `file_size` mediumint NOT NULL DEFAULT '0',
  `file_date` varchar(12) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `added_by` mediumint NOT NULL DEFAULT '0',
  `date_added` varchar(12) NOT NULL DEFAULT '',
  `attachments_module` varchar(20) NOT NULL,
  `url` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List the names and locations of files attached to tasks';

-- --------------------------------------------------------

--
-- Table structure for table `oct_category_notifications`
--

CREATE TABLE `oct_category_notifications` (
  `category_notifications_id` int NOT NULL,
  `category_id` int NOT NULL,
  `user_id` int NOT NULL,
  `notify_new` int NOT NULL,
  `notify_change` int NOT NULL,
  `notify_close` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_comments`
--

CREATE TABLE `oct_comments` (
  `comment_id` mediumint NOT NULL,
  `task_id` mediumint NOT NULL DEFAULT '0',
  `date_added` varchar(12) NOT NULL DEFAULT '',
  `user_id` mediumint NOT NULL DEFAULT '0',
  `comment_text` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_companion`
--

CREATE TABLE `oct_companion` (
  `related_id` mediumint NOT NULL,
  `this_task` mediumint NOT NULL DEFAULT '0',
  `related_task` mediumint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Related task entries';

-- --------------------------------------------------------

--
-- Table structure for table `oct_custom_fields`
--

CREATE TABLE `oct_custom_fields` (
  `custom_field_id` int NOT NULL,
  `custom_field_definition_id` int NOT NULL,
  `task_id` int NOT NULL,
  `custom_field_value` longtext,
  `custom_field_old_value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_custom_field_definitions`
--

CREATE TABLE `oct_custom_field_definitions` (
  `custom_field_definition_id` int NOT NULL,
  `custom_field_name` varchar(50) NOT NULL,
  `custom_field_visible` int NOT NULL,
  `custom_field_type` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_custom_texts`
--

CREATE TABLE `oct_custom_texts` (
  `custom_text_id` int NOT NULL,
  `modify_action` varchar(30) NOT NULL,
  `custom_text_lang` varchar(10) NOT NULL,
  `custom_text` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_emailtemplates`
--

CREATE TABLE `oct_emailtemplates` (
  `template_id` int NOT NULL,
  `project_id` int NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_groups`
--

CREATE TABLE `oct_groups` (
  `group_id` mediumint NOT NULL,
  `group_name` varchar(20) NOT NULL DEFAULT '',
  `group_desc` varchar(150) NOT NULL DEFAULT '',
  `is_admin` mediumint NOT NULL DEFAULT '0',
  `can_open_jobs` mediumint NOT NULL DEFAULT '0',
  `can_modify_jobs` mediumint NOT NULL DEFAULT '0',
  `can_add_comments` mediumint NOT NULL DEFAULT '0',
  `can_attach_files` mediumint NOT NULL DEFAULT '0',
  `can_vote` mediumint NOT NULL DEFAULT '0',
  `group_open` mediumint NOT NULL DEFAULT '0',
  `restrict_versions` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='User Groups for the Flyspray bug killer';

-- --------------------------------------------------------

--
-- Table structure for table `oct_history`
--

CREATE TABLE `oct_history` (
  `history_id` mediumint NOT NULL,
  `task_id` mediumint NOT NULL DEFAULT '0',
  `user_id` mediumint NOT NULL DEFAULT '0',
  `event_date` varchar(12) NOT NULL DEFAULT '',
  `event_type` mediumint NOT NULL DEFAULT '0',
  `field_changed` text NOT NULL,
  `old_value` text NOT NULL,
  `new_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_invoices`
--

CREATE TABLE `oct_invoices` (
  `invoice_id` int NOT NULL,
  `task_id` int NOT NULL DEFAULT '0',
  `time_ids` text NOT NULL,
  `date_generated` varchar(12) NOT NULL DEFAULT '',
  `minutes` int NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_list_category`
--

CREATE TABLE `oct_list_category` (
  `category_id` mediumint NOT NULL,
  `project_id` mediumint NOT NULL DEFAULT '0',
  `category_name` varchar(30) NOT NULL DEFAULT '',
  `category_descrip` varchar(255) NOT NULL DEFAULT '',
  `list_position` mediumint NOT NULL DEFAULT '0',
  `show_in_list` mediumint NOT NULL DEFAULT '0',
  `category_owner` mediumint NOT NULL DEFAULT '0',
  `parent_id` mediumint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_list_os`
--

CREATE TABLE `oct_list_os` (
  `os_id` mediumint NOT NULL,
  `project_id` mediumint NOT NULL DEFAULT '0',
  `os_name` varchar(20) NOT NULL DEFAULT '',
  `list_position` mediumint NOT NULL DEFAULT '0',
  `show_in_list` mediumint NOT NULL DEFAULT '0',
  `os_description` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Operating system list for the Flyspray bug killer';

-- --------------------------------------------------------

--
-- Table structure for table `oct_list_resolution`
--

CREATE TABLE `oct_list_resolution` (
  `resolution_id` mediumint NOT NULL,
  `resolution_name` varchar(60) NOT NULL DEFAULT '',
  `list_position` mediumint NOT NULL DEFAULT '0',
  `show_in_list` mediumint NOT NULL DEFAULT '0',
  `resolution_description` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_list_tasktype`
--

CREATE TABLE `oct_list_tasktype` (
  `tasktype_id` mediumint NOT NULL,
  `tasktype_name` varchar(60) NOT NULL DEFAULT '',
  `list_position` mediumint NOT NULL DEFAULT '0',
  `show_in_list` mediumint NOT NULL DEFAULT '0',
  `tasktype_description` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of task types for Flyspray the bug killer.';

-- --------------------------------------------------------

--
-- Table structure for table `oct_list_tasktype_groups`
--

CREATE TABLE `oct_list_tasktype_groups` (
  `tasktype_groups_id` int NOT NULL,
  `tasktype_groups_name` varchar(100) NOT NULL,
  `owner_id` int NOT NULL,
  `list_position` int NOT NULL,
  `hide_from_list` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_list_tasktype_groups_links`
--

CREATE TABLE `oct_list_tasktype_groups_links` (
  `id` int NOT NULL,
  `tasktype_groups_id` int NOT NULL,
  `tasktype_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_list_unit`
--

CREATE TABLE `oct_list_unit` (
  `unit_id` int NOT NULL,
  `project_id` int NOT NULL DEFAULT '0',
  `unit_descrip` varchar(255) NOT NULL,
  `list_position` int NOT NULL DEFAULT '0',
  `show_in_list` int NOT NULL DEFAULT '0',
  `category_owner` int NOT NULL DEFAULT '0',
  `parent_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_list_version`
--

CREATE TABLE `oct_list_version` (
  `version_id` mediumint NOT NULL,
  `project_id` mediumint NOT NULL DEFAULT '0',
  `version_name` varchar(20) NOT NULL DEFAULT '',
  `list_position` mediumint NOT NULL DEFAULT '0',
  `show_in_list` mediumint NOT NULL DEFAULT '0',
  `version_tense` mediumint NOT NULL DEFAULT '0',
  `is_enquiry` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_master`
--

CREATE TABLE `oct_master` (
  `link_id` mediumint NOT NULL,
  `master_task` mediumint NOT NULL DEFAULT '0',
  `servant_task` mediumint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Master task entries';

-- --------------------------------------------------------

--
-- Table structure for table `oct_member_cache`
--

CREATE TABLE `oct_member_cache` (
  `member` int NOT NULL,
  `subs_paid_to` datetime NOT NULL,
  `paying_emp` varchar(255) NOT NULL,
  `joined` varchar(20) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `pref_name` varchar(255) NOT NULL,
  `modified` int NOT NULL,
  `primary_key` text,
  `data` longblob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_notifications`
--

CREATE TABLE `oct_notifications` (
  `notify_id` mediumint NOT NULL,
  `task_id` mediumint NOT NULL DEFAULT '0',
  `user_id` mediumint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Extra task notifications are stored here';

-- --------------------------------------------------------

--
-- Table structure for table `oct_payments`
--

CREATE TABLE `oct_payments` (
  `payment_id` int NOT NULL,
  `task_id` int NOT NULL DEFAULT '0',
  `amount` float NOT NULL DEFAULT '0',
  `date_received` varchar(12) NOT NULL DEFAULT '0',
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_people`
--

CREATE TABLE `oct_people` (
  `id` int NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `organisation` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` varchar(12) NOT NULL,
  `modified` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_people_of_interest`
--

CREATE TABLE `oct_people_of_interest` (
  `id` int NOT NULL,
  `person_id` int NOT NULL,
  `task_id` int NOT NULL,
  `comment` text NOT NULL,
  `created` varchar(12) NOT NULL,
  `modified` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_prefs`
--

CREATE TABLE `oct_prefs` (
  `pref_id` mediumint NOT NULL,
  `pref_name` varchar(20) NOT NULL DEFAULT '',
  `pref_value` varchar(100) NOT NULL DEFAULT '',
  `pref_desc` varchar(100) NOT NULL DEFAULT '',
  `pref_group` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Application preferences are set here';

-- --------------------------------------------------------

--
-- Table structure for table `oct_projects`
--

CREATE TABLE `oct_projects` (
  `project_id` mediumint NOT NULL,
  `project_title` varchar(100) NOT NULL DEFAULT '',
  `theme_style` varchar(20) NOT NULL DEFAULT '0',
  `show_logo` mediumint NOT NULL DEFAULT '0',
  `inline_images` mediumint NOT NULL DEFAULT '0',
  `default_cat_owner` mediumint NOT NULL DEFAULT '0',
  `intro_message` longtext NOT NULL,
  `project_is_active` mediumint NOT NULL DEFAULT '0',
  `visible_columns` varchar(255) NOT NULL DEFAULT '',
  `last_email_check` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Details on multiple Flyspray projects';

-- --------------------------------------------------------

--
-- Table structure for table `oct_registrations`
--

CREATE TABLE `oct_registrations` (
  `reg_id` mediumint NOT NULL,
  `reg_time` varchar(12) NOT NULL DEFAULT '',
  `confirm_code` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Storage for new user registration confirmation codes';

-- --------------------------------------------------------

--
-- Table structure for table `oct_related`
--

CREATE TABLE `oct_related` (
  `related_id` mediumint NOT NULL,
  `this_task` mediumint NOT NULL DEFAULT '0',
  `related_task` mediumint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Related task entries';

-- --------------------------------------------------------

--
-- Table structure for table `oct_reminders`
--

CREATE TABLE `oct_reminders` (
  `reminder_id` mediumint NOT NULL,
  `task_id` mediumint NOT NULL DEFAULT '0',
  `to_user_id` mediumint NOT NULL DEFAULT '0',
  `from_user_id` mediumint NOT NULL DEFAULT '0',
  `start_time` varchar(12) NOT NULL DEFAULT '0',
  `how_often` mediumint NOT NULL DEFAULT '0',
  `last_sent` varchar(12) NOT NULL DEFAULT '0',
  `reminder_message` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Scheduled reminders about tasks';

-- --------------------------------------------------------

--
-- Table structure for table `oct_reports`
--

CREATE TABLE `oct_reports` (
  `report_id` int NOT NULL,
  `report_category_id` int NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `inputs` text NOT NULL,
  `input_names` text NOT NULL,
  `outputs` text NOT NULL,
  `output_names` text NOT NULL,
  `sql` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_report_categories`
--

CREATE TABLE `oct_report_categories` (
  `report_category_id` int NOT NULL,
  `project_id` int NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_strategy`
--

CREATE TABLE `oct_strategy` (
  `strategy_id` int NOT NULL,
  `task_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `comment_date` varchar(12) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `acknowledged_date` varchar(12) NOT NULL DEFAULT '',
  `acknowledged` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_tasks`
--

CREATE TABLE `oct_tasks` (
  `task_id` mediumint NOT NULL,
  `attached_to_project` mediumint NOT NULL DEFAULT '0',
  `task_type` mediumint NOT NULL DEFAULT '0',
  `date_opened` varchar(12) NOT NULL DEFAULT '',
  `date_due` varchar(12) NOT NULL DEFAULT '',
  `opened_by` mediumint NOT NULL DEFAULT '0',
  `is_closed` mediumint NOT NULL DEFAULT '0',
  `date_closed` varchar(12) NOT NULL DEFAULT '',
  `closed_by` mediumint NOT NULL DEFAULT '0',
  `closure_comment` longtext,
  `item_summary` varchar(100) NOT NULL DEFAULT '',
  `detailed_desc` longtext NOT NULL,
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
  `last_edited_time` varchar(12) NOT NULL DEFAULT '0',
  `percent_complete` mediumint NOT NULL DEFAULT '0',
  `member` int NOT NULL DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  `unit` text,
  `line_manager` text,
  `line_manager_ph` varchar(20) DEFAULT '',
  `local_delegate` text,
  `local_delegate_ph` varchar(20) DEFAULT '',
  `resolution_sought` text,
  `is_restricted` mediumint NOT NULL DEFAULT '0',
  `closure_checklist` varchar(10) NOT NULL DEFAULT '',
  `member_is_delegate` smallint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Bugs and feature requests for the Flyspray bug killer';

-- --------------------------------------------------------

--
-- Table structure for table `oct_tasktype_checked`
--

CREATE TABLE `oct_tasktype_checked` (
  `checked_id` int NOT NULL,
  `checklist_id` int NOT NULL DEFAULT '0',
  `task_id` int NOT NULL DEFAULT '0',
  `date_checked` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_tasktype_checklist`
--

CREATE TABLE `oct_tasktype_checklist` (
  `checklist_id` int NOT NULL,
  `tasktype_id` int NOT NULL DEFAULT '0',
  `item` text NOT NULL,
  `created` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `oct_times`
--

CREATE TABLE `oct_times` (
  `time_id` int NOT NULL,
  `task_id` int DEFAULT NULL,
  `time` varchar(12) DEFAULT NULL,
  `date` varchar(12) DEFAULT NULL,
  `description` text,
  `user_id` int DEFAULT NULL,
  `invoiced` int UNSIGNED DEFAULT '0',
  `invoice_date` varchar(12) DEFAULT NULL,
  `rate` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Timekeeping';

-- --------------------------------------------------------

--
-- Table structure for table `oct_users`
--

CREATE TABLE `oct_users` (
  `user_id` mediumint NOT NULL,
  `user_name` varchar(20) NOT NULL DEFAULT '',
  `user_pass` varchar(30) NOT NULL DEFAULT '',
  `real_name` varchar(100) NOT NULL DEFAULT '',
  `group_in` mediumint NOT NULL DEFAULT '0',
  `jabber_id` varchar(100) NOT NULL DEFAULT '',
  `email_address` varchar(100) NOT NULL DEFAULT '',
  `notify_type` mediumint NOT NULL DEFAULT '0',
  `account_enabled` mediumint NOT NULL DEFAULT '0',
  `dateformat` varchar(30) NOT NULL DEFAULT '',
  `dateformat_extended` varchar(30) NOT NULL DEFAULT '',
  `default_task_view` varchar(15) NOT NULL DEFAULT '',
  `default_version` int NOT NULL DEFAULT '0',
  `strategy_enabled` mediumint DEFAULT '0',
  `email_moderator` int NOT NULL DEFAULT '0',
  `self_notify` int NOT NULL DEFAULT '0',
  `notify_rate` varchar(1) NOT NULL DEFAULT 'D',
  `last_notice` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Users for the Flyspray bug killer';

-- --------------------------------------------------------

--
-- Table structure for table `oct_version_permissions`
--

CREATE TABLE `oct_version_permissions` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `version_id` int NOT NULL,
  `enabled` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oct_attachments`
--
ALTER TABLE `oct_attachments`
  ADD PRIMARY KEY (`attachment_id`);

--
-- Indexes for table `oct_category_notifications`
--
ALTER TABLE `oct_category_notifications`
  ADD PRIMARY KEY (`category_notifications_id`);

--
-- Indexes for table `oct_comments`
--
ALTER TABLE `oct_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `oct_companion`
--
ALTER TABLE `oct_companion`
  ADD PRIMARY KEY (`related_id`);

--
-- Indexes for table `oct_custom_fields`
--
ALTER TABLE `oct_custom_fields`
  ADD PRIMARY KEY (`custom_field_id`);

--
-- Indexes for table `oct_custom_field_definitions`
--
ALTER TABLE `oct_custom_field_definitions`
  ADD PRIMARY KEY (`custom_field_definition_id`);

--
-- Indexes for table `oct_custom_texts`
--
ALTER TABLE `oct_custom_texts`
  ADD PRIMARY KEY (`custom_text_id`);

--
-- Indexes for table `oct_emailtemplates`
--
ALTER TABLE `oct_emailtemplates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `oct_groups`
--
ALTER TABLE `oct_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `oct_history`
--
ALTER TABLE `oct_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `event_date` (`event_date`);

--
-- Indexes for table `oct_invoices`
--
ALTER TABLE `oct_invoices`
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `oct_list_category`
--
ALTER TABLE `oct_list_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `oct_list_os`
--
ALTER TABLE `oct_list_os`
  ADD PRIMARY KEY (`os_id`);

--
-- Indexes for table `oct_list_resolution`
--
ALTER TABLE `oct_list_resolution`
  ADD PRIMARY KEY (`resolution_id`);

--
-- Indexes for table `oct_list_tasktype`
--
ALTER TABLE `oct_list_tasktype`
  ADD PRIMARY KEY (`tasktype_id`);

--
-- Indexes for table `oct_list_tasktype_groups`
--
ALTER TABLE `oct_list_tasktype_groups`
  ADD PRIMARY KEY (`tasktype_groups_id`);

--
-- Indexes for table `oct_list_tasktype_groups_links`
--
ALTER TABLE `oct_list_tasktype_groups_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasktype_group_id` (`tasktype_groups_id`,`tasktype_id`);

--
-- Indexes for table `oct_list_unit`
--
ALTER TABLE `oct_list_unit`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `oct_list_version`
--
ALTER TABLE `oct_list_version`
  ADD PRIMARY KEY (`version_id`);

--
-- Indexes for table `oct_master`
--
ALTER TABLE `oct_master`
  ADD PRIMARY KEY (`link_id`);

--
-- Indexes for table `oct_member_cache`
--
ALTER TABLE `oct_member_cache`
  ADD PRIMARY KEY (`member`);

--
-- Indexes for table `oct_notifications`
--
ALTER TABLE `oct_notifications`
  ADD PRIMARY KEY (`notify_id`);

--
-- Indexes for table `oct_payments`
--
ALTER TABLE `oct_payments`
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `oct_people`
--
ALTER TABLE `oct_people`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oct_people_of_interest`
--
ALTER TABLE `oct_people_of_interest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oct_prefs`
--
ALTER TABLE `oct_prefs`
  ADD PRIMARY KEY (`pref_id`);

--
-- Indexes for table `oct_projects`
--
ALTER TABLE `oct_projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `oct_registrations`
--
ALTER TABLE `oct_registrations`
  ADD PRIMARY KEY (`reg_id`);

--
-- Indexes for table `oct_related`
--
ALTER TABLE `oct_related`
  ADD PRIMARY KEY (`related_id`);

--
-- Indexes for table `oct_reminders`
--
ALTER TABLE `oct_reminders`
  ADD PRIMARY KEY (`reminder_id`);

--
-- Indexes for table `oct_reports`
--
ALTER TABLE `oct_reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `oct_report_categories`
--
ALTER TABLE `oct_report_categories`
  ADD PRIMARY KEY (`report_category_id`);

--
-- Indexes for table `oct_strategy`
--
ALTER TABLE `oct_strategy`
  ADD PRIMARY KEY (`strategy_id`);

--
-- Indexes for table `oct_tasks`
--
ALTER TABLE `oct_tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `oct_tasktype_checked`
--
ALTER TABLE `oct_tasktype_checked`
  ADD PRIMARY KEY (`checked_id`);

--
-- Indexes for table `oct_tasktype_checklist`
--
ALTER TABLE `oct_tasktype_checklist`
  ADD PRIMARY KEY (`checklist_id`);

--
-- Indexes for table `oct_times`
--
ALTER TABLE `oct_times`
  ADD PRIMARY KEY (`time_id`);

--
-- Indexes for table `oct_users`
--
ALTER TABLE `oct_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `oct_version_permissions`
--
ALTER TABLE `oct_version_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oct_attachments`
--
ALTER TABLE `oct_attachments`
  MODIFY `attachment_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_category_notifications`
--
ALTER TABLE `oct_category_notifications`
  MODIFY `category_notifications_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_comments`
--
ALTER TABLE `oct_comments`
  MODIFY `comment_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_companion`
--
ALTER TABLE `oct_companion`
  MODIFY `related_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_custom_fields`
--
ALTER TABLE `oct_custom_fields`
  MODIFY `custom_field_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_custom_field_definitions`
--
ALTER TABLE `oct_custom_field_definitions`
  MODIFY `custom_field_definition_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_custom_texts`
--
ALTER TABLE `oct_custom_texts`
  MODIFY `custom_text_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_emailtemplates`
--
ALTER TABLE `oct_emailtemplates`
  MODIFY `template_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_groups`
--
ALTER TABLE `oct_groups`
  MODIFY `group_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_history`
--
ALTER TABLE `oct_history`
  MODIFY `history_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_invoices`
--
ALTER TABLE `oct_invoices`
  MODIFY `invoice_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_list_category`
--
ALTER TABLE `oct_list_category`
  MODIFY `category_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_list_os`
--
ALTER TABLE `oct_list_os`
  MODIFY `os_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_list_resolution`
--
ALTER TABLE `oct_list_resolution`
  MODIFY `resolution_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_list_tasktype`
--
ALTER TABLE `oct_list_tasktype`
  MODIFY `tasktype_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_list_tasktype_groups`
--
ALTER TABLE `oct_list_tasktype_groups`
  MODIFY `tasktype_groups_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_list_tasktype_groups_links`
--
ALTER TABLE `oct_list_tasktype_groups_links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_list_unit`
--
ALTER TABLE `oct_list_unit`
  MODIFY `unit_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_list_version`
--
ALTER TABLE `oct_list_version`
  MODIFY `version_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_master`
--
ALTER TABLE `oct_master`
  MODIFY `link_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_notifications`
--
ALTER TABLE `oct_notifications`
  MODIFY `notify_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_payments`
--
ALTER TABLE `oct_payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_people`
--
ALTER TABLE `oct_people`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_people_of_interest`
--
ALTER TABLE `oct_people_of_interest`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_prefs`
--
ALTER TABLE `oct_prefs`
  MODIFY `pref_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_projects`
--
ALTER TABLE `oct_projects`
  MODIFY `project_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_registrations`
--
ALTER TABLE `oct_registrations`
  MODIFY `reg_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_related`
--
ALTER TABLE `oct_related`
  MODIFY `related_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_reminders`
--
ALTER TABLE `oct_reminders`
  MODIFY `reminder_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_reports`
--
ALTER TABLE `oct_reports`
  MODIFY `report_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_report_categories`
--
ALTER TABLE `oct_report_categories`
  MODIFY `report_category_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_strategy`
--
ALTER TABLE `oct_strategy`
  MODIFY `strategy_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_tasks`
--
ALTER TABLE `oct_tasks`
  MODIFY `task_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_tasktype_checked`
--
ALTER TABLE `oct_tasktype_checked`
  MODIFY `checked_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_tasktype_checklist`
--
ALTER TABLE `oct_tasktype_checklist`
  MODIFY `checklist_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_times`
--
ALTER TABLE `oct_times`
  MODIFY `time_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_users`
--
ALTER TABLE `oct_users`
  MODIFY `user_id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oct_version_permissions`
--
ALTER TABLE `oct_version_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;