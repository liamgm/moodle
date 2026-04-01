<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Admin settings and defaults
 *
 * @package auth_lti
 * @copyright  2026 Liam Moran <moran@illinois.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once($CFG->dirroot . '/lib/accesslib.php');

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_lti/pluginname',
            get_string('default_settings', 'auth_lti'),
            get_string('auth_ltidescription', 'auth_lti')));

    // Prepare Roles
    $allroles = get_all_roles();
    $roles = [];
    $instructordefault = 0;
    $learnerdefault = 0;
    foreach ($allroles as $role) {
        if ($role->shortname == 'editingteacher') {
            $instructordefault = $role->id;
        } else if ($role->shortname == 'student') {
            $learnerdefault = $role->id;
        }
        $roles[$role->id] = role_get_name($role);
    }

    // Default Instructor role
    $settings->add(new admin_setting_configselect('auth_lti/defaultinstructorrole',
                   get_string('roleinstructor','auth_lti'),
                   get_string('roleinstructor_help','auth_lti'),
                   $instructordefault,
                   $roles));
    // Default Learner role
    $settings->add(new admin_setting_configselect('auth_lti/defaultlearnerrole',
                   get_string('rolelearner','auth_lti'),
                   get_string('rolelearner_help','auth_lti'),
                   $learnerdefault,
                   $roles));

    $authplugin = get_auth_plugin('lti');
    $authmodes = [
        auth_plugin_lti::PROVISIONING_MODE_AUTO_ONLY => get_string('provisioningmodeauto', 'auth_lti'),
        auth_plugin_lti::PROVISIONING_MODE_PROMPT_NEW_EXISTING => get_string('provisioningmodenewexisting', 'auth_lti'),
        auth_plugin_lti::PROVISIONING_MODE_PROMPT_EXISTING_ONLY => get_string('provisioningmodeexistingonly', 'auth_lti')
    ];

    // Default Provisioning Mode Instructor First Launch
    $settings->add(new admin_setting_configselect('auth_lti/defaultinstructorauthmode',
                   get_string('modeinstructordefault','auth_lti'),
                   get_string('modeinstructordefault_help','auth_lti'),
                   auth_plugin_lti::PROVISIONING_MODE_PROMPT_NEW_EXISTING,
                   $authmodes));

    // Default Provisioning Mode Learner First Launch
    $settings->add(new admin_setting_configselect('auth_lti/defaultlearnerauthmode',
                   get_string('modelearnerdefault','auth_lti'),
                   get_string('modelearnerdefault_help','auth_lti'),
                   auth_plugin_lti::PROVISIONING_MODE_AUTO_ONLY,
                   $authmodes));

    // Display locking / mapping of profile fields.
    display_auth_lock_options($settings, $authplugin->authtype,
        $authplugin->userfields, get_string('auth_fieldlocks_help', 'auth'), false, false);
}
