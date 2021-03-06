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
 * modulebuilder module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 */

// Define main template
$templateMain = 'modulebuilder_settings.tpl';

include __DIR__ . '/header.php';

// Recovered value of argument op in the URL $
$op    = \Xmf\Request::getString('op', 'list');
$setId = \Xmf\Request::getInt('set_id');

switch ($op) {
    case 'list':
    default:
        $start = \Xmf\Request::getInt('start', 0);
        $limit = \Xmf\Request::getInt('limit', $helper->getConfig('settings_adminpager'));
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoTheme']->addStylesheet('modules/modulebuilder/assets/css/admin/style.css');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('settings.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_ADD_SETTING, 'settings.php?op=new', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('tdmc_upload_imgmod_url', TDMC_UPLOAD_IMGMOD_URL);
        $GLOBALS['xoopsTpl']->assign('tdmc_url', TDMC_URL);
        $GLOBALS['xoopsTpl']->assign('modPathIcon16', TDMC_URL . '/' . $modPathIcon16);
        $GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
        $settingsCount = $helper->getHandler('Settings')->getCountSettings();
        $settingsAll   = $helper->getHandler('Settings')->getAllSettings($start, $limit);
        // Display settings list
        if ($settingsCount > 0) {
            foreach (array_keys($settingsAll) as $i) {
                $setting = $settingsAll[$i]->getValuesSettings();
                $GLOBALS['xoopsTpl']->append('settings_list', $setting);
                unset($setting);
            }
            if ($settingsCount > $limit) {
                include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
                $pagenav = new \XoopsPageNav($settingsCount, $limit, $start, 'start', 'op=list&limit=' . $limit);
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_MODULEBUILDER_THEREARENT_SETTINGS);
        }
        break;
    case 'new':
        $GLOBALS['xoTheme']->addScript('modules/modulebuilder/assets/js/functions.js');
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('settings.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_SETTINGS_LIST, 'settings.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $settingsObj = $helper->getHandler('Settings')->create();
        $form        = $settingsObj->getFormSettings();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'save':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('settings.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($setId)) {
            $settingsObj = $helper->getHandler('Settings')->get($setId);
        } else {
            $settingsObj = $helper->getHandler('Settings')->create();
        }
        $setModuleDirname = preg_replace('/[^a-zA-Z0-9]\s+/', '', mb_strtolower($_POST['set_dirname']));
        //Form module save
        $settingsObj->setVars(
            [
                'set_name'                => \Xmf\Request::getString('set_name', '', 'POST'),
                'set_dirname'             => $setModuleDirname,
                'set_version'             => \Xmf\Request::getString('set_version', '', 'POST'),
                'set_since'               => \Xmf\Request::getString('set_since', '', 'POST'),
                'set_min_php'             => \Xmf\Request::getString('set_min_php', '', 'POST'),
                'set_min_xoops'           => \Xmf\Request::getString('set_min_xoops', '', 'POST'),
                'set_min_admin'           => \Xmf\Request::getString('set_min_admin', '', 'POST'),
                'set_min_mysql'           => \Xmf\Request::getString('set_min_mysql', '', 'POST'),
                'set_description'         => \Xmf\Request::getString('set_description', '', 'POST'),
                'set_author'              => \Xmf\Request::getString('set_author', '', 'POST'),
                'set_author_mail'         => \Xmf\Request::getString('set_author_mail', '', 'POST'),
                'set_author_website_url'  => \Xmf\Request::getString('set_author_website_url', '', 'POST'),
                'set_author_website_name' => \Xmf\Request::getString('set_author_website_name', '', 'POST'),
                'set_credits'             => \Xmf\Request::getString('set_credits', '', 'POST'),
                'set_license'             => \Xmf\Request::getString('set_license', '', 'POST'),
                'set_release_info'        => \Xmf\Request::getString('set_release_info', '', 'POST'),
                'set_release_file'        => \Xmf\Request::getString('set_release_file', '', 'POST'),
                'set_manual'              => \Xmf\Request::getString('set_manual', '', 'POST'),
                'set_manual_file'         => \Xmf\Request::getString('set_manual_file', '', 'POST'),
            ]
        );
        //Form set_image
        $settingsObj->setVar('set_image', \Xmf\Request::getString('set_image', '', 'POST'));
        //Form module save
        $settingsObj->setVars(
            [
                'set_demo_site_url'  => \Xmf\Request::getString('set_demo_site_url', '', 'POST'),
                'set_demo_site_name' => \Xmf\Request::getString('set_demo_site_name', '', 'POST'),
                'set_support_url'    => \Xmf\Request::getString('set_support_url', '', 'POST'),
                'set_support_name'   => \Xmf\Request::getString('set_support_name', '', 'POST'),
                'set_website_url'    => \Xmf\Request::getString('set_website_url', '', 'POST'),
                'set_website_name'   => \Xmf\Request::getString('set_website_name', '', 'POST'),
                'set_release'        => \Xmf\Request::getString('set_release', '', 'POST'),
                'set_status'         => \Xmf\Request::getString('set_status', '', 'POST'),
                'set_donations'      => \Xmf\Request::getString('set_donations', '', 'POST'),
                'set_subversion'     => \Xmf\Request::getString('set_subversion', '', 'POST'),
            ]
        );
        $settingOption = \Xmf\Request::getArray('setting_option', []);
        $settingsObj->setVar('set_admin', in_array('admin', $settingOption));
        $settingsObj->setVar('set_user', in_array('user', $settingOption));
        $settingsObj->setVar('set_blocks', in_array('blocks', $settingOption));
        $settingsObj->setVar('set_search', in_array('search', $settingOption));
        $settingsObj->setVar('set_comments', in_array('comments', $settingOption));
        $settingsObj->setVar('set_notifications', in_array('notifications', $settingOption));
        $settingsObj->setVar('set_permissions', in_array('permissions', $settingOption));
        //$settingsObj->setVar('set_inroot_copy', in_array('inroot', $settingOption));
        if (\Xmf\Request::hasVar('set_type')) {
            $settingsObj->setVar('set_type', \Xmf\Request::getString('set_type', '', 'POST'));
        }

        if ($helper->getHandler('Settings')->insert($settingsObj)) {
            redirect_header('settings.php', 5, sprintf(_AM_MODULEBUILDER_MODULE_FORM_UPDATED_OK, \Xmf\Request::getString('set_name', '', 'POST')));
        }

        $GLOBALS['xoopsTpl']->assign('error', $settingsObj->getHtmlErrors());
        $form = $settingsObj->getFormSettings();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'edit':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('settings.php'));
        $adminObject->addItemButton(_AM_MODULEBUILDER_ADD_SETTING, 'settings.php?op=new', 'add');
        $adminObject->addItemButton(_AM_MODULEBUILDER_SETTINGS_LIST, 'settings.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $settingsObj = $helper->getHandler('Settings')->get($setId);
        $form        = $settingsObj->getFormSettings();
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    case 'delete':
        $settingsObj = $helper->getHandler('Settings')->get($setId);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('settings.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($helper->getHandler('Settings')->delete($settingsObj)) {
                redirect_header('settings.php', 3, _AM_MODULEBUILDER_FORMDELOK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $settingsObj->getHtmlErrors());
            }
        } else {
            xoops_confirm(['ok' => 1, 'set_id' => $setId, 'op' => 'delete'], \Xmf\Request::getString('REQUEST_URI', '', 'SERVER'), sprintf(_AM_MODULEBUILDER_FORMSUREDEL, $settingsObj->getVar('set_name')));
        }
        break;
    case 'display':
        $id = \Xmf\Request::getInt('set_id', 0, 'POST');
        if ($id > 0) {
            $settingsObj = $helper->getHandler('Settings')->get($id);
            if (isset($_POST['set_type'])) {
                $setType = $settingsObj->getVar('set_type');
                $settingsObj->setVar('set_type', !$setType);
            }
            $GLOBALS['xoopsTpl']->assign('error', $settingsObj->getHtmlErrors());
        }
        break;
}
include __DIR__ . '/footer.php';
