<?php
/**
 * XOOPS Kernel Class
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A Module
 *
 * @package kernel
 * @author Kazumi Ono <onokazu@xoops.org>
 */
class XoopsModule extends XoopsObject
{
    /**
     * @var string
     */
    public $modinfo;

    /**
     *
     * @var array
     */
    public $adminmenu;
    /**
    *
    * @var array
    */
    private $_msg = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('mid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 150);
        $this->initVar('version', XOBJ_DTYPE_INT, 100, false);
        $this->initVar('last_update', XOBJ_DTYPE_INT, null, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('isactive', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('dirname', XOBJ_DTYPE_OTHER, null, true);
        $this->initVar('hasmain', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hasadmin', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hassearch', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hasconfig', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hascomments', XOBJ_DTYPE_INT, 0, false);
        // RMV-NOTIFY
        $this->initVar('hasnotification', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * Load module info
     *
     * @param string $dirname Directory Name
     * @param boolean $verbose
     */
    public function loadInfoAsVar($dirname, $verbose = true)
    {
        $dirname = basename($dirname);
        if (!isset($this->modinfo)) {
            $this->loadInfo($dirname, $verbose);
        }
        $this->setVar('name', $this->modinfo['name'], true);
        $this->setVar('version', intval(100 * ($this->modinfo['version'] + 0.001)), true);
        $this->setVar('dirname', $this->modinfo['dirname'], true);
        $hasmain = (isset($this->modinfo['hasMain']) && $this->modinfo['hasMain'] == 1) ? 1 : 0;
        $hasadmin = (isset($this->modinfo['hasAdmin']) && $this->modinfo['hasAdmin'] == 1) ? 1 : 0;
        $hassearch = (isset($this->modinfo['hasSearch']) && $this->modinfo['hasSearch'] == 1) ? 1 : 0;
        $hasconfig = ((isset($this->modinfo['config']) && is_array($this->modinfo['config'])) || ! empty($this->modinfo['hasComments'])) ? 1 : 0;
        $hascomments = (isset($this->modinfo['hasComments']) && $this->modinfo['hasComments'] == 1) ? 1 : 0;
        // RMV-NOTIFY
        $hasnotification = (isset($this->modinfo['hasNotification']) && $this->modinfo['hasNotification'] == 1) ? 1 : 0;
        $this->setVar('hasmain', $hasmain);
        $this->setVar('hasadmin', $hasadmin);
        $this->setVar('hassearch', $hassearch);
        $this->setVar('hasconfig', $hasconfig);
        $this->setVar('hascomments', $hascomments);
        // RMV-NOTIFY
        $this->setVar('hasnotification', $hasnotification);
    }

    /**
     * add a message
     *
     * @param string $str message to add
     * @access public
     */
    public function setMessage($str)
    {
        $this->_msg[] = trim($str);
    }

    /**
     * return the messages for this object as an array
     *
     * @return array an array of messages
     * @access public
     */
    public function getMessages()
    {
        return $this->_msg;
    }

    /**
     * Set module info
     *
     * @param   string  $name
     * @param   mixed   $value
     * @return  bool
     **/
    public function setInfo($name, $value)
    {
        if (empty($name)) {
            $this->modinfo = $value;
        } else {
            $this->modinfo[$name] = $value;
        }
        return true;
    }

    /**
     * Get module info
     *
     * @param string $name
     * @return array |string    Array of module information.
     * If {@link $name} is set, returns a single module information item as string.
     */
    public function getInfo($name = null)
    {
        if (!isset($this->modinfo)) {
            $this->loadInfo($this->getVar('dirname'));
        }
        if (isset($name)) {
            if (isset($this->modinfo[$name])) {
                return $this->modinfo[$name];
            }
            $return = false;
            return $return;
        }
        return $this->modinfo;
    }

    /**
     * Get a link to the modules main page
     *
     * @return string FALSE on fail
     */
    public function mainLink()
    {
        if ($this->getVar('hasmain') == 1) {
            $ret = '<a href="' . XOOPS_URL . '/modules/' . $this->getVar('dirname') . '/">' . $this->getVar('name') . '</a>';
            return $ret;
        }
        return false;
    }

    /**
     * Get links to the subpages
     *
     * @return string
     */
    public function subLink()
    {
        $ret = array();
        if ($this->getInfo('sub') && is_array($this->getInfo('sub'))) {
            foreach ($this->getInfo('sub') as $submenu) {
                $ret[] = array(
                    'name' => $submenu['name'] ,
                    'url' => $submenu['url']);
            }
        }
        return $ret;
    }

    /**
     * Load the admin menu for the module
     */
    public function loadAdminMenu()
    {
        if ($this->getInfo('adminmenu') && $this->getInfo('adminmenu') != '' && XoopsLoad::fileExists(XOOPS_ROOT_PATH . '/modules/' . $this->getInfo('dirname') . '/' . $this->getInfo('adminmenu'))) {
            $adminmenu = array();
            include XOOPS_ROOT_PATH . '/modules/' . $this->getInfo('dirname') . '/' . $this->getInfo('adminmenu');
            $this->adminmenu = $adminmenu;
        }
    }

    /**
     * Get the admin menu for the module
     *
     * @return string
     */
    public function getAdminMenu()
    {
        if (!isset($this->adminmenu)) {
            $this->loadAdminMenu();
        }
        return $this->adminmenu;
    }

    /**
     * Load the module info for this module
     *
     * @param string $dirname Module directory
     * @param bool $verbose Give an error on fail?
     * @return bool
     */
    public function loadInfo($dirname, $verbose = true)
    {
        if (empty($dirname)) {
            return false;
        }
        global $xoopsConfig; //for legacy
        $xoops = xoops::getInstance();
        $dirname = basename($dirname);
        $xoops->loadLanguage('modinfo', $dirname);
        $xoops->loadLocale($dirname);

        if (!XoopsLoad::fileExists($file = $xoops->path('modules/' . $dirname . '/xoops_version.php'))) {
            if (false != $verbose) {
                echo "Module File for $dirname Not Found!";
            }
            return false;
        }
        $modversion = array();
        include $file;
        $this->modinfo = $modversion;
        return true;
    }

    /**
     * Search contents within a module
     *
     * @deprecated Use search module instead
     *
     * @param string $term
     * @param string $andor 'AND' or 'OR'
     * @param integer $limit
     * @param integer $offset
     * @param integer $userid
     * @return mixed Search result.
     */
    public function search($term = '', $andor = 'AND', $limit = 0, $offset = 0, $userid = 0)
    {
        return false;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('mid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function mid($format = '')
    {
        return $this->getVar('mid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function name($format = '')
    {
        return $this->getVar('name', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function version($format = '')
    {
        return $this->getVar('version', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function last_update($format = '')
    {
        return $this->getVar('last_update', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function weight($format = '')
    {
        return $this->getVar('weight', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function isactive($format = '')
    {
        return $this->getVar('isactive', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function dirname($format = '')
    {
        return $this->getVar('dirname', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hasmain($format = '')
    {
        return $this->getVar('hasmain', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hasadmin($format = '')
    {
        return $this->getVar('hasadmin', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hassearch($format = '')
    {
        return $this->getVar('hassearch', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hasconfig($format = '')
    {
        return $this->getVar('hasconfig', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hascomments($format = '')
    {
        return $this->getVar('hascomments', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hasnotification($format = '')
    {
        return $this->getVar('hasnotification', $format);
    }

    /**
     * @param $dirname
     * @return XoopsModule
     */
    public function getByDirName($dirname)
    {
        return Xoops::getInstance()->getModuleByDirname($dirname);
    }
}

/**
 * XOOPS module handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS module class objects.
 *
 * @package kernel
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright (c) 2000-2003 The Xoops Project - www.xoops.org
 */
class XoopsModuleHandler extends XoopsPersistableObjectHandler
{
    /**
     * holds an array of cached module references, indexed by module id
     *
     * @var array
     * @access private
     */
    private $_cachedModule_mid = array();

    /**
     * holds an array of cached module references, indexed by module dirname
     *
     * @var array
     * @access private
     */
    private $_cachedModule_dirname = array();

    /**
     * Constructor
     *
     * @param XoopsConnection|null $db {@link XoopsConnection}
     */
    public function __construct(XoopsConnection $db = null)
    {
        parent::__construct($db, 'modules', 'XoopsModule', 'mid', 'dirname');
    }

    /**
     * Load a module from the database
     *
     * @param int $id ID of the module
     * @return XoopsModule|bool on fail
     */
    function getById($id = null)
    {
        static $_cachedModule_dirname;
        static $_cachedModule_mid;
        $id = intval($id);
        if ($id > 0) {
            if (!empty($_cachedModule_mid[$id])) {
                return $_cachedModule_mid[$id];
            } else {
                $module = parent::get($id);
                if (!is_object($module)) {
                    return false;
                }
                $_cachedModule_mid[$id] = $module;
                $_cachedModule_dirname[$module->getVar('dirname')] = $module;
                return $module;
            }
        }
        return false;
    }

    /**
     * Load a module by its dirname
     *
     * @param string $dirname
     * @return XoopsModule|bool FALSE on fail
     */
    public function getByDirname($dirname)
    {
        $dirname = basename(trim($dirname));
        static $_cachedModule_mid;
        static $_cachedModule_dirname;
        if (!empty($_cachedModule_dirname[$dirname])) {
            return $_cachedModule_dirname[$dirname];
        } else {
            $myts = MyTextSanitizer::getInstance();
            $criteria = new Criteria('dirname', $myts->addSlashes($dirname));
            $modules = $this->getObjectsArray($criteria);
            if (count($modules) == 1 && is_object($modules[0])) {
                $module = $modules[0];
            } else {
                return false;
            }
            /* @var $module XoopsModule */
            $_cachedModule_dirname[$dirname] = $module;
            $_cachedModule_mid[$module->getVar('mid')] = $module;
            return $module;
        }
    }

    /**
     * Write a module to the database
     *
     * @param XoopsModule $module reference to a {@link XoopsModule}
     *
     * @return bool
     */
    public function insertModule(XoopsModule &$module)
    {
        if (!parent::insert($module)) {
            return false;
        }

        $dirname = $module->getvar('dirname');
        $mid = $module->getvar('mid');

        if (!empty($this->_cachedModule_dirname[$dirname])) {
            unset($this->_cachedModule_dirname[$dirname]);
        }
        if (!empty($this->_cachedModule_mid[$mid])) {
            unset($this->_cachedModule_mid[$mid]);
        }
        Xoops_Cache::delete("module_id_" . $module->getVar('mid'));
        Xoops_Cache::delete("module_dirname_" . $module->getVar('dirname'));
        return true;
    }

    /**
     * Delete a module from the database
     *
     * @param XoopsModule &$module
     * @return bool
     */
    public function deleteModule(XoopsModule &$module)
    {
        if (!parent::delete($module)) {
            return false;
        }

        // delete admin permissions assigned for this module
        $sql = sprintf("DELETE FROM %s WHERE gperm_name = 'module_admin' AND gperm_itemid = %u", $this->db->prefix('group_permission'), $module->getVar('mid'));
        $this->db->query($sql);
        // delete read permissions assigned for this module
        $sql = sprintf("DELETE FROM %s WHERE gperm_name = 'module_read' AND gperm_itemid = %u", $this->db->prefix('group_permission'), $module->getVar('mid'));
        $this->db->query($sql);

        $sql = sprintf("SELECT block_id FROM %s WHERE module_id = %u", $this->db->prefix('block_module_link'), $module->getVar('mid'));
        if ($result = $this->db->query($sql)) {
            $block_id_arr = array();
            while ($myrow = $this->db->fetchArray($result)) {
                array_push($block_id_arr, $myrow['block_id']);
            }
        }
        // loop through block_id_arr
        if (isset($block_id_arr)) {
            foreach ($block_id_arr as $i) {
                $sql = sprintf("SELECT block_id FROM %s WHERE module_id != %u AND block_id = %u", $this->db->prefix('block_module_link'), $module->getVar('mid'), $i);
                if ($result2 = $this->db->query($sql)) {
                    if (0 < $this->db->getRowsNum($result2)) {
                        // this block has other entries, so delete the entry for this module
                        $sql = sprintf("DELETE FROM %s WHERE (module_id = %u) AND (block_id = %u)", $this->db->prefix('block_module_link'), $module->getVar('mid'), $i);
                        $this->db->query($sql);
                    } else {
                        // this block doesnt have other entries, so disable the block and let it show on top page only. otherwise, this block will not display anymore on block admin page!
                        $sql = sprintf("UPDATE %s SET visible = 0 WHERE bid = %u", $this->db->prefix('newblocks'), $i);
                        $this->db->query($sql);
                        $sql = sprintf("UPDATE %s SET module_id = -1 WHERE module_id = %u", $this->db->prefix('block_module_link'), $module->getVar('mid'));
                        $this->db->query($sql);
                    }
                }
            }
        }

        if (!empty($this->_cachedModule_dirname[$module->getVar('dirname')])) {
            unset($this->_cachedModule_dirname[$module->getVar('dirname')]);
        }
        if (!empty($this->_cachedModule_mid[$module->getVar('mid')])) {
            unset($this->_cachedModule_mid[$module->getVar('mid')]);
        }
        return true;
    }

    /**
     * Load some modules
     *
     * @param CriteriaElement|null $criteria {@link CriteriaElement}
     * @param boolean $id_as_key Use the ID as key into the array
     * @return array
     */
    public function getObjectsArray(CriteriaElement $criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM ' . $this->db->prefix('modules');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            $sql .= ' ORDER BY weight ' . $criteria->getOrder() . ', mid ASC';
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $module = new XoopsModule();
            $module->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $module;
            } else {
                $ret[$myrow['mid']] = $module;
            }
            unset($module);
        }
        return $ret;
    }

    /**
     * returns an array of module names
     *
     * @param CriteriaElement|null $criteria
     * @param boolean $dirname_as_key if true, array keys will be module directory names
     *          if false, array keys will be module id
     * @return array
     */
    function getNameList(CriteriaElement $criteria = null, $dirname_as_key = false)
    {
        $ret = array();
        $modules = $this->getObjectsArray($criteria, true);
        foreach (array_keys($modules) as $i) {
            if (!$dirname_as_key) {
                $ret[$i] = $modules[$i]->getVar('name');
            } else {
                $ret[$modules[$i]->getVar('dirname')] = $modules[$i]->getVar('name');
            }
        }
        return $ret;
    }
}