<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Repositories\Hooks;
use Joomla\Github\Tests\Stub\GitHubTestCase;

/**
 * Test class for Hooks.
 *
 * @since  1.0
 */
class HooksTest extends GitHubTestCase
{
	/**
	 * @var    Hooks  Object under test.
	 * @since  12.3
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Hooks($this->options, $this->client);
	}

	/**
	 * Tests the create method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreate()
	{
		$this->response->code = 201;
		$this->response->body = $this->sampleString;

		$hook = new \stdClass;
		$hook->name = 'acunote';
		$hook->config = array('token' => '123456789');
		$hook->events = array('push', 'public');
		$hook->active = true;

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/hooks', json_encode($hook))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->create('joomla', 'joomla-platform', 'acunote', array('token' => '123456789'), array('push', 'public')),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the create method - simulated failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreateFailure()
	{
		$exception = false;

		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$hook = new \stdClass;
		$hook->name = 'acunote';
		$hook->config = array('token' => '123456789');
		$hook->events = array('push', 'public');
		$hook->active = true;

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/hooks', json_encode($hook))
			->will($this->returnValue($this->response));

		try
		{
			$this->object->create('joomla', 'joomla-platform', 'acunote', array('token' => '123456789'), array('push', 'public'));
		}
		catch (\DomainException $e)
		{
			$exception = true;

			$this->assertThat(
				$e->getMessage(),
				$this->equalTo(json_decode($this->errorString)->message)
			);
		}
		$this->assertTrue($exception);
	}

	/**
	 * Tests the create method - unauthorised event
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \RuntimeException
	 */
	public function testCreateUnauthorisedEvent()
	{
		$this->object->create('joomla', 'joomla-platform', 'acunote', array('token' => '123456789'), array('push', 'faker'));
	}

	/**
	 * Tests the delete method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDelete()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/repos/joomla/joomla-platform/hooks/42')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->delete('joomla', 'joomla-platform', 42),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the delete method - simulated failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDeleteFailure()
	{
		$exception = false;

		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('delete')
			->with('/repos/joomla/joomla-platform/hooks/42')
			->will($this->returnValue($this->response));

		try
		{
			$this->object->delete('joomla', 'joomla-platform', 42);
		}
		catch (\DomainException $e)
		{
			$exception = true;

			$this->assertThat(
				$e->getMessage(),
				$this->equalTo(json_decode($this->errorString)->message)
			);
		}
		$this->assertTrue($exception);
	}

	/**
	 * Tests the edit method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testEdit()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$hook = new \stdClass;
		$hook->name = 'acunote';
		$hook->config = array('token' => '123456789');
		$hook->events = array('push', 'public');
		$hook->add_events = array('watch');
		$hook->remove_events = array('watch');
		$hook->active = true;

		$this->client->expects($this->once())
			->method('patch')
			->with('/repos/joomla/joomla-platform/hooks/42', json_encode($hook))
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->edit('joomla', 'joomla-platform', 42, 'acunote', array('token' => '123456789'),
				array('push', 'public'), array('watch'), array('watch')
			),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the edit method - simulated failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testEditFailure()
	{
		$exception = false;

		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$hook = new \stdClass;
		$hook->name = 'acunote';
		$hook->config = array('token' => '123456789');
		$hook->events = array('push', 'public');
		$hook->add_events = array('watch');
		$hook->remove_events = array('watch');
		$hook->active = true;

		$this->client->expects($this->once())
			->method('patch')
			->with('/repos/joomla/joomla-platform/hooks/42', json_encode($hook))
			->will($this->returnValue($this->response));

		try
		{
			$this->object->edit('joomla', 'joomla-platform', 42, 'acunote', array('token' => '123456789'),
				array('push', 'public'), array('watch'), array('watch')
			);
		}
		catch (\DomainException $e)
		{
			$exception = true;

			$this->assertThat(
				$e->getMessage(),
				$this->equalTo(json_decode($this->errorString)->message)
			);
		}
		$this->assertTrue($exception);
	}

	/**
	 * Tests the edit method - unauthorised event
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  RuntimeException
	 */
	public function testEditUnauthorisedEvent()
	{
		$this->object->edit('joomla', 'joomla-platform', 42, 'acunote', array('token' => '123456789'), array('invalid'));
	}

	/**
	 * Tests the edit method - unauthorised event
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \RuntimeException
	 */
	public function testEditUnauthorisedAddEvent()
	{
		$this->object->edit('joomla', 'joomla-platform', 42, 'acunote', array('token' => '123456789'), array('push'), array('invalid'));
	}

	/**
	 * Tests the edit method - unauthorised event
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \RuntimeException
	 */
	public function testEditUnauthorisedRemoveEvent()
	{
		$this->object->edit('joomla', 'joomla-platform', 42, 'acunote', array('token' => '123456789'), array('push'), array('push'), array('invalid'));
	}

	/**
	 * Tests the get method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGet()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/hooks/42')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->get('joomla', 'joomla-platform', 42),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the get method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \DomainException
	 */
	public function testGetFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/hooks/42')
			->will($this->returnValue($this->response));

		$this->object->get('joomla', 'joomla-platform', 42);
	}

	/**
	 * Tests the getList method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetList()
	{
		$this->response->code = 200;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/hooks')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->getList('joomla', 'joomla-platform'),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getList method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \DomainException
	 */
	public function testGetListFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('get')
			->with('/repos/joomla/joomla-platform/hooks')
			->will($this->returnValue($this->response));

		$this->object->getList('joomla', 'joomla-platform');
	}

	/**
	 * Tests the test method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testTest()
	{
		$this->response->code = 204;
		$this->response->body = $this->sampleString;

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/hooks/42/test')
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->test('joomla', 'joomla-platform', 42),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the test method - failure
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  \DomainException
	 */
	public function testTestFailure()
	{
		$this->response->code = 500;
		$this->response->body = $this->errorString;

		$this->client->expects($this->once())
			->method('post')
			->with('/repos/joomla/joomla-platform/hooks/42/test')
			->will($this->returnValue($this->response));

		$this->object->test('joomla', 'joomla-platform', 42);
	}
}
