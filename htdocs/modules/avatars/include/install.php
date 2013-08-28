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
 * avatars module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id$
 */
function xoops_module_install_avatars(&$module)
{
    // delete avatar_allow_upload, avatar_width, avatar_height, avatar_maxsize and avatar_minposts
    $xoops = Xoops::getInstance();
    $sql = "DELETE FROM " . $xoopsDB->prefix("config") . " WHERE `conf_name` = 'avatar_allow_upload'";
    $xoopsDB->queryF($sql);
    $sql = "DELETE FROM " . $xoopsDB->prefix("config") . " WHERE `conf_name` = 'avatar_width'";
    $xoopsDB->queryF($sql);
    $sql = "DELETE FROM " . $xoopsDB->prefix("config") . " WHERE `conf_name` = 'avatar_height'";
    $xoopsDB->queryF($sql);
    $sql = "DELETE FROM " . $xoopsDB->prefix("config") . " WHERE `conf_name` = 'avatar_maxsize'";
    $xoopsDB->queryF($sql);
    $sql = "DELETE FROM " . $xoopsDB->prefix("config") . " WHERE `conf_name` = 'avatar_minposts'";
    $xoopsDB->queryF($sql);

    $dbManager = new XoopsDatabaseManager();
    $map = array(
        'avatar_id'       => 'avatar_id',
        'avatar_file'     => 'avatar_file',
        'avatar_name'     => 'avatar_name',
        'avatar_mimetype' => 'avatar_mimetype',
        'avatar_created'  => 'avatar_created',
        'avatar_display'  => 'avatar_display',
        'avatar_weight'   => 'avatar_weight',
        'avatar_type'     => 'avatar_type',
    );
    $dbManager->copyFields($map, 'avatar', 'avatars_avatar', false);

    $map = array(
        'avatar_id' => 'avatar_id',
        'user_id'   => 'user_id',
    );
    $dbManager->copyFields($map, 'avatar_user_link', 'avatars_user_link', false);


    // create folder "avatars"
    $dir = XOOPS_ROOT_PATH . "/uploads/avatars";
    if(!is_dir($dir)) {
        mkdir($dir, 0777);
        chmod($dir, 0777);
    }
    //Copy index.html
    $file = XOOPS_ROOT_PATH . "/uploads/avatars/index.html";
    if(!is_file($file)) {
        copy(XOOPS_ROOT_PATH . "/modules/avatars/images/index.html", $file);
    }
    //Copy blank.gif
    $file = XOOPS_ROOT_PATH . "/uploads/avatars/blank.gif";
    if(!is_file($file)) {
        copy(XOOPS_ROOT_PATH . "/modules/avatars/images/blank.gif", $file);
    }
    //Copy .htaccess
    $file = XOOPS_ROOT_PATH . "/uploads/avatars/.htaccess";
    if(!is_file($file)) {
        copy(XOOPS_ROOT_PATH . "/uploads/.htaccess", $file);
    }
    return true;
}