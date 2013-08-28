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
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Comments
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */


include dirname(dirname(dirname(dirname(__FILE__)))) . '/mainfile.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/include/cp_header.php';

$xoops = Xoops::getInstance();
XoopsLoad::load('system', 'system');
$system = System::getInstance();