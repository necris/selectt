<?php

namespace Selectt;


/**
 * This interface has to be implemented in your own datasource and given to Select2AutocompleteControl object
 */
interface SelecttDataSource
{
	/**
	 * @param string $query
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function searchTerm(?string $query, int $limit, int $offset): array;

	/**
	 * @param string $query
	 * @return int
	 */
	public function searchTermCount(?string $query): int;

	/**
	 * @param mixed $key
	 * @return ResultEntity|NULL
	 */
	public function findByKey($key): ?ResultEntity;

	/**
	 * @param array $keys
	 * @return ResultEntity[]
	 */
	public function findByKeys(array $keys): array;


}
