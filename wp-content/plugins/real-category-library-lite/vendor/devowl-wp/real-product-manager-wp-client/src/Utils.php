<?php

namespace DevOwl\RealCategoryLibrary\Vendor\DevOwl\RealProductManagerWpClient;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Utils functionality.
 */
class Utils {
    /**
     * Check if the current installation is multisite.
     */
    public static function isMu() {
        return \function_exists('switch_to_blog') && \function_exists('get_network') && get_network() !== null;
    }
    /**
     * Get current home url, normalized without schema and `www` subdomain.
     * This avoids general conflicts for situations, when customers move their
     * HTTP site to HTTPS.
     *
     * @return string
     */
    public static function getCurrentHostname() {
        $url = \trim(untrailingslashit(site_url()), '/');
        $url = \preg_replace('/^http(s)?:\\/\\//', '', $url);
        return \preg_replace('/^www\\./', '', $url);
    }
    /**
     * To avoid issues with multisites without own domains, we need to map blog ids
     * to their `site_url`'s host so we can determine the used license for a given blog.
     *
     * @param int[] $blogIds
     */
    public static function mapBlogsToHosts($blogIds) {
        // Map blog ids to potential hostnames and reverse
        $hostnames = [];
        $isMu = \DevOwl\RealCategoryLibrary\Vendor\DevOwl\RealProductManagerWpClient\Utils::isMu();
        foreach ($blogIds as $blogId) {
            if ($isMu) {
                switch_to_blog($blogId);
            }
            $host = \parse_url(site_url(), \PHP_URL_HOST);
            $hostnames['blog'][$blogId] = $host;
            $hostnames['host'][$host][] = $blogId;
            if ($isMu) {
                restore_current_blog();
            }
        }
        return $hostnames;
    }
}
