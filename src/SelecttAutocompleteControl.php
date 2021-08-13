<?php

namespace Selectt;


use Nette\Application\UI\Control;
use stdClass;

/**
 *
 */
class SelecttAutocompleteControl extends Control
{

	private const QUERY_PARAM_NAME = "q";
	private const PAGE_PARAM_NAME = "page";
	private const RESULTS_PER_PAGE = 10;

	private SelecttDataSource $dataSource;
	private bool $enableEmptyQuery = false;

	public function __construct(SelecttDataSource $dataSource)
	{
		$this->dataSource = $dataSource;
	}

	/**
	 * @param string $destination
	 * @param array $args
	 * @return string
	 * @throws \Nette\Application\UI\InvalidLinkException
	 */
	public function getLink(string $destination = 'autocomplete!', $args = []): string
	{
		return $this->link($destination, $args);
	}


	public function handleAutocomplete(): void
	{
		$query = $this->getPresenter()->getParameter(self::QUERY_PARAM_NAME);
		$page = max((int)$this->getPresenter()->getParameter(self::PAGE_PARAM_NAME, 1), 1);

		$return = [
			'results' => [],
			'total_count' => 0,
			'pagination' => [
				'more' => false
			]
		];

		if (!$this->enableEmptyQuery && empty($query)) {
			$this->sendJson($return);
		}

		$count = $this->getDataSource()->searchTermCount($query);

		if (!$count) {
			$this->sendJson($return);
		}

		$offsetStart = ($page - 1) * self::RESULTS_PER_PAGE;
		$offsetEnd = $offsetStart + self::RESULTS_PER_PAGE;

		if ($offsetEnd < $count) {
			$return['pagination']['more'] = TRUE;
		}
		$return['total_count'] = $count;

		$results = $this->getDataSource()->searchTerm($query, self::RESULTS_PER_PAGE, $offsetStart);
		foreach ($results as $result) {
			$return['results'][] = $this->formatResult($result);
		}

		$this->sendJson($return);
	}

	/**
	 * @return SelecttDataSource
	 */
	public function getDataSource(): SelecttDataSource
	{
		return $this->dataSource;
	}

	/**
	 * @param ResultEntity $result
	 * @return stdClass
	 */
	private function formatResult(ResultEntity $result): stdClass
	{
		$row = [
			'id' => $result->getId(),
			'text' => (string)$result
		];

		if ($result->isSelected()) {
			$row['selected'] = TRUE;
		}

		return (object)$row;
	}

	/**
	 * @param $data
	 * @throws \Nette\Application\AbortException
	 */
	private function sendJson($data): void
	{
		$this->getPresenter()->sendJson($data);
	}

	public function enableEmptyQuery(bool $enable = true): self
	{
		$this->enableEmptyQuery = $enable;
		return $this;
	}


}
