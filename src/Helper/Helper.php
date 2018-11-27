<?php
/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 26/11/2018
 * Time: 15.38
 */

namespace NobrainerWeb\IconPicker\Helper;


use SilverStripe\Control\Director;

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

    public static function getSVGContents($path)
    {
        // TODO error handling
        return file_get_contents($path);
    }
}