<?php

namespace Stripe;

/*
 * These tests should really be part of `FileTest`, but because the file creation requests use a
 * different host, the tests for these methods need their own setup and teardown methods.
 */
class FileCreationTest extends TestCase
{
    /**
     * @before
     */
    public function setUpUploadBase()
    {
        Stripe::$apiUploadBase = Stripe::$apiBase;
        Stripe::$apiBase = null;
    }

    /**
     * @after
     */
    public function tearDownUploadBase()
    {
        Stripe::$apiBase = Stripe::$apiUploadBase;
        Stripe::$apiUploadBase = 'https://files.stripe.com';
    }

    public function testIsCreatableWithFileHandle()
    {
        $this->expectsRequest(
            'post',
            '/v1/files',
            null,
            ['Content-Type: multipart/form-data'],
            true,
            Stripe::$apiUploadBase
        );
        $fp = \fopen(\dirname(__FILE__) . '/../data/test.png', 'r');
        $resource = File::create([
            "purpose" => "dispute_evidence",
            "file" => $fp,
            "file_link_data" => ["create" => true]
        ]);
        $this->assertInstanceOf(\Stripe\File::class, $resource);
    }

    public function testIsCreatableWithCURLFile()
    {
        $this->expectsRequest(
            'post',
            '/v1/files',
            null,
            ['Content-Type: multipart/form-data'],
            true,
            Stripe::$apiUploadBase
        );
        $curlFile = new \CURLFile(\dirname(__FILE__) . '/../data/test.png');
        $resource = File::create([
            "purpose" => "dispute_evidence",
            "file" => $curlFile,
            "file_link_data" => ["create" => true]
        ]);
        $this->assertInstanceOf(\Stripe\File::class, $resource);
    }
}
