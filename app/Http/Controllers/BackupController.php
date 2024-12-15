<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use Illuminate\Support\Facades\File;
class BackupController extends Controller
{
    //
    public function backup()
    {

                
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $backupPath = storage_path('app/backups');
        $fileName = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $zipFileName = 'backup-' . date('Y-m-d_H-i-s') . '.zip';
        $sqlFilePath = $backupPath . '/' . $fileName;
        $zipFilePath = $backupPath . '/' . $zipFileName;

        // Create the backup directory if it doesn't exist
        if (!File::isDirectory($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        // Run mysqldump command to export the database
        $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} > {$sqlFilePath}";
        exec($command, $output, $result);

        if ($result === 0) {
            // Create a zip archive
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                // Add the SQL file to the zip
                $zip->addFile($sqlFilePath, $fileName);
                $zip->close();

                // Optionally, remove the original SQL file
                File::delete($sqlFilePath);

                echo "Backup successful and saved as zip: $zipFilePath";
            } else {
                echo "Failed to create zip file.";
            }
        } else {
            echo "Database backup failed.";
        }
    }
}
