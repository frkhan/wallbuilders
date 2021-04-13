<?php

namespace DevOwl\RealCategoryLibrary\lite;

\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
trait ScreenSettings
{
    // Documented in IOverrideScreenSettings
    public function screen_settings($settings)
    {
        $taxTree = $this->getCore()->getDefaultTaxTree();
        // Info about page categories.
        if ($taxTree->getTypeNow() === 'page') {
            $settings .= '<p style="font-weight:bold;font-size:11px">' . __('You are using Real Category Management (Free), but you need the PRO version to activate categories for pages.', RCL_TD) . ' &middot; <a href="' . RCL_PRO_VERSION . '" target="_blank">' . __('Learn more about PRO', RCL_TD) . '</a></p>';
        }
        if (!$taxTree->isAvailable()) {
            return $settings;
        }
        $treeViewDisabled = $taxTree->getTypeNow() === 'post' ? '' : 'disabled="disabled"';
        $settings .= '<fieldset class="metabox-prefs">
    		<legend>' . __('Advanced settings for this post type', RCL_TD) . '</legend>
    		<label><input ' . $treeViewDisabled . ' class="hide-column-tog" name="rcl-active" type="checkbox" id="rcl-active" value="1" ' . checked($this->isActive($taxTree), 1, \false) . '>' . __('Tree view', RCL_TD) . '</label>
            <label><input disabled="disabled" class="hide-column-tog" name="rcl-fast-mode" type="checkbox" id="rcl-fast-categories-off" value="1" ' . checked($this->isFastMode($taxTree), 1, \false) . '>' . __('Avoid page reload (tree view must be active)', RCL_TD) . '</label>' . '<p style="font-weight:bold;margin:0;font-size:11px">' . __('You are using Real Category Management (Free), you need the PRO version to use "Avoid page reload" and "Tree view" for custom post types.', RCL_TD) . ' &middot; <a href="' . RCL_PRO_VERSION . '" target="_blank">' . __('Learn more about PRO', RCL_TD) . '</a></p>
		</fieldset>';
        return $settings;
    }
}
