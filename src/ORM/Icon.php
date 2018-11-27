<?php

namespace NobrainerWeb\IconPicker\ORM;

use NobrainerWeb\IconPicker\Fields\IconField;
use NobrainerWeb\IconPicker\Helper\Helper;
use SilverStripe\Core\Path;
use SilverStripe\ORM\FieldType\DBVarchar;
use SilverStripe\ORM\ValidationException;

class Icon extends DBVarchar
{
    /**
     * Where the SVG's are located for the frontend
     *
     * @config
     */
    private static $icon_source_folder;

    /**
     * @var array
     */
    private static $casting = [
        'Name' => 'Text',
        'SVG'  => 'HTMLText',
    ];

    public function setSourceFolder($folder)
    {
        self::config()->update('icon_source_folder', $folder);

        return $this;
    }

    /***
     * @return string
     */
    public function getSourceFolder()
    {
        return self::config()->get('icon_source_folder');
    }

    /**
     * @param null $title
     * @param null $params
     * @return IconField
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        return IconField::create($this->name, $title);
    }

    /**
     * @return bool|string
     * @throws ValidationException
     */
    public function SVG()
    {
        $icon = $this->Value;
        $path = Helper::getAbsoluteFolderPath(Path::join($this->getSourceFolder(), $icon . '.svg'));

        return Helper::getSVGContents($path);
    }

}
