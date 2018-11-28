<?php
/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 26/11/2018
 * Time: 14.16
 */

namespace NobrainerWeb\IconPicker\Fields;


use NobrainerWeb\IconPicker\Helper\Helper;
use SilverStripe\Core\Path;
use SilverStripe\Forms\GroupedDropdownField;
use SilverStripe\Forms\Validator;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;

class IconField extends GroupedDropdownField
{
    public function getSource()
    {
        return ['icons' => $this->generateSource()];
    }

    /**
     * @return string
     */
    public function getSourceFolder()
    {
        $path = Helper::getCMSSourceFolder();

        $this->extend('updateSourceFolder', $path);

        return $path;
    }

    private function generateSource()
    {
        $folder = Helper::getRelativeFolderPath($this->getSourceFolder()); // TODO cache these values

        return Helper::getSVGFiles($folder);
    }

    /**
     * Validate this field
     *
     * @param Validator $validator
     * @return bool
     */
    public function validate($validator)
    {
        // Check if valid value is given
        $selected = $this->Value();
        $path = Path::join($this->getSourceFolder(), $selected . '.svg');
        $path = Helper::getRelativeFolderPath($path);

        // check if file exists
        if(file_exists($path)){
            return true;
        }

        // Fail
        $validator->validationError(
            $this->name,
            _t(
                'SilverStripe\\Forms\\DropdownField.SOURCE_VALIDATION',
                'Please select a value within the list provided. {value} is not a valid option',
                ['value' => $selected]
            ),
            'validation'
        );
        return false;
    }

    /**
     * Build a potentially nested fieldgroup
     *
     * @param mixed $valueOrGroup Value of item, or title of group
     * @param string|array $titleOrOptions Title of item, or options in grouip
     * @return ArrayData Data for this item
     */
    protected function getFieldOption($valueOrGroup, $titleOrOptions)
    {
        // Return flat option
        if (!\is_array($titleOrOptions)) {
            return $this->getSingleFieldOption($valueOrGroup, $titleOrOptions);
        }

        // Build children from options list
        $options = ArrayList::create();
        foreach ($titleOrOptions as $childValue => $childTitle) {
            $options->push($this->getSingleFieldOption($childValue, $childTitle));
        }

        return new ArrayData([
            'Title' => $valueOrGroup,
            'Options' => $options
        ]);
    }

    protected function getSingleFieldOption($value, $title)
    {
        // Check selection
        $selected = $this->isSelectedValue($value, $this->Value());

        // Check disabled
        $disabled = false;
        if ($this->isDisabledValue($value) && $title != $this->getEmptyString()) {
            $disabled = 'disabled';
        }
        // check if an SVG exists with this name
        $path = Path::join($this->getSourceFolder(), $value . '.svg');
        $svg = Helper::getRelativeFolderPath($path);
        $svg = file_exists($svg) ? $path : null;

        return ArrayData::create([
            'Title' => $title,
            'Value' => $value,
            'Icon' => $svg,
            'Selected' => $selected,
            'Disabled' => $disabled,
        ]);
    }

    public function Field($properties = [])
    {
        Requirements::javascript('silverstripe/admin: thirdparty/jquery/jquery.js');
        Requirements::javascript('silverstripe/admin: thirdparty/jquery-entwine/dist/jquery.entwine-dist.js');

        Requirements::css('nobrainerweb/silverstripe-iconpicker:client/select2/css/select2.min.css');
        Requirements::javascript('nobrainerweb/silverstripe-iconpicker:client/select2/js/select2.full.min.js');

        Requirements::javascript('nobrainerweb/silverstripe-iconpicker:client/nw-iconpicker.js');
        Requirements::css('nobrainerweb/silverstripe-iconpicker:client/nw-iconpicker.css');

        $this->addExtraClass('nw-icon-picker js-nw-icon-picker select2 no-chosen');
        $this->setDescription(Helper::getFieldDescription($this));
        $this->setEmptyString(_t(__CLASS__ . '.EmptyString', 'Select an icon...'));

        return parent::Field($properties);
    }
}