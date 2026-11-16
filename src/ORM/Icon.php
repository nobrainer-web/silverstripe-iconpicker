<?php

namespace NobrainerWeb\IconPicker\ORM;

use NobrainerWeb\IconPicker\Fields\IconTextField;
use NobrainerWeb\IconPicker\Helper\Helper;
use SilverStripe\Core\Path;
use SilverStripe\Forms\FormField;
use SilverStripe\ORM\FieldType\DBVarchar;

class Icon extends DBVarchar
{
    /**
     * Where the SVG's are located for the frontend
     *
     * @config
     */
    private static $template_icon_source_folder;

    /**
     * Where the SVG's are located for the CMS
     *
     * @config
     */
    private static $cms_icon_source_folder;

    /**
     * A html file that shows all available icons. Used for setDescription on formfields
     *
     * @config
     */
    private static $all_icons_file;

    /**
     * @var array
     */
    private static $casting = [
        'Name' => 'Text',
        'SVG'  => 'HTMLText',
    ];

    public function setSourceFolder($folder)
    {
        static::config()->set('template_icon_source_folder', $folder);

        return $this;
    }

    /***
     * @return string
     */
    public function getSourceFolder()
    {
        return self::config()->get('template_icon_source_folder');
    }

    /**
     * @param null $title
     * @param null $params
     * @return IconTextField
     */
    public function scaffoldFormField(?string $title = null, array $params = []): ?FormField
    {
        return IconTextField::create($this->name, $title);
    }

    /**
     * @return bool|string
     * @throws \SilverStripe\Core\Validation\ValidationException
     */
    public function SVG()
    {
        return $this->getSVGContents();
    }

    public function getSVGContents()
    {
        $icon = $this->Value;
        if (!$icon) {
            return '';
        }
        $relative = Path::join($this->getSourceFolder(), $icon . '.svg');

        // Read from disk. (Before SS6 this did an HTTP request to absoluteBaseURL() . '/' . $relative,
        // which only worked when the web root was the project root and gave warnings otherwise.)
        $file = Helper::getRelativeFolderPath($relative);
        if (is_file($file)) {
            return file_get_contents($file);
        }

        // Fallback for source folders that are only reachable over HTTP
        $contents = @file_get_contents(Helper::getAbsoluteFolderPath($relative));

        return $contents === false ? '' : $contents;
    }

}
