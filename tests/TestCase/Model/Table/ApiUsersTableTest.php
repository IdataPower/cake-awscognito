<?php
namespace PowerSystem\CognitoSDK\Test\TestCase\Model\Table;

use PowerSystem\CognitoSDK\Model\Table\ApiUsersTable;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Result;

use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class ApiUsersTableTest extends TestCase
{

	public $fixtures = [
		'plugin.PowerSystem/CognitoSDK.api_users'
	];

	public function setUp()
	{
		parent::setUp();
		$this->ApiUsers = TableRegistry::get('PowerSystem/CognitoSDK.ApiUsers');
	}

	public function tearDown()
	{
		unset($this->ApiUsers);
		TableRegistry::clear();
		parent::tearDown();
	}


	public function testValidationDefault()
	{
		$roles = [
			'user' => 'User',
			'admin' => 'Admin'
		];
		Configure::write('ApiUsers.roles', $roles);

		$entity = $this->ApiUsers->newEntity([
			'email' => 'lorenzo@PowerSystem.com.ar'
        ], [
            'accessibleFields' => ['email' => true]
        ]);

		//presence required
		$this->assertArrayHasKey('_required', $entity->getError('role'));
		$this->assertArrayHasKey('_required', $entity->getError('first_name'));
		$this->assertArrayHasKey('_required', $entity->getError('last_name'));

		//notEmpty
		$entity = $this->ApiUsers->newEntity([
			'role' => null,
			'first_name' => null,
			'last_name' => null,
		]);
		$this->assertArrayHasKey('_empty', $entity->getError('role'));
		$this->assertArrayHasKey('_empty', $entity->getError('first_name'));
		$this->assertArrayHasKey('_empty', $entity->getError('last_name'));

		//inList
		$entity = $this->ApiUsers->newEntity(['role' => 'not_a_role']);
		$this->assertArrayHasKey('inList', $entity->getError('role'));
	}

	public function testGetRolesFailedEmptySettings()
	{
		Configure::write('ApiUsers.roles', null);
		$this->expectExceptionMessage(__d('PowerSystem/CognitoSDK', 'ApiUsers.roles setting is invalid'));
		$this->ApiUsers->getRoles();

	}

	public function testGetRolesSuccess()
	{
		$roles = [
			'user' => 'User',
			'admin' => 'Admin'
		];

		Configure::write('ApiUsers.roles', $roles);
		$this->assertEquals($roles, $this->ApiUsers->getRoles());
	}

	public function testGetRolesFailedConfigArrayNotAssociative()
	{
		$roles = ['user', 'admin'];
		$this->expectExceptionMessage(__d('PowerSystem/CognitoSDK', 'The ApiUsers.roles array should be entirely associative'));
		Configure::write('ApiUsers.roles', $roles);
		$this->ApiUsers->getRoles();
	}

}
