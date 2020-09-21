<?php

namespace App\Library\Services;

use App\Exceptions\FTPException;

class FtpUtils
{
    private $ftpHandle;

    public function __construct(string $ftpHost, string $ftpUser, string $ftpPass)
    {
        $this->ftpHandle = ftp_connect($ftpHost);
        if ($this->isConnected()) {
            if (!ftp_login($this->ftpHandle, $ftpUser, $ftpPass)) {
                throw new FTPException('Could not Log in to FTP server');
            } else {
                ftp_set_option($this->ftpHandle, FTP_USEPASVADDRESS, false);
                ftp_pasv($this->ftpHandle, true);
            }
        } else {
            throw new FTPException('Could not connect to FTP server');
        }
    }

    public function isConnected(): bool
    {
        return is_resource($this->ftpHandle);
    }

    public function putToFTP(string $localFile, string $remotePath, string $remoteFileName): bool
    {
        $remotePath     = $this->cleanPath($remotePath);
        $remoteFileName = $this->cleanFile($remoteFileName);
        if ($this->isConnected()) {
            if (!$this->remoteFolderExists($remotePath)) {
                $this->makeRemoteDir($remotePath);
            }
            ftp_put($this->ftpHandle, $remotePath . $remoteFileName, $localFile, FTP_BINARY);
        } else {
            throw new FTPException('Not connected to FTP');
        }
        return true;
    }

    public function cleanPath(string $path): string
    {
        return rtrim($path, '/') . '/';
    }

    public function cleanFile(string $file): string
    {
        return ltrim($file, '/');
    }

    function listFilesByFtp(string $path): array
    {
        $results = [];
        if ($this->isConnected()) {
            $pathCleaned = $this->cleanPath($path);
            $files       = ftp_nlist($this->ftpHandle, $pathCleaned);

            foreach ($files as $file) {
                $justThefile = str_replace($pathCleaned, '', $file);
                if (!in_array($file, ['.', '..'])) {
                    $results[] = $justThefile;
                }
            }
        } else {
            throw new FTPException('Not connected to FTP');
        }
        return $results;
    }

    /**
     * @param string $remotePath
     * @return bool
     * @throws FTPException
     */
    public function remoteFolderExists(string $remotePath): bool
    {
        if ($this->isConnected()) {
            $oldDir = ftp_pwd($this->ftpHandle);
            if (@ftp_chdir($this->ftpHandle, $remotePath)) {
                ftp_chdir($this->ftpHandle, $oldDir);
                $result = true;
            } else {
                $result = false;
            }

            return $result;
        } else {
            throw new FTPException('Not connected to FTP');
        }
    }

    /**
     * @param string $remotePath
     * @return bool
     * @throws FTPException
     */
    public function makeRemoteDir(string $remotePath): bool
    {
        if ($this->isConnected()) {
            return false === ftp_mkdir($this->ftpHandle, $remotePath);
        } else {
            throw new FTPException('Not connected to FTP');
        }
    }

    /**
     * @param string $remotePath
     * @param string $remoteFileName
     * @param string $localPath
     * @param string $localFileName
     * @param bool $deleteAfterGetting
     * @throws FTPException
     * @throws \App\Exceptions\FileNotOpenableException
     */
    public function getFile(string $remotePath, string $remoteFileName, string $localPath, string $localFileName, bool $deleteAfterGetting = false)
    {
        if ($this->isConnected()) {
            try {
                $fullLocalFilePath  = $this->cleanPath($localPath) . $localFileName;
                $fullRemoteFilePath = $this->cleanPath($remotePath) . $remoteFileName;
                $fileHandle         = FileUtils::openFileForWriting($fullLocalFilePath);
            } catch (\Exceptions\FileNotOpenableException $ex) {
                throw new \Exception('Could not open destination file: ' . $ex->getMessage());
            }
            ftp_fget($this->ftpHandle, $fileHandle, $fullRemoteFilePath, FTP_ASCII);
            FileUtils::closeFile($fileHandle);
            if (is_file($fullLocalFilePath)) {
                if ($deleteAfterGetting) {
                    ftp_delete($this->ftpHandle, $fullRemoteFilePath);
                }
            }
        } else {
            throw new FTPException('Not connected to FTP');
        }
    }

    //
    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->closeConnection();
        }
    }

    public function closeConnection()
    {
        return ftp_close($this->ftpHandle);
    }
}
