<?php

/* 
 * Use this SDK as a plugin to your current codebase. All you have to do is include/require this
 * file once and it will take care of the rest.
 */

require_once(dirname(__FILE__) . '/libs/autoloader.class.php');

# Set up the autoloader first so that settings file can use the classes if it needs to.
$dirs = array(
    dirname(__FILE__) . '/enums',
    dirname(__FILE__) . '/libs',
    dirname(__FILE__) . '/objects',
    dirname(__FILE__) . '/requests'
);

$autoloader = new Autoloader($dirs);

# Dont forget to fill this out!
require_once(dirname(__FILE__) . '/settings/settings.php');