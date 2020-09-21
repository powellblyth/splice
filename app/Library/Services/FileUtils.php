<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Library\Services;

use App\Exceptions\FileNotExistsException;
use App\Exceptions\FileNotOpenableException;

class FileUtils {

    /**
     * @param string $fileName
     * @return int
     * @throws FileNotOpenableException
     */
    public static function getStartTimestamp(string $fileName): int {
        try {
            $timeStamp = (int)self::getFileContents($fileName);
        } catch (FileNotExistsException $ex) {
            $timeStamp = strToTime('2018-01-01T19:20+01:00');
        }
        return $timeStamp;
    }

    /**
     * @param string $fileName
     * @return string
     * @throws FileNotExistsException
     * @throws FileNotOpenableException
     */
    public static function getFileContents(string $fileName): string {
        $fileHandle = self::openFileForReading($fileName);
        // retrieve the timestamp from the file
        $contents = fgets($fileHandle);
        self::closeFile($fileHandle);

        return $contents;
    }

    /**
     * @param string $fileName
     * @param $timestamp
     * @return bool
     * @throws FileNotOpenableException
     */
    public static function writeFile(string $fileName, $timestamp) {
        $fileHandle = self::openFileForWriting($fileName);
        fwrite($fileHandle, $timestamp);
        self::closeFile($fileHandle);
        return true;
    }

    /**
     * @param string $fileName
     * @return bool|resource
     * @throws FileNotOpenableException
     */
    public static function openFileForWriting(string $fileName, $binaryMode = false) {
        $mode = 'w+';
        if ($binaryMode) {
            $mode = 'wtb';
        }
        $fileHandle = fopen($fileName, $mode);
        if ($fileHandle) {
            return $fileHandle;
        } else {
            throw new FileNotOpenableException('Could not open ' . $fileName . ' for writing', ['level' => 'fatal', 'message', 'Could not open ' . $fileName . ' for writing, serious problem']);
        }
    }

    /**
     * @param string $fileName
     * @return bool|resource
     * @throws FileNotExistsException
     * @throws FileNotOpenableException
     */
    public static function openFileForReading(string $fileName) {
        if (is_file($fileName)) {
            $fileHandle = fopen($fileName, 'r');
            if ($fileHandle) {
                return $fileHandle;
            } else {
                throw new FileNotOpenableException('Could not open ' . $fileName . ' for reading', ['level' => 'fatal', 'message', 'Could not open ' . $fileName . ' for writing, serious problem']);
            }
        } else {
            throw new FileNotExistsException('file ' . $fileName . ' does not exist');
        }
    }

    /**
     * @param $fileHandle
     */
    public static function closeFile($fileHandle) {
        if (is_resource($fileHandle)) {
            fclose($fileHandle);
        }
    }
}
