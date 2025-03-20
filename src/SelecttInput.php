<?php

namespace Selectt;

use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Helpers;
use Nette\InvalidArgumentException;
use Nette\Utils\Json;


class SelecttInput extends SelectBox
{
	private SelecttAutocompleteControl $dataSource;
	private ?ResultEntity $selectedValue;
	private $select2atts = [];

	private string $htmlClass = "select2-ajax";

	public function __construct(SelecttAutocompleteControl $dataSourceControl, $label = null)
	{
		$this->dataSource = $dataSourceControl;
		parent::__construct($label, []);

	}


	public function getControl(): \Nette\Utils\Html
	{
        $attributes = parent::getControl()->attributes;
		//$attributes = [];
		$attributes['data-select2-url'] = $this->getDataSource()->getLink();
		$attributes['name'] = $this->getName();
		$attributes['class'] = $this->getHtmlClass();
		$attributes['disabled'] = $this->disabled;
		$attributes['data-select2-params'] = "{}";
		if (count($this->select2atts) > 0) {
			$attributes['data-select2-params'] = JSON::encode($this->select2atts);
		}

		$items = $this->getSelectedItems();

        $ctrl = Helpers::createSelectBox($items, NULL, array_keys($items))
            ->addAttributes($attributes);

        foreach (parent::getControl()->attrs as $key => $value) {
            $ctrl->appendAttribute($key, $value);
        }

		return $ctrl;
	}

	public function getDataSource(): SelecttAutocompleteControl
	{
		return $this->dataSource;
	}


	public function getValue(): null|string|int|float
	{
		return $this->value;
	}

	public function setValue($value): self
	{


		$this->selectedValue = NULL;
		if ($value !== NULL) {
			$item = $this->getDataSource()->getDataSource()->findByKey($value);
			if (!$item) {
				throw new InvalidArgumentException(sprintf('Value "%s" is not allowed!', $value));
			}

			$item->setSelected(TRUE);
			$this->selectedValue = $item;
		}

		$this->value = $value === null ? null : key([(string)$value => null]);

		return $this;
	}

	protected function getSelectedItems(): array
	{
        $this->setValue($this->value);
		return $this->selectedValue !== NULL ? [$this->selectedValue->getId() => (string)$this->selectedValue] : [];
	}

	public function addSelect2Attribute(string $name, $value = null): self
	{
		$this->select2atts[$name] = $value;
		return $this;
	}

	private function getHtmlClass(): string
	{
		return $this->htmlClass;
	}


	public function setHtmlClass(string $class): self
	{
		$this->htmlClass = $class;
		return $this;
	}

    public function setConfigResultsPerPage(int $configResultsPerPage): SelecttInput
    {
        $this->dataSource->setConfigResultsPerPage($configResultsPerPage);
        return $this;
    }


}
