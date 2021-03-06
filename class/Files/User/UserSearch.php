<?php

namespace XoopsModules\Modulebuilder\Files\User;

use XoopsModules\Modulebuilder;
use XoopsModules\Modulebuilder\Files;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.0
 *
 * @author          Txmod Xoops http://www.txmodxoops.org
 *
 */

/**
 * Class UserSearch.
 */
class UserSearch extends Files\CreateFile
{
    /**
     * @var mixed
     */
    private $usercode = null;

    /**
     * @var string
     */
    private $xoopscode = null;

    /**
     * @public function constructor
     * @param null
     */
    public function __construct()
    {
        parent::__construct();
        $this->xoopscode = Modulebuilder\Files\CreateXoopsCode::getInstance();
        $this->phpcode   = Modulebuilder\Files\CreatePhpCode::getInstance();
        $this->usercode  = UserXoopsCode::getInstance();
    }

    /**
     * @static function getInstance
     * @param null
     * @return UserSearch
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @public function write
     * @param string $module
     * @param mixed  $table
     * @param string $filename
     */
    public function write($module, $table, $filename)
    {
        $this->setModule($module);
        $this->setTable($table);
        $this->setFileName($filename);
    }

    /**
     * @private function getUserSearchHeader
     *
     * @param $moduleDirname
     *
     * @param $table
     * @param $fields
     * @return string
     */
    private function getUserSearchHeader($moduleDirname, $table, $fields)
    {
        $pc  = Modulebuilder\Files\CreatePhpCode::getInstance();
        $ret = $pc->getPhpCodeUseNamespace(['Xmf', 'Request'], '', '');
        $ret .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname], '', '');
        $ret .= $pc->getPhpCodeUseNamespace(['XoopsModules', $moduleDirname, 'Constants']);
        $ret .= $this->getInclude();
        foreach (array_keys($fields) as $f) {
            $fieldName = $fields[$f]->getVar('field_name');
            if (0 == $f) {
                $fieldId = $fieldName;
            }
            if (1 == $fields[$f]->getVar('field_parent')) {
                $fieldPid = $fieldName;
            }
        }
        if (1 == $table->getVar('table_category')) {
            $ccFieldPid = $this->getCamelCase($fieldPid, false, true);
            $ret        .= $this->xoopscode->getXcXoopsRequest($ccFieldPid, (string)$fieldPid, '0', 'Int');
        }
        $ccFieldId = $this->getCamelCase($fieldId, false, true);
        $ret       .= $this->xoopscode->getXcXoopsRequest($ccFieldId, (string)$fieldId, '0', 'Int');
        $ret       .= $this->usercode->getUserTplMain($moduleDirname);
        $ret       .= $this->phpcode->getPhpCodeIncludeDir('XOOPS_ROOT_PATH', 'header', true);
        $ret       .= $this->getDashComment('Define Stylesheet');
        $ret       .= $this->xoopscode->getXcXoThemeAddStylesheet();

        return $ret;
    }

    /**
     * @public function getAdminPagesList
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     * @return string
     */
    public function getUserSearch($moduleDirname, $tableName, $language)
    {
        $ret = <<<'EOT'

EOT;
        $ret .= $this->getSimpleString('$keywords = array();');

        return $ret;
    }

    /**
     * @private function getUserSearchFooter
     *
     * @param $moduleDirname
     * @param $tableName
     * @param $language
     *
     * @return string
     */
    private function getUserSearchFooter($moduleDirname, $tableName, $language)
    {
        $stuModuleDirname = mb_strtoupper($moduleDirname);
        $stuTableName     = mb_strtoupper($tableName);
        $ret              = $this->getDashComment('Breadcrumbs');
        $ret              .= $this->usercode->getUserBreadcrumbs((string)$stuTableName, $language);
        $ret              .= $this->getDashComment('Keywords');
        $ret              .= $this->usercode->getUserMetaKeywords($moduleDirname);
        $ret              .= $this->phpcode->getPhpCodeUnset('keywords');
        $ret              .= $this->getDashComment('Description');
        $ret              .= $this->usercode->getUserMetaDesc($moduleDirname, 'DESC', $language);
        $ret              .= $this->xoopscode->getXcXoopsTplAssign('xoops_mpageurl', "{$stuModuleDirname}_URL.'/index.php'");
        $ret              .= $this->xoopscode->getXcXoopsTplAssign('xoops_icons32_url', 'XOOPS_ICONS32_URL');
        $ret              .= $this->xoopscode->getXcXoopsTplAssign("{$moduleDirname}_upload_url", "{$stuModuleDirname}_UPLOAD_URL");
        $ret              .= $this->getInclude('footer');

        return $ret;
    }

    /**
     * @public function render
     * @param null
     * @return bool|string
     */
    public function render()
    {
        $module        = $this->getModule();
        $table         = $this->getTable();
        $filename      = $this->getFileName();
        $moduleDirname = $module->getVar('mod_dirname');
        $tableId       = $table->getVar('table_id');
        $tableMid      = $table->getVar('table_mid');
        $tableName     = $table->getVar('table_name');
        $fields        = $this->getTableFields($tableMid, $tableId);
        $language      = $this->getLanguage($moduleDirname, 'MA');
        $content       = $this->getHeaderFilesComments($module);
        $content       .= $this->getUserSearchHeader($moduleDirname, $table, $fields);
        $content       .= $this->getUserSearch($moduleDirname, $tableName, $language);
        $content       .= $this->getUserSearchFooter($moduleDirname, $tableName, $language);

        $this->create($moduleDirname, '/', $filename, $content, _AM_MODULEBUILDER_FILE_CREATED, _AM_MODULEBUILDER_FILE_NOTCREATED);

        return $this->renderFile();
    }
}
