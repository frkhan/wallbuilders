<?php

namespace DevOwl\RealCategoryLibrary\view;

use DevOwl\RealCategoryLibrary\base\UtilsProvider;
use DevOwl\RealCategoryLibrary\lite\ScreenSettings as LiteScreenSettings;
use DevOwl\RealCategoryLibrary\overrides\interfce\IOverrideScreenSettings;
use DevOwl\RealCategoryLibrary\TaxTree;
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
/**
 * Screen settings for every post type / taxonomy.
 */
class ScreenSettings implements \DevOwl\RealCategoryLibrary\overrides\interfce\IOverrideScreenSettings
{
    use UtilsProvider;
    use LiteScreenSettings;
    /**
     * Returns if the tree is active for the given taxonomy tree.
     *
     * @param TaxTree $taxTree The taxonomy tree
     * @return boolean
     */
    public function isActive($taxTree)
    {
        // We can do the isPro check here because when it also returns true the CPT tree does not work
        if (!$this->isPro() && ($taxTree->getTypeNow() !== 'post' || $taxTree->getTaxNow()->objkey !== 'category')) {
            return \false;
        }
        return (bool) get_option(RCL_OPT_PREFIX . '-active-' . $taxTree->getTypeNow(), 1);
    }
    /**
     * Returns if the tree is fast mode for the given taxonomy tree.
     *
     * @param TaxTree $taxTree The taxonomy tree
     * @return boolean
     */
    public function isFastMode($taxTree)
    {
        // We can do the isPro check here because it is JS splitted
        return $this->isPro() && (bool) get_option(RCL_OPT_PREFIX . '-fast-mode-' . $taxTree->getTypeNow(), 1);
    }
    /**
     * Save the screen options over the nonce checker.
     * The nonce name is "screen-options-nonce".
     *
     * @param string $action
     * @param mixed $result
     */
    public function check_admin_referer($action, $result)
    {
        if ($action === 'screen-options-nonce' && $result) {
            $typenow = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'post';
            $checkbox = [RCL_OPT_PREFIX . '-active', RCL_OPT_PREFIX . '-fast-mode'];
            require_once ABSPATH . WPINC . \DIRECTORY_SEPARATOR . 'option.php';
            foreach ($checkbox as $name) {
                update_option($name . '-' . $typenow, isset($_POST[$name]) && \boolval($_POST[$name]) ? '1' : '0');
            }
        }
    }
}
