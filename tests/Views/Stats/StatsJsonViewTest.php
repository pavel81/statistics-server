<?php
/**
 * Joomla! Statistics Server
 *
 * @copyright  Copyright (C) 2013 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Joomla\StatsServer\Tests\Views\Stats;

use PHPUnit\Framework\TestCase;
use Joomla\StatsServer\Models\StatsModel;
use Joomla\StatsServer\Views\Stats\StatsJsonView;

/**
 * Test class for \Joomla\StatsServer\Views\Stats\StatsJsonView
 */
class StatsJsonViewTest extends TestCase
{
	/**
	 * @testdox The authorized raw flag is set to the view
	 *
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::isAuthorizedRaw
	 */
	public function testTheAuthorizedRawFlagIsSetToTheView()
	{
		$mockModel = $this->getMockBuilder(StatsModel::class)
			->disableOriginalConstructor()
			->getMock();

		$authorizedRaw = true;

		$view = new StatsJsonView($mockModel);
		$view->isAuthorizedRaw($authorizedRaw);

		$this->assertAttributeSame($authorizedRaw, 'authorizedRaw', $view);
	}

	/**
	 * @testdox The statistics data is returned
	 *
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::buildResponseData
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::render
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::sanitizeData
	 */
	public function testTheStatisticsDataIsReturned()
	{
		$mockModel = $this->getMockBuilder(StatsModel::class)
			->disableOriginalConstructor()
			->getMock();

		$mockModel->expects($this->once())
			->method('getItems')
			->willReturn(
				[
					'cms_version' => [
						[
							'cms_version' => '3.5.0',
							'count'       => 3,
						],
					],
					'php_version' => [
						[
							'php_version' => PHP_VERSION,
							'count'       => 3,
						],
					],
					'db_type' => [
						[
							'db_type' => 'mysql',
							'count'   => 1,
						],
						[
							'db_type' => 'postgresql',
							'count'   => 1,
						],
						[
							'db_type' => 'sqlsrv',
							'count'   => 1,
						],
					],
					'db_version' => [
						[
							'db_version' => '5.6.25',
							'count'      => 1,
						],
						[
							'db_version' => '9.4.0',
							'count'      => 1,
						],
						[
							'db_version' => '10.50.2500',
							'count'      => 1,
						],
					],
					'server_os' => [
						[
							'server_os' => 'Darwin 14.1.0',
							'count'     => 2,
						],
						[
							'server_os' => '',
							'count'     => 1,
						],
					],
				]
			);

		$phpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;

		$returnData = [
			'data' => [
				'php_version' => [$phpVersion => 100],
				'db_type'     => ['mysql' => round((1 / 3) * 100, 2), 'postgresql' => round((1 / 3) * 100, 2), 'sqlsrv' => round((1 / 3) * 100, 2)],
				'db_version'  => ['5.6' => round((1 / 3) * 100, 2), '9.4' => round((1 / 3) * 100, 2), '10.50' => round((1 / 3) * 100, 2)],
				'cms_version' => ['3.5' => 100],
				'server_os'   => ['Darwin' => round((2 / 3) * 100, 2), 'unknown' => round((1 / 3) * 100, 2)],
				'total'       => 3
			]
		];

		$view = new StatsJsonView($mockModel);

		$this->assertSame($returnData, json_decode($view->render(), true));
	}

	/**
	 * @testdox The raw statistics data is returned
	 *
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::buildResponseData
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::render
	 */
	public function testTheRawStatisticsDataIsReturned()
	{
		$mockModel = $this->getMockBuilder(StatsModel::class)
			->disableOriginalConstructor()
			->getMock();

		$mockModel->expects($this->once())
			->method('getItems')
			->willReturn(
				[
					'cms_version' => [
						[
							'cms_version' => '3.5.0',
							'count'       => 3,
						],
					],
					'php_version' => [
						[
							'php_version' => PHP_VERSION,
							'count'       => 3,
						],
					],
					'db_type' => [
						[
							'db_type' => 'mysql',
							'count'   => 1,
						],
						[
							'db_type' => 'postgresql',
							'count'   => 1,
						],
						[
							'db_type' => 'sqlsrv',
							'count'   => 1,
						],
					],
					'db_version' => [
						[
							'db_version' => '5.6.25',
							'count'      => 1,
						],
						[
							'db_version' => '9.4.0',
							'count'      => 1,
						],
						[
							'db_version' => '10.50.2500',
							'count'      => 1,
						],
					],
					'server_os' => [
						[
							'server_os' => 'Darwin 14.1.0',
							'count'     => 2,
						],
						[
							'server_os' => '',
							'count'     => 1,
						],
					],
				]
			);

		$returnData = [
			'data' => [
				'php_version' => [
					[
						'name'  => PHP_VERSION,
						'count' => 3
					]
				],
				'db_type'     => [
					[
						'name'  => 'mysql',
						'count' => 1
					],
					[
						'name'  => 'postgresql',
						'count' => 1
					],
					[
						'name'  => 'sqlsrv',
						'count' => 1
					],
				],
				'db_version'  => [
					[
						'name'  => '5.6.25',
						'count' => 1
					],
					[
						'name'  => '9.4.0',
						'count' => 1
					],
					[
						'name'  => '10.50.2500',
						'count' => 1
					],
				],
				'cms_version' => [
					[
						'name'  => '3.5.0',
						'count' => 3
					],
				],
				'server_os'   => [
					[
						'name'  => 'Darwin 14.1.0',
						'count' => 2
					],
					[
						'name'  => 'unknown',
						'count' => 1
					],
				],
				'total'       => 3
			]
		];

		$view = new StatsJsonView($mockModel);
		$view->isAuthorizedRaw(true);

		$this->assertSame($returnData, json_decode($view->render(), true));
	}

	/**
	 * @testdox The statistics data for a single source is returned
	 *
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::buildResponseData
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::processSingleSource
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::render
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::sanitizeData
	 * @uses    Joomla\StatsServer\Views\Stats\StatsJsonView::setSource
	 */
	public function testTheStatisticsDataForASingleSourceIsReturned()
	{
		$mockModel = $this->getMockBuilder(StatsModel::class)
			->disableOriginalConstructor()
			->getMock();

		$mockModel->expects($this->once())
			->method('getItems')
			->willReturn(
				[
					[
						'php_version' => PHP_VERSION, 'count' => 3
					],
				]
			);

		$phpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;

		$returnData = [
			'data' => [
				'php_version' => [$phpVersion => 100],
				'total'       => 3
			]
		];

		$view = new StatsJsonView($mockModel);
		$view->setSource('php_version');

		$this->assertSame($returnData, json_decode($view->render(), true));
	}

	/**
	 * @testdox The statistics data for the server OS source is returned
	 *
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::buildResponseData
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::processSingleSource
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::render
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::sanitizeData
	 * @uses    Joomla\StatsServer\Views\Stats\StatsJsonView::setSource
	 */
	public function testTheStatisticsDataForTheServerOsSourceIsReturned()
	{
		$mockModel = $this->getMockBuilder(StatsModel::class)
			->disableOriginalConstructor()
			->getMock();

		$mockModel->expects($this->once())
			->method('getItems')
			->willReturn(
				[
					[
						'count' => 2, 'server_os' => 'Darwin 14.1.0',
					],
					[
						'count' => 1, 'server_os' => '',
					],
				]
			);

		$phpVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;

		$returnData = [
			'data' => [
				'server_os' => ['Darwin' => round((2 / 3) * 100, 2), 'unknown' => round((1 / 3) * 100, 2)],
				'total'     => 3
			]
		];

		$view = new StatsJsonView($mockModel);
		$view->setSource('server_os');

		$this->assertSame($returnData, json_decode($view->render(), true));
	}

	/**
	 * @testdox The data source is set to the view
	 *
	 * @covers  Joomla\StatsServer\Views\Stats\StatsJsonView::setSource
	 */
	public function testTheDataSourceIsSetToTheView()
	{
		$mockModel = $this->getMockBuilder(StatsModel::class)
			->disableOriginalConstructor()
			->getMock();

		$source = 'php_version';

		$view = new StatsJsonView($mockModel);
		$view->setSource('php_version');

		$this->assertAttributeSame($source, 'source', $view);
	}
}
