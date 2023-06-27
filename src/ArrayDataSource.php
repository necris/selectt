<?php

namespace Selectt;

/**
 * Sample Array Datasource implementation
 */
class ArrayDataSource implements SelecttDataSource
{

	private array $data;

	public function __construct(array $data)
	{
		$this->data = $data;
	}

	private function searchData(?string $query): array
	{
		$query = strtolower($query ?? "");
		$return = [];
		foreach ($this->data as $key => $val) {
			if (strpos(strtolower($val), $query) !== false) {
				$return[$key] = $val;
			}
		}
		return $return;
	}

	/**
	 * @param string $query
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function searchTerm(?string $query, int $limit, int $offset): array
	{
		$return = [];
		foreach ($this->searchData($query) as $k => $v) {
			$return[] = new ResultEntity($k, $v);
		}

		return $return;
	}

	/**
	 * @param string $query
	 * @return int
	 */
	public function searchTermCount(?string $query): int
	{
		return count($this->searchData($query));
	}

	/**
	 * @param mixed $key
	 * @return ResultEntity|null
	 */
	public function findByKey($key): ?ResultEntity
	{
		if (isset($this->data[$key])) {
			$e = new ResultEntity($key, $this->data[$key]);
			return $e;
		}
		return null;
	}

	/**
	 * @param array $keys
	 * @return array
	 */
	public function findByKeys(array $keys): array
	{
		$ret = [];
		foreach ($keys as $key) {
			$ret[] = $this->findByKey($key);
		}
		return $ret;

	}
}
