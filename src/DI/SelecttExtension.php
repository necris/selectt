<?php
declare(strict_types=1);

namespace Selectt\DI;

use Selectt\SelecttAutocompleteControl;
use Selectt\SelecttInput;
use Nette\DI\CompilerExtension;
use Nette\Forms\Container;

use Nette\PhpGenerator\ClassType;
use Selectt\SelecttMultipleInput;


final class SelecttExtension extends CompilerExtension
{

	public function afterCompile(ClassType $class): void
	{
		$initializeMethod = $class->getMethod('initialize');
		$cfg = json_encode($this->getConfig());
		$initializeMethod->addBody(__CLASS__ . '::registerFormExtension(\'' . $cfg . '\');');
	}

	public static function registerFormExtension($cfg): void
	{
		$cfg = json_decode($cfg, true);

		// addSelect2()
		Container::extensionMethod('addSelect2', function (
            Container                  $container,
            string                     $name,
            SelecttAutocompleteControl $dataSource,
            ?string                    $label = NULL
		) use ($cfg) {
			$select = $container[$name] = new SelecttInput($dataSource, $label);
			if (isset($cfg['single']['jsAttributes'])) {
				foreach ($cfg['single']['jsAttributes'] as $k => $v) {
					$select->addSelect2Attribute($k, $v);
				}
			}

			if (isset($cfg['single']['class'])) {
				$select->setHtmlClass($cfg['single']['class']);
			}
            if(isset($cfg['resultsPerPage'])){
                $select->setConfigResultsPerPage((int)$cfg['resultsPerPage']);
            }

			return $select;
		});

		// addSelect2Multi()
		Container::extensionMethod('addSelect2Multi', function (
            Container                  $container,
            string                     $name,
            SelecttAutocompleteControl $dataSource,
            ?string                    $label = NULL
		) use ($cfg) {

			$select = $container[$name] = new SelecttMultipleInput($dataSource, $label);
			if (isset($cfg['multi']['jsAttributes'])) {
				foreach ($cfg['multi']['jsAttributes'] as $k => $v) {
					$select->addSelect2Attribute($k, $v);
				}
			}
			if (isset($cfg['multi']['class'])) {
				$select->setHtmlClass($cfg['multi']['class']);
			}
            if(isset($cfg['resultsPerPage'])){
                $select->setConfigResultsPerPage((int)$cfg['resultsPerPage']);
            }
			return $select;
		});

	}

}
