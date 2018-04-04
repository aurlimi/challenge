<?php
namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Supplier;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SupplierTest extends WebTestCase
{
    protected $object;

    public function setUp() {
        $this->object = new Supplier();
    }

    public function test_setter_and_getter() {
        $this->object->setName('FR 1');
        $this->assertEquals('FR 1', $this->object->getName());
    }
}