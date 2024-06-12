<?php

use Dsjsdz\Signatory\Signatory;
use PHPUnit\Framework\TestCase;

class SignatoryTest extends TestCase
{

    public function testCheckSignature()
    {
        try {
            $signer = new Signatory(getenv("APP_KEY"));
        } catch (\Dsjsdz\Signatory\Error $e) {
            $this->fail($e->getMessage());
        }

        $args = [
            "a" => 2,
            "b" => 3,
            "timestamp" => "1718180788",
            "sign" => "8BD7FD74204B0204CBF365F20287D436",
        ];

        try {
            $sign = $signer->genSignature($args);
            $this->assertEquals($args["sign"], $sign, "signature done");

            $result = $signer->checkSignature($args, $args["sign"]);
            $this->assertTrue($result, "checkSignature done");
        } catch (\Dsjsdz\Signatory\Error $e) {
            $this->fail($e->getMessage());
        }
    }
}