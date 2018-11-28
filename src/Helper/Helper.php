<?php
/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 26/11/2018
 * Time: 15.38
 */

namespace NobrainerWeb\IconPicker\Helper;


use NobrainerWeb\IconPicker\ORM\Icon;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;

class Helper
{
    /**
     * @param $folderPath
     * @return array
     */
    public static function getSVGFiles($folderPath)
    {
        $files = scandir($folderPath, SCANDIR_SORT_ASCENDING);
        $svg_regex = '/\.svg$/';
        $src = [];

        foreach ($files as $file) {
            // only svg
            if (!preg_match($svg_regex, $file)) {
                continue;
            }
            $cleanName = preg_replace($svg_regex, '', $file);
            $src[$cleanName] = $cleanName;
        }

        return $src;
    }

    /**
     * @param $path
     * @return string
     */
    public static function getAbsoluteFolderPath($path)
    {
        return Director::absoluteBaseURL() . '/'.  $path;
    }

    /**
     * @param $path
     * @return string
     */
    public static function getRelativeFolderPath($path)
    {
        return Director::baseFolder() . '/'. $path;
    }

    /**
     * @return string
     */
    public static function getCMSSourceFolder()
    {
        return Config::inst()->get(Icon::class, 'cms_icon_source_folder');
    }
}