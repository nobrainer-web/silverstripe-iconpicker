<?php
/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 28/11/2018
 * Time: 15.25
 */

namespace NobrainerWeb\IconPicker\Traits;


use NobrainerWeb\IconPicker\Helper\Helper;
use NobrainerWeb\IconPicker\ORM\Icon;

trait FieldTraits
{
    /**
     * @return string
     */
    public function getDescription()
    {
        $link = Helper::getAbsoluteFolderPath(Icon::config()->get('all_icons_file'));
        $linkText = _t(Icon::class . '.DefaultFieldDescription', 'Click here to view all available icons');

        return '<a href="' . $link . '" target="_blank">' . $linkText . '</a>';
    }
}