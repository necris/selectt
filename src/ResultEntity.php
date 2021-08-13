<?php

declare(strict_types=1);

namespace Selectt;


class ResultEntity
{

	public function __construct($id = null, $text = null)
	{
		$this->id = $id;
		$this->text = $text;
	}

	/**
	 * @var int|null
	 */
	private $id;

	/**
	 * @var string
	 */
	private $text;

	/**
	 * @var bool
	 */
	private $selected = FALSE;


	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @param mixed $id
	 */
	public function setId($id): void
	{
		$this->id = $id;
	}


	public function getText(): string
	{
		return $this->text;
	}


	public function setText(string $text): void
	{
		$this->text = $text;
	}


	public function isSelected(): bool
	{
		return $this->selected;
	}


	public function setSelected(bool $selected): void
	{
		$this->selected = $selected;
	}

	public function __toString(): string
	{
		return $this->text;
	}

}
