<?php
/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 26/11/2018
 * Time: 14.16
 */

namespace NobrainerWeb\IconPicker\Fields;


use NobrainerWeb\IconPicker\Helper\Helper;
use SilverStripe\Control\Director;
use SilverStripe\Core\Path;
use SilverStripe\Forms\GroupedDropdownField;

class IconField extends GroupedDropdownField
{
    /**
     * Where the SVG's are located for the backend
     *
     * @config
     */
    private static $icon_source_folder;


    public function __construct($name, $title = null)
    {
        parent::__construct($name, $title);

        $this->addExtraClass('nw-icon-picker select2 no-chzn');
        $this->setEmptyString(_t(__CLASS__ . '.EmptyString', 'Select an icon...'));
    }

    public function getSource()
    {
        return ['icons' => $this->generateSource()];
    }

    private function generateSource()
    {
        $folder = Path::join(Director::baseFolder(), self::config()->get('icon_source_folder')); // TODO cache these values

        return Helper::getSVGFiles($folder);
    }
}