<?php

namespace Awesomite\StackTrace\SourceCode;

/**
 * @internal
 */
class File implements FileInterface
{
    private $fileName;

    private $deserialized = false;

    private $fileObject = null;
    
    private $numberOfLines;
    
    private $lines = array();

    private $thresholds = array();
    
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * One-based array type
     * @param int $from
     * @param int $to
     * @return $this
     */
    public function addThreshold($from, $to)
    {
        $this->thresholds[] = array($from, $to);
        
        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            'fileName' => $this->fileName,
            'numberOfLines' => $this->countLines(),
            'lines' => $this->getAllLines(),
            'thresholds' => $this->thresholds,
        ));
    }

    public function unserialize($serialized)
    {
        $deserialized = unserialize($serialized);
        $this->fileName = $deserialized['fileName'];
        $this->numberOfLines = $deserialized['numberOfLines'];
        $this->lines = $deserialized['lines'];
        $this->thresholds = $deserialized['thresholds'];
        $this->deserialized = true;
    }
    
    public function countLines()
    {
        if (is_null($this->numberOfLines)) {
            $file = $this->getFileObject();
            $file->seek($file->getSize());
            $this->numberOfLines = $file->key() + 1;
        }
        
        return $this->numberOfLines;
    }

    public function getLines($from, $to)
    {
        if (!$this->deserialized) {
            $file = $this->getFileObject();
            $max = min($to, $this->countLines());
            $result = array();
            for ($i = max(1, $from); $i <= $max; $i++) {
                $file->seek($i-1);
                // substr($file->current(), 0, -1) can return false
                $result[$i] = substr_replace($file->current(), '', -1);
            }

            return $result;
        }

        $result = array();
        for ($i = $from; $i <= $to; $i++) {
            if (isset($this->lines[$i])) {
                $result[$i] = $this->lines[$i];
            }
        }

        return $result;
    }

    private function getAllLines()
    {
        if ($this->lines) {
            return $this->lines;
        }

        $result = array();
        foreach ($this->thresholds as $data) {
            list($from, $to) = $data;
            $result += $this->getLines($from, $to);
        }

        return $result;
    }

    /**
     * @return \SplFileObject
     */
    private function getFileObject()
    {
        if (is_null($this->fileObject)) {
            $this->fileObject = new \SplFileObject($this->fileName, 'r');
        }

        return $this->fileObject;
    }
}