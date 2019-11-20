<?php

use Edujugon\Laradoo\Exceptions\AuthenticationException;
use Edujugon\Laradoo\Odoo;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Ripcord\Ripcord;

class OdooTest extends TestCase
{

    protected $odoo;

    /**
     * Demo credentials set.
     */
    protected $host;
    protected $db;
    protected $username;
    protected $password;

    public function __construct()
    {
        parent::__construct();

        $this->odoo = new Odoo();

        $this->setDemoCredentials();
        $this->createOdooInstance();
    }

    /**
     * Set odoo.com test credentials
     */
    protected function setDemoCredentials()
    {

        $info = Ripcord::client('https://demo.odoo.com/start', [
            'encoding' => 'utf-8'
        ])->start();

//        $info = $this->odoo->getClient('https://demo.odoo.com/start')->start();

        list($this->host, $this->db, $this->username, $this->password) =
            array($info['host'], $info['database'], $info['user'], $info['password']);

    }

    /**
     * Connect with the odoo and create the oddo instance.
     */
    protected function createOdooInstance()
    {
        $this->odoo = $this->odoo
            ->username($this->username)
            ->password($this->password)
            ->db($this->db)
            ->host($this->host)
            ->connect();
    }


    public function testOdooAuthenticationException()
    {
        $this->expectException(AuthenticationException::class);

        $this->odoo = (new Odoo())
            ->username($this->username)
            ->password($this->password.'invalid')
            ->db($this->db)
            ->host($this->host)
            ->connect();
    }

    public function testVersion()
    {
        $version = $this->odoo->version();
        $this->assertInstanceOf(Odoo\Response\VersionResponse::class, $version);
    }

    public function testSuccessfulConnection()
    {
        $this->assertEquals('integer', gettype($this->odoo->getUid()));
    }

    public function testCheckModelAccess()
    {
        $check = $this->odoo->can('read', 'res.partner');

        $this->assertTrue($check);
    }

    public function testCount()
    {
        $amount = $this->odoo->count('res.partner');
        $this->assertEquals('integer', gettype($amount));
    }

    public function testCountWhere()
    {
        $amount = $this->odoo->count('res.partner');

        $customerAmount = $this->odoo
            ->model('res.partner')
            ->where('is_company', '=', true)
            ->count();

        $this->assertLessThan($amount, $customerAmount);
    }

    public function testSearchLimit()
    {
        $ids = $this->odoo
            ->model('res.partner')
            ->limit(5)
            ->search();

        $this->assertInstanceOf(Collection::class, $ids);
    }

    public function testRead()
    {
        $ids = $this->odoo
            ->model('res.partner')
            ->limit(5)
            ->search();

        $items = $this->odoo
            ->model('res.partner')
            ->read($ids);

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertCount(5, $items->all());
    }


    public function testSearchRead()
    {
        $items = $this->odoo
            ->model('res.partner')
            ->limit(5)
            ->get();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertCount(5, $items);
        $this->assertArrayHasKey('name',$items->first());
    }

    public function testSearchReadFields()
    {
        $items = $this->odoo
            ->model('res.partner')
            ->fields('name')
            ->limit(5)
            ->get();

        $this->assertInstanceOf(Collection::class, $items);
        $this->assertCount(5, $items);
        $this->assertArrayHasKey('name',$items->first());
        $this->assertArrayNotHasKey('email',$items->first());
    }

    /** @test */
    public function testListFields()
    {
        $fields = $this->odoo
            ->model('res.partner')
            ->listModelFields();

        $this->assertInstanceOf(Collection::class, $fields);
    }

    public function testCreateRecord()
    {

        $id = $this->odoo
            ->model('res.partner')
            ->create([
                'name' => 'Bobby Brown'
            ]);

        $this->assertEquals('integer', gettype($id));
    }


    /** @test */
    public function delete_a_record()
    {
        $id = $this->odoo
            ->create('res.partner',['name' => 'John Odoo']);

        $this->assertEquals('integer', gettype($id));

        $result = $this->odoo->deleteById('res.partner',$id);

        $ids = $this->odoo
            ->where('id', $id)
            ->search('res.partner');

        $this->assertTrue($ids->isEmpty());

        $this->assertEquals('boolean', gettype($result));
    }

    /** @test */
    public function delete_two_record()
    {
        $this->odoo
            ->create('res.partner',['name' => 'John Odoo']);
        $this->odoo
            ->create('res.partner',['name' => 'John Odoo']);

        $ids = $this->odoo
            ->where('name', 'John Odoo')
            ->search('res.partner');

        $result = $this->odoo->deleteById('res.partner',$ids);

        $ids = $this->odoo
            ->where('name', 'John Odoo')
            ->search('res.partner');

        $this->assertTrue($ids->isEmpty());

        $this->assertEquals('boolean', gettype($result));
    }

    /** @test */
    public function delete_a_record_directly()
    {
        // Create a record
        $this->odoo->create('res.partner',['name' => 'John Odoo']);

        // Delete it
        $result = $this->odoo->where('name', 'John Odoo')
            ->delete('res.partner');

        $this->assertEquals('boolean', gettype($result));
    }

    /** @test */
    public function update_record_with_new_name()
    {
        // Create a record
        $initId = $this->odoo->create('res.partner',['name' => 'John Odoo','email' => 'Johndoe@odoo.com']);

        //Update the name
        $updated = $this->odoo->where('name', 'John Odoo')
            ->update('res.partner',['name' => 'John Odoo Updated','email' => 'newJohndoe@odoo.com']);

        $this->assertTrue($updated);


        //Delete the record
        $result = $this->odoo->deleteById('res.partner',$initId);

        $this->assertTrue($result);

    }

    /** @test */
    public function using_call_directly()
    {
        $ids = $this->odoo->call('res.partner', 'search',[
            [
                ['is_company', '=', true],
                ['customer', '=', true]
            ]
        ],[
            'offset'=>1,
            'limit'=>5
        ]);

        $this->assertCount(5,$ids);
    }
}