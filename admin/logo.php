<?php

use XoopsModules\Tdmcreate;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * tdmcreate module.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 *
 * @since           2.5.5
 *
 * @author          Txmod Xoops <support@txmodxoops.org>
 *
 * @version         $Id: 1.59 logo.php 11297 2013-03-24 10:58:10Z timgno $
 */
include __DIR__ . '/header.php';
$funct = \Xmf\Request::getString('funct', '', 'GET');
$iconName = \Xmf\Request::getString('iconName', '', 'GET');
$caption = \Xmf\Request::getString('caption', '', 'GET');
if (function_exists($funct)) {
    $ret = Tdmcreate\Logo::getInstance()->createLogo($iconName, $caption);
    phpFunction($ret);
} else {
    redirect_header('logo.php', 3, 'Method Not Exist');
}
// phpFunction
/**
 * @param string $val
 */
function phpFunction($val = '')
{
    // create php function here
    echo $val;
}
