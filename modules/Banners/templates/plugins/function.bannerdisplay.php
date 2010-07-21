<?php
/**
 * Smarty function to display .
 *
 * This function takes a identifier and returns a banner from the banners module
 *
 * Available parameters:
 *   - type:     type of banner as defined in the banners module
 *               note that the types are category IDs
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *
 * Example
 * {bannerdisplay type=10012}
 *
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @param        sting
 * @return       string      the banner
 */
function smarty_function_bannerdisplay ($params, &$smarty)
{
    $id     = isset($params['type'])   ? (int)$params['type'] : 0;
    $assign = isset($params['assign']) ? $params['assign']    : null;

    if (ModUtil::available('Banners'))  {
        $banner = ModUtil::func('Banners', 'user', 'display', array('type' => $type));
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
