<?php

namespace Awesomite\StackTrace\SourceCode;

use Awesomite\StackTrace\BaseTestCase;

class FileTest extends BaseTestCase
{
    public function testSerialize()
    {
        $file = new File(__FILE__);
        $from = 1;
        $to = __LINE__;
        $file->addThreshold(1, __LINE__);
        $deserialized = unserialize(serialize($file));

        $this->assertSame($file->getLines($from, $to), $deserialized->getLines($from, $to));
        $this->assertSame($file->countLines(), $deserialized->countLines());
        $this->assertSame($file->getFileName(), $deserialized->getFileName());
    }
}