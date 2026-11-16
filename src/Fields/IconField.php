<?php
/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 26/11/2018
 * Time: 14.16
 */

namespace NobrainerWeb\IconPicker\Fields;


use SilverStripe\Model\List\ArrayList;
use SilverStripe\Model\ArrayData;
use NobrainerWeb\IconPicker\Helper\Helper;
use NobrainerWeb\IconPicker\Traits\FieldTraits;
use SilverStripe\Core\Path;
use SilverStripe\Core\Validation\ValidationResult;
use SilverStripe\Forms\GroupedDropdownField;
use SilverStripe\View\Requirements;

class IconField extends GroupedDropdownField
{
    use FieldTraits;

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
     * @param \SilverStripe\Forms\Validation\Validator $validator
     * @return bool
     */
    public function validate(): ValidationResult
    {
        $result = ValidationResult::create();

        // Check if valid value is given
        $selected = $this->getValue();
        if ($selected === null || $selected === '') {
            return $result;
        }
        $path = Path::join($this->getSourceFolder(), $selected . '.svg');
        $path = Helper::getRelativeFolderPath($path);

        // check if file exists
        if (!file_exists($path)) {
            $result->addFieldError(
                $this->getName(),
                _t(
                    'SilverStripe\\Forms\\DropdownField.SOURCE_VALIDATION',
                    'Please select a value within the list provided. {value} is not a valid option',
                    ['value' => $selected]
                ),
                'validation'
            );
        }
        $this->extend('updateValidate', $result);

        return $result;
    }

    /**
     * Build a potentially nested fieldgroup
     *
     * @param mixed $valueOrGroup Value of item, or title of group
     * @param string|array $titleOrOptions Title of item, or options in grouip
     * @return \SilverStripe\Model\ArrayData Data for this item
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
        $selected = $this->isSelectedValue($value, $this->getValue());

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
        // jQuery and entwine are part of the SS6 admin bundle (silverstripe/admin client/dist/js/vendor.js)

        Requirements::css('nobrainerweb/silverstripe-iconpicker:client/select2/css/select2.min.css');
        Requirements::javascript('nobrainerweb/silverstripe-iconpicker:client/select2/js/select2.full.min.js');

        Requirements::javascript('nobrainerweb/silverstripe-iconpicker:client/nw-iconpicker.js');
        Requirements::css('nobrainerweb/silverstripe-iconpicker:client/nw-iconpicker.css');

        $this->addExtraClass('nw-icon-picker js-nw-icon-picker select2 no-chosen');
        $this->setEmptyString(_t(__CLASS__ . '.EmptyString', 'Select an icon...'));

        return parent::Field($properties);
    }
}