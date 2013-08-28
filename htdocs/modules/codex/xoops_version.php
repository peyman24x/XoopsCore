<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         codex
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

$modversion = array();
$modversion['name'] = _MI_CODEX_NAME;
$modversion['description'] = _MI_CODEX_DSC;
$modversion['version'] = 0.1;
$modversion['author'] = 'Trabis';
$modversion['nickname'] = 'trabis';
$modversion['credits'] = 'The XOOPS Project';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['official'] = 1;
//$modversion['help']           = 'page=help';
$modversion['image'] = 'images/logo.png';
$modversion['dirname'] = 'codex';

//about
$modversion['release_date'] = '2012/11/25';
$modversion['module_website_url'] = 'http://www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status'] = 'ALPHA 1';
$modversion['min_php'] = '5.3';
$modversion['min_xoops'] = '2.6.0';
$modversion['min_db'] = array('mysql' => '5.0.7', 'mysqli' => '5.0.7');

// paypal
$modversion['paypal'] = array();
$modversion['paypal']['business'] = 'lusopoemas@gmail.com';
$modversion['paypal']['item_name'] = '';
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'EUR';

// Admin menu
// Set to 1 if you want to display menu generated by system module
//$modversion['system_menu'] = 1;


// Admin things
$modversion['hasAdmin'] = 0;

// Menu
$modversion['hasMain'] = 1;
