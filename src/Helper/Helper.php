<?php
/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 26/11/2018
 * Time: 15.38
 */

namespace NobrainerWeb\IconPicker\Helper;


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
}