<?php

use PHPUnit\Framework\TestCase;

class ZabbixAgentTest extends TestCase {

    /**
     * @expectedException ZabbixAgentException
     */
    public function testCreateNoHost(){
        new ZabbixAgent(null, null);
    }

    /**
     * @expectedException ZabbixAgentException
     */
    public function testCreateNoPort(){
        new ZabbixAgent("0.0.0.0", null);
    }

    public function testCreateSuccess(){
        $agent = new ZabbixAgentSurf("1.2.3.4", 12345);

        $this->assertEquals("1.2.3.4", $agent->getHost());
        $this->assertEquals(12345, $agent->getPort());
    }

    /*public function testCreateStatic() {
        $agent1 = ZabbixAgent::create(12345);
        $this->assertEquals("1.2.3.4", $agent1->getHost());
        $this->assertEquals(12345, $agent1->getPort());
    }*/
}
