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
 * My Module 2 module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        mymodule2
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         TDM XOOPS - Email:<info@email.com> - Website:<http://xoops.org>
 */

/**
 * CommentsUpdate
 *
 * @param mixed $itemId
 * @param mixed $itemNumb
 * @return bool
 */
function mymodule2CommentsUpdate($itemId, $itemNumb) {
    $itemId = (int)$itemId;
    $itemNumb = (int)$itemNumb;
    $article = new Mymodule2Testfields($itemId);
    if (!$article->updateComments($itemNumb)) {
        return false;
    }
    return true;
}

/**
 * CommentsApprove
 *
 * @param string  $comment
 * @return void
 */
function mymodule2CommentsApprove(&$comment){
    // notification mail here
}