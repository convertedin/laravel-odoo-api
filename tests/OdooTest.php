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

//        $info = Ripcord::client('https://demo.odoo.com/start', [
//            'encoding' => 'utf-8'
//        ])->start();
//
//        list($this->host, $this->db, $this->username, $this->password) =
//            array($info['host'], $info['database'], $info['user'], $info['password']);

        $this->host = "http://localhost:18069";
        $this->db = "pcweb_live";
        $this->username = 'admin';
        $this->password = 'admin';

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
        $amount = $this->odoo
            ->model('res.partner')
            ->count();
        $this->assertEquals('integer', gettype($amount));
    }

    public function testCountWhere()
    {
        $amount = $this->odoo
            ->model('res.partner')
            ->count();

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

    public function testFirst()
    {
        $item = $this->odoo
            ->model('res.partner')
            ->first();


        $this->assertArrayHasKey('name',$item);
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

    public function testDeleteRecord()
    {
        $id = $this->odoo
            ->model('res.partner')
            ->create([
                'name' => 'Bobby Brown'
            ]);

        $this->assertEquals('integer', gettype($id));

        $this->odoo
            ->model('res.partner')
            ->deleteById($id);

        $ids = $this->odoo
            ->model('res.partner')
            ->where('id', '=', $id)
            ->search();

        $this->assertEmpty($ids);
    }

    public function testDeleteSearch()
    {
        $id = $this->odoo
            ->model('res.partner')
            ->create([
                'name' => 'Bobby Brown'
            ]);

        $this->assertEquals('integer', gettype($id));

        $deleteResponse = $this->odoo
            ->model('res.partner')
            ->where('name', '=', 'Bobby Brown')
            ->delete();

        $this->assertTrue($deleteResponse);

        $ids = $this->odoo
            ->model('res.partner')
            ->where('name', '=', 'Bobby Brown')
            ->search();

        $this->assertEmpty($ids);

    }

    public function testUpdateById()
    {
        $id = $this->odoo
            ->model('res.partner')
            ->create([
                'name' => 'Bobby Brown'
            ]);

        $this->assertEquals('integer', gettype($id));

        $updateResponse = $this->odoo
            ->model('res.partner')
            ->updateById($id,[
                'name' => 'Dagobert Duck'
            ]);

        $this->assertTrue($updateResponse);

        $item = $this->odoo
            ->model('res.partner')
            ->where('id', '=', $id)
            ->fields(['name'])
            ->first();

        $this->assertEquals('Dagobert Duck', $item['name']);
    }

    public function testUpdateSearch()
    {
        $id = $this->odoo
            ->model('res.partner')
            ->create([
                'name' => 'Bobby Brown'
            ]);

        $this->assertEquals('integer', gettype($id));

        $updateResponse = $this->odoo
            ->model('res.partner')
            ->where('name', '=', 'Bobby Brown')
            ->update([
                'name' => 'Dagobert Duck'
            ]);

        $this->assertTrue($updateResponse);

        $ids = $this->odoo
            ->model('res.partner')
            ->where('name', '=', 'Bobby Brown')
            ->search();

        $this->assertEmpty($ids);

    }

    public function testCallMethodDirect(){
        $ids = $this->odoo
            ->model('res.partner')
            ->setMethod('search')
            ->setArguments([[
                ['is_company', '=', true]
            ]])
            ->setOption('limit', 3)
            ->addResponseClass(Odoo\Response\ListResponse::class)
            ->get();
        $this->assertInstanceOf(Collection::class, $ids);
        $this->assertCount(3, $ids);
    }

}