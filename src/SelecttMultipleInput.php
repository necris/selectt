<?php

namespace Selectt;

use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Helpers;
use Nette\InvalidArgumentException;
use Nette\Utils\Json;


class SelecttMultipleInput extends MultiSelectBox
{
    private SelecttAutocompleteControl $dataSourceControl;
    /** @var ResultEntity[] */
    private array $selectedValues = [];
    private $select2atts = [];

    private string $htmlClass = "select2-ajax-multi";

    public function __construct(SelecttAutocompleteControl $dataSourceControl, $label = null)
    {
        $this->dataSourceControl = $dataSourceControl;
        $this->select2atts['multiple'] = true;
        parent::__construct($label, []);
    }


    public function getControl(): \Nette\Utils\Html
    {
        //return parent::getControl();
        $attributes = [];
        $attributes['data-select2-url'] = $this->getDataSourceControl()->getLink();
        $attributes['name'] = $this->getName() . "[]";
        $attributes['multiple'] = "multiple";
        $attributes['class'] = $this->getHtmlClass();
        $attributes['data-select2-params'] = "{}";
        if (count($this->select2atts) > 0) {
            $attributes['data-select2-params'] = JSON::encode($this->select2atts);
        }

        $attributes['disabled'] = $this->disabled;


        $items = $this->getSelectedItems();

        $ctrl = Helpers::createSelectBox($items, NULL, array_keys($items))->addAttributes($attributes);
        return $ctrl;
    }

    public function getDataSourceControl(): SelecttAutocompleteControl
    {
        return $this->dataSourceControl;
    }


    public function getValue(): array
    {

        //return array_values(array_intersect($this->value, array_keys($this->items)));
        return $this->value;
    }

    public function setValue($values)
    {
        if (is_scalar($values) || $values === null) {
            $values = (array)$values;
        } elseif (!is_array($values)) {
            throw new \Nette\InvalidArgumentException(sprintf("Value must be array or null, %s given in field '%s'.", gettype($values), $this->name));
        }
        $flip = [];
        foreach ($values as $value) {
            if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
                throw new \Nette\InvalidArgumentException(sprintf("Values must be scalar, %s given in field '%s'.", gettype($value), $this->name));
            }
            $flip[(string)$value] = true;
        }
        $values = array_keys($flip);

        if (count($values)) {

            $items = $this->dataSourceControl->getDataSource()->findByKeys($values);
            if (!$items) {
                throw new InvalidArgumentException('Unexpected values!');
            }

            foreach ($items as $item) {
                $this->selectedValues[$item->getId()] = $item;
            }
        }

        $this->value = $values;
        return $this;
    }

    public function getSelectedItems(): array
    {
        foreach ($this->selectedValues as $k => $v) {
            if (!in_array($k, $this->value)) {
                unset($this->selectedValues[$k]);
            }
        }

        $this->setValue($this->value);

        $ret = [];
        foreach ($this->selectedValues as $k => $v) {
            $ret[$k] = $v->getText();
        }
        return $ret;
    }

    public function addSelect2Attribute(string $name, $value = null): self
    {
        $this->select2atts[$name] = $value;
        return $this;
    }

    public function setHtmlClass(string $class): self
    {
        $this->htmlClass = $class;
        return $this;
    }

    private function getHtmlClass(): string
    {
        return $this->htmlClass;
    }
    public function setConfigResultsPerPage(int $configResultsPerPage): SelecttMultipleInput
    {
        $this->dataSourceControl->setConfigResultsPerPage($configResultsPerPage);
        return $this;
    }
}
