<?php
/*
 * Copyright [2022] [Jason Alexander Cleeland, Melbourne Australia]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

$tables = [
    'attachments' => [
        'attachment_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'task_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'orig_name' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'file_name' => 'varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'file_desc' => 'varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'file_type' => 'varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'file_size' => 'bigint NOT NULL DEFAULT \'0\'',
        'file_date' => 'varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL',
        'added_by' => 'mediumint NOT NULL DEFAULT \'0\'',
        'date_added' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'attachments_module' => 'varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL',
        'url' => 'longtext COLLATE utf8mb4_unicode_ci'
    ],
    'category_notifications' => [
        'category_notifications_id' => 'int NOT NULL AUTO_INCREMENT',
        'category_id' => 'int NOT NULL',
        'user_id' => 'int NOT NULL',
        'notify_new' => 'int NOT NULL',
        'notify_change' => 'int NOT NULL',
        'notify_close' => 'int NOT NULL'
    ],
    'comments' => [
        'comment_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'task_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'date_added' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'user_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'comment_text' => 'longtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'time_spent' => 'int DEFAULT NULL',
        'cost' => 'int DEFAULT NULL',
        'date_created' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL'
    ],
    'companion' => [
        'related_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'this_task' => 'mediumint NOT NULL DEFAULT \'0\'',
        'related_task' => 'mediumint NOT NULL DEFAULT \'0\''
    ],
    'custom_fields' => [
        'custom_field_id' => 'int NOT NULL AUTO_INCREMENT',
        'custom_field_definition_id' => 'int NOT NULL',
        'task_id' => 'int NOT NULL',
        'custom_field_value' => 'longtext COLLATE utf8mb4_unicode_ci',
        'custom_field_old_value' => 'longtext COLLATE utf8mb4_unicode_ci'
    ],
    'custom_field_definitions' => [
        'custom_field_definition_id' => 'int NOT NULL AUTO_INCREMENT',
        'custom_field_name' => 'varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL',
        'custom_field_visible' => 'int NOT NULL',
        'custom_field_type' => 'varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL'
    ],
    'custom_field_lists' => [
        'custom_field_list_id' => 'int NOT NULL AUTO_INCREMENT',
        'custom_field_definition_id' => 'int NOT NULL',
        'custom_field_value' => 'varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL',
        'custom_field_order' => 'int NOT NULL'
    ],
    'custom_texts' => [
        'custom_text_id' => 'int NOT NULL AUTO_INCREMENT',
        'modify_action' => 'varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL',
        'custom_text_lang' => 'varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL',
        'custom_text' => 'longtext COLLATE utf8mb4_unicode_ci'
    ],
    'emailtemplates' => [
        'template_id' => 'int NOT NULL AUTO_INCREMENT',
        'project_id' => 'int NOT NULL DEFAULT \'0\'',
        'name' => 'varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'subject' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'message' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'attachment' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\''
    ],
    'groups' => [
        'group_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'group_name' => 'varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'group_desc' => 'varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'is_admin' => 'mediumint NOT NULL DEFAULT \'0\'',
        'can_open_jobs' => 'mediumint NOT NULL DEFAULT \'0\'',
        'can_modify_jobs' => 'mediumint NOT NULL DEFAULT \'0\'',
        'can_add_comments' => 'mediumint NOT NULL DEFAULT \'0\'',
        'can_attach_files' => 'mediumint NOT NULL DEFAULT \'0\'',
        'can_vote' => 'mediumint NOT NULL DEFAULT \'0\'',
        'group_open' => 'mediumint NOT NULL DEFAULT \'0\'',
        'restrict_versions' => 'int NOT NULL DEFAULT \'0\''
    ],
    'history' => [
        'history_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'task_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'user_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'event_date' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'event_type' => 'mediumint NOT NULL DEFAULT \'0\'',
        'field_changed' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'old_value' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'new_value' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL'
    ],
    'invoices' => [
        'invoice_id' => 'int NOT NULL AUTO_INCREMENT',
        'task_id' => 'int NOT NULL DEFAULT \'0\'',
        'time_ids' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'date_generated' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'minutes' => 'int NOT NULL DEFAULT \'0\'',
        'amount' => 'float NOT NULL DEFAULT \'0\''
    ],
    'list_category' => [
        'category_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'project_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'category_name' => 'varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'category_descrip' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'list_position' => 'mediumint NOT NULL DEFAULT \'0\'',
        'show_in_list' => 'mediumint NOT NULL DEFAULT \'0\'',
        'category_owner' => 'mediumint NOT NULL DEFAULT \'0\'',
        'parent_id' => 'mediumint NOT NULL DEFAULT \'0\''
    ],
    'list_os' => [
        'os_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'project_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'os_name' => 'varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'list_position' => 'mediumint NOT NULL DEFAULT \'0\'',
        'show_in_list' => 'mediumint NOT NULL DEFAULT \'0\'',
        'os_description' => 'longtext COLLATE utf8mb4_unicode_ci'
    ],
    'list_resolution' => [
        'resolution_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'resolution_name' => 'varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'list_position' => 'mediumint NOT NULL DEFAULT \'0\'',
        'show_in_list' => 'mediumint NOT NULL DEFAULT \'0\'',
        'resolution_description' => 'longtext COLLATE utf8mb4_unicode_ci'
    ],
    'list_tasktype' => [
        'tasktype_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'tasktype_name' => 'varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'list_position' => 'mediumint NOT NULL DEFAULT \'0\'',
        'show_in_list' => 'mediumint NOT NULL DEFAULT \'0\'',
        'tasktype_description' => 'longtext COLLATE utf8mb4_unicode_ci'
    ],
    'list_tasktype_groups' => [
        'tasktype_groups_id' => 'int NOT NULL AUTO_INCREMENT',
        'tasktype_groups_name' => 'varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL',
        'owner_id' => 'int NOT NULL',
        'list_position' => 'int NOT NULL',
        'hide_from_list' => 'int NOT NULL DEFAULT \'0\''
    ],
    'list_tasktype_groups_links' => [
        'id' => 'int NOT NULL AUTO_INCREMENT',
        'tasktype_groups_id' => 'int NOT NULL',
        'tasktype_id' => 'int NOT NULL'
    ],
    'list_unit' => [
        'unit_id' => 'int NOT NULL AUTO_INCREMENT',
        'project_id' => 'int NOT NULL DEFAULT \'0\'',
        'unit_descrip' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
        'list_position' => 'int NOT NULL DEFAULT \'0\'',
        'show_in_list' => 'int NOT NULL DEFAULT \'0\'',
        'category_owner' => 'int NOT NULL DEFAULT \'0\'',
        'parent_id' => 'int NOT NULL DEFAULT \'0\''
    ],
    'list_version' => [
        'version_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'project_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'version_name' => 'varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'list_position' => 'mediumint NOT NULL DEFAULT \'0\'',
        'show_in_list' => 'mediumint NOT NULL DEFAULT \'0\'',
        'version_tense' => 'mediumint NOT NULL DEFAULT \'0\'',
        'is_enquiry' => 'int NOT NULL DEFAULT \'0\''
    ],
    'master' => [
        'link_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'master_task' => 'mediumint NOT NULL DEFAULT \'0\'',
        'servant_task' => 'mediumint NOT NULL DEFAULT \'0\''
    ],
    'member_cache' => [
        'member' => 'int NOT NULL DEFAULT \'0\'',
        'subs_paid_to' => 'datetime NOT NULL',
        'paying_emp' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
        'joined' => 'varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL',
        'surname' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
        'pref_name' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
        'modified' => 'int NOT NULL',
        'primary_key' => 'mediumtext COLLATE utf8mb4_unicode_ci',
        'data' => 'longblob'
    ],
    'noticeboard' => [
        'id' => 'int NOT NULL AUTO_INCREMENT',
        'publish_date' => 'date NOT NULL',
        'expiry_date' => 'date NOT NULL',
        'published' => 'int DEFAULT \'0\'',
        'title' => 'varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL',
        'message' => 'longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL',
        'created_by' => 'int NOT NULL',
        'allow_comments' => 'int NOT NULL DEFAULT \'0\'',
        'created_at' => 'timestamp NULL DEFAULT CURRENT_TIMESTAMP',
        'updated_at' => 'timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ],
    'noticeboard_comments' => [
        'id' => 'int NOT NULL AUTO_INCREMENT',
        'noticeboard_id' => 'int NOT NULL',
        'comment' => 'text COLLATE utf8mb4_unicode_ci NOT NULL',
        'hide_comment' => 'int NOT NULL DEFAULT \'0\'',
        'created_by' => 'int NOT NULL',
        'created_at' => 'timestamp NULL DEFAULT CURRENT_TIMESTAMP',
        'updated_at' => 'timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ],
    'notifications' => [
        'notify_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'task_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'user_id' => 'mediumint NOT NULL DEFAULT \'0\''
    ],
    'payments' => [
        'payment_id' => 'int NOT NULL AUTO_INCREMENT',
        'task_id' => 'int NOT NULL DEFAULT \'0\'',
        'amount' => 'float NOT NULL DEFAULT \'0\'',
        'date_received' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'0\'',
        'notes' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL'
    ],
    'people' => [
        'id' => 'int NOT NULL AUTO_INCREMENT',
        'firstname' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
        'lastname' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
        'position' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
        'organisation' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
        'phone' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
        'email' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL',
        'created' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL',
        'modified' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL'
    ],
    'people_of_interest' => [
        'id' => 'int NOT NULL AUTO_INCREMENT',
        'person_id' => 'int NOT NULL',
        'task_id' => 'int NOT NULL',
        'comment' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'created' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL',
        'modified' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL'
    ],
    'prefs' => [
        'pref_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'pref_name' => 'varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'pref_value' => 'varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'pref_desc' => 'varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'pref_group' => 'varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL'
    ],
    'projects' => [
        'project_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'project_title' => 'varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'theme_style' => 'varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'0\'',
        'show_logo' => 'mediumint NOT NULL DEFAULT \'0\'',
        'inline_images' => 'mediumint NOT NULL DEFAULT \'0\'',
        'default_cat_owner' => 'mediumint NOT NULL DEFAULT \'0\'',
        'intro_message' => 'longtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'project_is_active' => 'mediumint NOT NULL DEFAULT \'0\'',
        'visible_columns' => 'varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'last_email_check' => 'varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL'
    ],
    'registrations' => [
        'reg_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'reg_time' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'confirm_code' => 'varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\''
    ],
    'related' => [
        'related_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'this_task' => 'mediumint NOT NULL DEFAULT \'0\'',
        'related_task' => 'mediumint NOT NULL DEFAULT \'0\''
    ],
    'reminders' => [
        'reminder_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'task_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'to_user_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'from_user_id' => 'mediumint NOT NULL DEFAULT \'0\'',
        'start_time' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'0\'',
        'how_often' => 'mediumint NOT NULL DEFAULT \'0\'',
        'last_sent' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'0\'',
        'reminder_message' => 'longtext COLLATE utf8mb4_unicode_ci NOT NULL'
    ],
    'reports' => [
        'report_id' => 'int NOT NULL AUTO_INCREMENT',
        'report_category_id' => 'int NOT NULL DEFAULT \'0\'',
        'name' => 'varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'description' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'inputs' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'input_names' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'outputs' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'output_names' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'sql' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL'
    ],
    'report_categories' => [
        'report_category_id' => 'int NOT NULL AUTO_INCREMENT',
        'project_id' => 'int NOT NULL DEFAULT \'0\'',
        'name' => 'varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'description' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'sort_order' => 'int NOT NULL DEFAULT \'0\''
    ],
    'strategy' => [
        'strategy_id' => 'int NOT NULL AUTO_INCREMENT',
        'task_id' => 'int NOT NULL DEFAULT \'0\'',
        'user_id' => 'int NOT NULL DEFAULT \'0\'',
        'comment_date' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'0\'',
        'comment' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'acknowledged_date' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'acknowledged' => 'int NOT NULL DEFAULT \'0\''
    ],
    'tasks' => [
        'task_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'attached_to_project' => 'mediumint NOT NULL DEFAULT \'0\'',
        'task_type' => 'mediumint NOT NULL DEFAULT \'0\'',
        'date_opened' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'date_due' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'opened_by' => 'mediumint NOT NULL DEFAULT \'0\'',
        'is_closed' => 'mediumint NOT NULL DEFAULT \'0\'',
        'date_closed' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'closed_by' => 'mediumint NOT NULL DEFAULT \'0\'',
        'closure_comment' => 'longtext COLLATE utf8mb4_unicode_ci',
        'item_summary' => 'varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'detailed_desc' => 'longtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'item_status' => 'mediumint NOT NULL DEFAULT \'0\'',
        'assigned_to' => 'mediumint NOT NULL DEFAULT \'0\'',
        'resolution_reason' => 'mediumint NOT NULL DEFAULT \'1\'',
        'product_category' => 'mediumint NOT NULL DEFAULT \'0\'',
        'product_version' => 'mediumint NOT NULL DEFAULT \'0\'',
        'closedby_version' => 'mediumint NOT NULL DEFAULT \'0\'',
        'operating_system' => 'mediumint NOT NULL DEFAULT \'0\'',
        'task_severity' => 'mediumint NOT NULL DEFAULT \'0\'',
        'task_priority' => 'mediumint NOT NULL DEFAULT \'0\'',
        'last_edited_by' => 'mediumint NOT NULL DEFAULT \'0\'',
        'last_edited_time' => 'varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'0\'',
        'percent_complete' => 'mediumint NOT NULL DEFAULT \'0\'',
        'member' => 'int NOT NULL DEFAULT \'0\'',
        'name' => 'varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL',
        'unit' => 'mediumtext COLLATE utf8mb4_unicode_ci',
        'line_manager' => 'mediumtext COLLATE utf8mb4_unicode_ci',
        'line_manager_ph' => 'varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT \'\'',
        'local_delegate' => 'mediumtext COLLATE utf8mb4_unicode_ci',
        'local_delegate_ph' => 'varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT \'\'',
        'resolution_sought' => 'mediumtext COLLATE utf8mb4_unicode_ci',
        'is_restricted' => 'mediumint NOT NULL DEFAULT \'0\'',
        'closure_checklist' => 'varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'member_is_delegate' => 'smallint NOT NULL DEFAULT \'0\''
    ],
    'tasktype_checked' => [
        'checked_id' => 'int NOT NULL AUTO_INCREMENT',
        'checklist_id' => 'int NOT NULL DEFAULT \'0\'',
        'task_id' => 'int NOT NULL DEFAULT \'0\'',
        'date_checked' => 'int NOT NULL DEFAULT \'0\'',
        'user_id' => 'int NOT NULL DEFAULT \'0\''
    ],
    'tasktype_checklist' => [
        'checklist_id' => 'int NOT NULL AUTO_INCREMENT',
        'tasktype_id' => 'int NOT NULL DEFAULT \'0\'',
        'item' => 'mediumtext COLLATE utf8mb4_unicode_ci NOT NULL',
        'created' => 'int NOT NULL DEFAULT \'0\'',
        'user_id' => 'int NOT NULL DEFAULT \'0\''
    ],
    'times' => [
        'time_id' => 'int NOT NULL AUTO_INCREMENT',
        'task_id' => 'int DEFAULT NULL',
        'time' => 'varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL',
        'date' => 'varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL',
        'description' => 'mediumtext COLLATE utf8mb4_unicode_ci',
        'user_id' => 'int DEFAULT NULL',
        'invoiced' => 'int UNSIGNED DEFAULT \'0\'',
        'invoice_date' => 'varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL',
        'rate' => 'float NOT NULL DEFAULT \'0\''
    ],
    'users' => [
        'user_id' => 'mediumint NOT NULL AUTO_INCREMENT',
        'user_name' => 'varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'user_pass' => 'varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'real_name' => 'varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\'',
        'email_address' => 'varchar(100) COLLATE utf8mb4_unicode_ci NULL DEFAULT \'\'',
        'email_moderator' => 'int',
        'jabber_id' => 'varchar(100) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL',
        'group_in' => 'mediumint NOT NULL DEFAULT \'0\'',
        'notify_type' => 'mediumint NULL DEFAULT \'0\'',
        'notify_rate' => 'varchar(1) COLLATE utf8mb4_unicode_ci NULL DEFAULT \'D\'',
        'self_notify' => 'int NULL DEFAULT \'0\'',
        'default_version' => 'int NULL',
        'default_task_view' => 'varchar(15) COLLATE utf8mb4_unicode_ci NULL DEFAULT \'\'',
        'dateformat' => 'varchar(30) COLLATE utf8mb4_unicode_ci NULL DEFAULT \'\'',
        'dateformat_extended' => 'varchar(30) COLLATE utf8mb4_unicode_ci NULL DEFAULT \'\'',
        'strategy_enabled' => 'mediumint NULL',
        'account_enabled' => 'mediumint NULL DEFAULT \'0\'',
        'last_notice' => 'varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL'
    ],
    'version_permissions' => [
        'id' => 'int NOT NULL AUTO_INCREMENT',
        'group_id' => 'int NOT NULL',
        'version_id' => 'int NOT NULL',
        'enabled' => 'int NOT NULL DEFAULT \'0\''
    ]
];

$indexes = [
    'attachments' => [
        'PRIMARY KEY' => '(`attachment_id`)',
        'KEY `task_id-idx`' => '(`task_id`) USING BTREE'
    ],
    'category_notifications' => [
        'PRIMARY KEY' => '(`category_notifications_id`)'
    ],
    'comments' => [
        'PRIMARY KEY' => '(`comment_id`)',
        'KEY `task_id-idx`' => '(`task_id`) USING BTREE'
    ],
    'companion' => [
        'PRIMARY KEY' => '(`related_id`)'
    ],
    'custom_fields' => [
        'PRIMARY KEY' => '(`custom_field_id`)'
    ],
    'custom_field_definitions' => [
        'PRIMARY KEY' => '(`custom_field_definition_id`)'
    ],
    'custom_field_lists' => [
        'PRIMARY KEY' => '(`custom_field_list_id`)'
    ],
    'custom_texts' => [
        'PRIMARY KEY' => '(`custom_text_id`)'
    ],
    'emailtemplates' => [
        'PRIMARY KEY' => '(`template_id`)'
    ],
    'groups' => [
        'PRIMARY KEY' => '(`group_id`)'
    ],
    'history' => [
        'PRIMARY KEY' => '(`history_id`)',
        'KEY `task_id`' => '(`task_id`)',
        'KEY `event_date`' => '(`event_date`)',
        'KEY `task_id-idx`' => '(`task_id`) USING BTREE'
    ],
    'invoices' => [
        'KEY `invoice_id`' => '(`invoice_id`)'
    ],
    'list_category' => [
        'PRIMARY KEY' => '(`category_id`)'
    ],
    'list_os' => [
        'PRIMARY KEY' => '(`os_id`)'
    ],
    'list_resolution' => [
        'PRIMARY KEY' => '(`resolution_id`)'
    ],
    'list_tasktype' => [
        'PRIMARY KEY' => '(`tasktype_id`)'
    ],
    'list_tasktype_groups' => [
        'PRIMARY KEY' => '(`tasktype_groups_id`)'
    ],
    'list_tasktype_groups_links' => [
        'PRIMARY KEY' => '(`id`)',
        'KEY `tasktype_group_id`' => '(`tasktype_groups_id`,`tasktype_id`)'
    ],
    'list_unit' => [
        'PRIMARY KEY' => '(`unit_id`)'
    ],
    'list_version' => [
        'PRIMARY KEY' => '(`version_id`)'
    ],
    'master' => [
        'PRIMARY KEY' => '(`link_id`)'
    ],
    'member_cache' => [
        'PRIMARY KEY' => '(`member`)'
    ],
    'noticeboard' => [
        'PRIMARY KEY' => '(`id`)'
    ],
    'noticeboard_comments' => [
        'PRIMARY KEY' => '(`id`)'
    ],
    'notifications' => [
        'PRIMARY KEY' => '(`notify_id`)'
    ],
    'payments' => [
        'KEY `payment_id`' => '(`payment_id`)'
    ],
    'people' => [
        'PRIMARY KEY' => '(`id`)'
    ],
    'people_of_interest' => [
        'PRIMARY KEY' => '(`id`)'
    ],
    'prefs' => [
        'PRIMARY KEY' => '(`pref_id`)'
    ],
    'projects' => [
        'PRIMARY KEY' => '(`project_id`)'
    ],
    'registrations' => [
        'PRIMARY KEY' => '(`reg_id`)'
    ],
    'related' => [
        'PRIMARY KEY' => '(`related_id`)'
    ],
    'reminders' => [
        'PRIMARY KEY' => '(`reminder_id`)'
    ],
    'reports' => [
        'PRIMARY KEY' => '(`report_id`)'
    ],
    'report_categories' => [
        'PRIMARY KEY' => '(`report_category_id`)'
    ],
    'strategy' => [
        'PRIMARY KEY' => '(`strategy_id`)'
    ],
    'tasks' => [
        'PRIMARY KEY' => '(`task_id`)',
        'KEY `member-idx`' => '(`member`) USING BTREE',
        'KEY `member-is_closed-idx`' => '(`member`,`is_closed`) USING BTREE',
        'KEY `assigned_to-idx`' => '(`assigned_to`) USING BTREE',
        'KEY `assigned_to-date_due-is_closed-idx`' => '(`assigned_to`,`date_due`,`is_closed`) USING BTREE'
    ],
    'tasktype_checked' => [
        'PRIMARY KEY' => '(`checked_id`)'
    ],
    'tasktype_checklist' => [
        'PRIMARY KEY' => '(`checklist_id`)'
    ],
    'times' => [
        'PRIMARY KEY' => '(`time_id`)'
    ],
    'users' => [
        'PRIMARY KEY' => '(`user_id`)'
    ],
    'version_permissions' => [
        'PRIMARY KEY' => '(`id`)'
    ]
];


$initialdata = [
    'groups' => [
        [
            'group_id' => '1',
            'group_name' => 'Admin',
            'group_desc' => 'Administrators group. Users in this group have access to all features.',
            'is_admin' => '1',
            'can_open_jobs' => '1',
            'can_modify_jobs' => '1',
            'can_add_comments' => '1',
            'can_attach_files' => '1',
            'can_vote' => '1',
            'group_open' => '1',
            'restrict_versions' => '0'
        ],
        [
            'group_id' => '2',
            'group_name' => 'No Access',
            'group_desc' => 'Users in this group cannot access any features.',
            'is_admin' => '0',
            'can_open_jobs' => '0',
            'can_modify_jobs' => '0',
            'can_add_comments' => '0',
            'can_attach_files' => '0',
            'can_vote' => '0',
            'group_open' => '0',
            'restrict_versions' => '0'
        ],
        [
            'group_id' => '3',
            'group_name' => 'AttiCase Team',
            'group_desc' => 'Users in this group have access to standard user features.',
            'is_admin' => '0',
            'can_open_jobs' => '1',
            'can_modify_jobs' => '1',
            'can_add_comments' => '1',
            'can_attach_files' => '1',
            'can_vote' => '1',
            'group_open' => '1',
            'restrict_versions' => '0'
        ],
    ],
    'list_category' => [
        [
            'category_name' => 'Department 1',
            'category_descrip' => 'Department 1',
            'list_position' => '1',
            'show_in_list' => '1',
            'category_owner' => '0',
            'parent_id' => '0'
        ]
    ],
    'list_resolution' => [
        [
            'resolution_name' => 'Resolved',
            'list_position' => '1',
            'show_in_list' => '1',
            'resolution_description' => 'Resolved'
        ]
    ],
    'list_tasktype' => [
        [
            'tasktype_name' => 'Not yet categorised',
            'list_position' => '10',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Classification Issue',
            'list_position' => '20',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Complaints Investigation',
            'list_position' => '30',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Conduct / Inappropriate behaviour',
            'list_position' => '40',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Disciplinary',
            'list_position' => '50',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Grievance',
            'list_position' => '60',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Health and Safety',
            'list_position' => '70',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Pay and entitlements',
            'list_position' => '80',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Performance Management',
            'list_position' => '90',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Redeployment',
            'list_position' => '100',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Serious and wilful misconduct',
            'list_position' => '110',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Termination of employment',
            'list_position' => '120',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Underperformance',
            'list_position' => '130',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ],
        [
            'tasktype_name' => 'Workcover',
            'list_position' => '140',
            'show_in_list' => '1',
            'tasktype_description' => ''
        ]
    ],
    'list_version' => [
        [
            'version_name' => 'People & Culture Case',
            'list_position' => '1',
            'show_in_list' => '1',
            'version_tense' => '1',
            'is_enquiry' => '0'
        ],
        [
            'version_name' => 'People & Culture Enquiry',
            'list_position' => '2',
            'show_in_list' => '1',
            'version_tense' => '0',
            'is_enquiry' => '1'
        ]
    ],
];



?>