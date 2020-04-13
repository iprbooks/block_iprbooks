<?php

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configtext('iprbooks/user_id', get_string('user_id', 'block_iprbooks'), "", null, PARAM_INT));
    $settings->add(new admin_setting_configtext('iprbooks/user_token', get_string('user_token', 'block_iprbooks'), "", null));

}