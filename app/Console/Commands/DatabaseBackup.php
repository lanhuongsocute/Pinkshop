<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use ZipArchive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:database-backup';
    protected $signature = 'backup:db';
    protected $description = 'Backup the database to a SQL file and compress it into a zip file';


    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info('cron backup');
        // return 0;
        //
        $database = 'u963648275_banme';
        $username = 'u963648275_banme';
        $password = '7^nKLGF:Th*';
        $host = 'localhost';
        $backupPath = storage_path('app/backups');
        $fileName = 'backup-banme' . date('Y-m-d_H-i-s') . '.sql';
        $zipFileName = 'backup-banme' . date('Y-m-d_H-i-s') . '.zip';
        $sqlFilePath = $backupPath . '/' . $fileName;
        $zipFilePath = $backupPath . '/' . $zipFileName;

        // Create the backup folder if it doesn't exist
        if (!File::isDirectory($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        // Run the mysqldump command to export the database
        $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} > {$sqlFilePath}";
        $result = null;
        exec($command, $output, $result);

        if ($result === 0) {
            // Create a zip archive
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                // Add the SQL file to the zip
                $zip->addFile($sqlFilePath, $fileName);
                $zip->close();

                // Optionally, remove the SQL file after zipping it
                File::delete($sqlFilePath);

                $this->info('Database backup completed and saved as zip: ' . $zipFilePath);
            } else {
                $this->error('Failed to create zip file.');
                 \Log::info('Failed to create zip file.');
            }
        } else {
            \Log::info('Database backup failed.');
        }
    
        $database = 'u963648275_tanphat';
        $username = 'u963648275_tanphat';
        $password = 'iL5N6AibjCVyg4-';
        $host = 'localhost';
        $backupPath = storage_path('app/backups');
        $fileName = 'backup-tanphat' . date('Y-m-d_H-i-s') . '.sql';
        $zipFileName = 'backup-tanphat' . date('Y-m-d_H-i-s') . '.zip';
        $sqlFilePath = $backupPath . '/' . $fileName;
        $zipFilePath = $backupPath . '/' . $zipFileName;

        // Create the backup folder if it doesn't exist
        if (!File::isDirectory($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        // Run the mysqldump command to export the database
        $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} > {$sqlFilePath}";
        $result = null;
        exec($command, $output, $result);

        if ($result === 0) {
            // Create a zip archive
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                // Add the SQL file to the zip
                $zip->addFile($sqlFilePath, $fileName);
                $zip->close();

                // Optionally, remove the SQL file after zipping it
                File::delete($sqlFilePath);

                $this->info('Database backup completed and saved as zip: ' . $zipFilePath);
            } else {
                $this->error('Failed to create zip file.');
                \Log::info('Failed to create zip file.');
            }
        } else {
            \Log::info('Database backup failed.');
        }
    }
}
