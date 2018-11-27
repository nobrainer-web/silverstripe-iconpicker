<?php

namespace NobrainerWeb\IconPicker\ORM;

use NobrainerWeb\IconPicker\Fields\IconField;
use NobrainerWeb\IconPicker\Helper\Helper;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Path;
use SilverStripe\ORM\Connect\MySQLDatabase;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\ValidationException;

class Icon extends DBField
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

    /**
     * (non-PHPdoc)
     *
     * @see DBField::requireField()
     */
    public function requireField()
    {
        $charset = Config::inst()->get(MySQLDatabase::class, 'charset');
        $collation = Config::inst()->get(MySQLDatabase::class, 'collation');

        $parts = [
            'datatype'      => 'varchar',
            'precision'     => 25,
            'character set' => $charset,
            'collate'       => $collation,
            'arrayValue'    => $this->arrayValue
        ];

        $values = [
            'type'  => 'varchar',
            'parts' => $parts
        ];

        DB::require_field($this->tableName, $this->name, $values);
    }

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

    public function forTemplate()
    {
        return $this->SVG();
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
