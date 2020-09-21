<?php

namespace App\Console\Commands;

use App\Exceptions\FTPException;
use App\Library\Services\FtpUtils;
use App\Notifications\importOrderConfirmsFromSupplierNotification;
use Illuminate\Console\Command;

class importWalkerOrderConfirms extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'walker:importOrderConfirms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'bring all the receipt files from walker';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $inputFtpFolder = 'tochateaurouge';
        $outputPath = storage_path('app/walker/received/pending/');

        try {
            $ftp = new FtpUtils(config('walker.ftp.host'), config('walker.ftp.user'), config('walker.ftp.password'));
            $files = $ftp->listFilesByFtp($inputFtpFolder);
            foreach ($files as $fileName) {
                if (0 === strpos($fileName, 'DESCON')
                    || 0 === strpos($fileName, 'RECCON')
                    || 0 === strpos($fileName, 'STOCK')) {
                    $ftp->getFile($inputFtpFolder, $fileName, $outputPath, $fileName, true);
                }
                echo $fileName . "\n";
            }

            $errors = [];

        } catch (FTPException $e) {
            $notifiableUsers = User::Where('notify_about_failed_orders', true)->get();
            $notification = new importOrderConfirmsFromSupplierNotification('Walker', ['Exception' => 'importWalkerOrderConfirms - ' . get_class($e) . ' - ' . $e->getMessage()]);
            foreach ($notifiableUsers as $user) {
                try {
                    $user->notify($notification);
                } catch (\Aws\Ses\Exception\SesException $e) {
                    ;
                }
            }
            $errors[] = ['level' => 'terminal', $e->getMessage()];
        }//putToFtp($ftpHost, $ftpUser, $ftpPass, $outputCsvFileName, $remoteFileName);
        var_dump($errors);
    }
}