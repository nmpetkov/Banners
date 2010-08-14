<?php

/**
 * Smarty function to display .
 *
 * This function takes a identifier and returns a banner from the banners module
 *
 * Available parameters:
 *   - blocktype: type of banner as defined in the banners module
 *                note that the types are category IDs
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *
 * Example
 * {bannerdisplay blocktype=10012}
 *
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the banner
 */
function smarty_function_bannerdisplay($params, &$smarty)
{
    $id     = isset($params['blocktype']) ? (int)$params['blocktype'] : 0;
    $assign = isset($params['assign'])    ? $params['assign']         : null;

    if (ModUtil::available('Banners')) {
        $banner = ModUtil::func('Banners', 'user', 'display', array('blocktype' => array('Main' => $id)));
        if ($banner) {
            if ($assign) {
                $smarty->assign($assign, $banner['displaystring']);
            } else {
                return $banner['displaystring'];
            }
        } else {
            return;
        }
    } else {
        return;
    }
}
