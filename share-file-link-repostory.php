<?php
declare(strict_types=1);

namespace services\auth;

use App\Jobs\ShareFilesLinkEmail;
use App\SharedFile;
use App\SharedFilesLinkVisitedRecord;
use App\ShareFile;
use App\ViewFileLogsTable;
use Services\JwtAuthentication\JwtDecode;
use Services\JwtAuthentication\JwtEncode;
use services\WorkspaceDocsPath;

class ShareLinkRepository
{

    public function shareLink($request)
    {
        $usersList = json_decode($request->usersList);
        $files = json_decode($request->sharedFiles);
        for ($i = 0; $i < count($usersList); $i++) {
            $shareFilesTable = new ShareFile();
            $shareFilesTable->email = $usersList[$i];
            $shareFilesTable->expiry_date = $request->expiryDate;
            $shareFilesTable->download_once = $request->downloadOnce;
            $shareFilesTable->id_user = $request->senderUser;
            $shareFilesTable->save();
            $jwtEncode = new JwtEncode(['sharedFileId' => $shareFilesTable->id, 'expiry_date' => $request->expiryDate, 'downloadOnce' => $request->downloadOnce]);
            $token = $jwtEncode->jwtEncode();
            for ($j = 0; $j < count($files); $j++) {
                $sharedFilesTable = new SharedFile();
                $sharedFilesTable->id_shared_files = $shareFilesTable->id;
                $sharedFilesTable->files_path = $files[$j];
                $sharedFilesTable->download_once = $request->downloadOnce;
                $result = $sharedFilesTable->save();
            }
            ShareFilesLinkEmail::dispatch(['token' => $token, 'email' => $usersList[$i]], $request->senderUser);
        }
        return ($result);

    }

    public function getSharedFiles($request){
        $jwtDecode = new JwtDecode($request->token);
        $decodedToken = $jwtDecode->jwtDecode();
        $sharedFiles  = ShareFile::where('id', $decodedToken->sharedFileId)->first();
        $expiryDate = new \DateTimeImmutable($sharedFiles['expiry_date']);
        $today = new \DateTimeImmutable();
        if($expiryDate < $today){
            return ['status' => false, 'message' => 'Sorry! Link Expired.'];
        }
        $workspaceDocsPath = new WorkspaceDocsPath($sharedFiles['id_user']);
        $filePath = $workspaceDocsPath->path() . '/user-' . $sharedFiles['id_user'] . '/files/';
        $sharedFiles = SharedFile::where('id_shared_files', $decodedToken->sharedFileId)->get();
        foreach ($sharedFiles as $sharedFile){
            $file = $filePath . $sharedFile->files_path;
            $sharedFile->size = round(filesize($file) / 1024, 2);
            $sharedFile->type =  mime_content_type($file);

        }
        $sharedFilesLinkVisitedRecord = new SharedFilesLinkVisitedRecord();
        $sharedFilesLinkVisitedRecord->id_shared = $decodedToken->sharedFileId;
        $sharedFilesLinkVisitedRecord->save();
        return ['status' => true, 'data' => $sharedFiles];
    }

    public function getSharedFile($fileId){
        $file = SharedFile::where('id', $fileId)->first();
        $sharedFiles = ShareFile::where('id', $file->id_shared_files)->first();
        $expiryDate = new \DateTimeImmutable($sharedFiles['expiry_date']);
        $today = new \DateTimeImmutable();
        if($expiryDate < $today){
            return ['status' => false, 'message' => 'Sorry! Link Expired.'];
        }
        $workspaceDocsPath = new WorkspaceDocsPath($sharedFiles['id_user']);
        $filePath = $workspaceDocsPath->path() . '/user-' . $sharedFiles['id_user'] . '/files/';
        $file = $filePath . $file->files_path;
        $type = mime_content_type($file);
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($file));
        return readfile($file);
    }

    public function downloadFile($fileId){
        $file = SharedFile::where('id', $fileId)->first();
        $sharedFiles = ShareFile::where('id', $file->id_shared_files)->first();
        $expiryDate = new \DateTimeImmutable($sharedFiles['expiry_date']);
        $today = new \DateTimeImmutable();
        if($expiryDate < $today){
            return ['status' => false, 'message' => 'Sorry! Link Expired.'];
        }
        $workspaceDocsPath = new WorkspaceDocsPath($sharedFiles['id_user']);
        $filePath = $workspaceDocsPath->path() . '/user-' . $sharedFiles['id_user'] . '/files/';
        $file = $filePath . $file->files_path;
        $type = mime_content_type($file);
        header('Content-Type:' . $type);
        header('Content-Length: ' . filesize($file));
        $viewFileLog = new ViewFileLogsTable();
        $viewFileLog->id_shared_file = $fileId;
        $viewFileLog->viewed = 1;
        $viewFileLog->save();
        return readfile($file);
    }

    public function downloadAllFiles($token)
    {
        $jwtDecode = new JwtDecode($token);
        $decodedToken = $jwtDecode->jwtDecode();
        $sharedFiles = ShareFile::where('id', $decodedToken->sharedFileId)->first();
        $expiryDate = new \DateTimeImmutable($sharedFiles['expiry_date']);
        $today = new \DateTimeImmutable();
        if ($expiryDate < $today) {
            return ['status' => false, 'message' => 'Sorry! Link Expired.'];
        }
        $workspaceDocsPath = new WorkspaceDocsPath($sharedFiles['id_user']);
        $filePath = $workspaceDocsPath->path() . '/user-' . $sharedFiles['id_user'] . '/files/';
        $sharedFiles = SharedFile::where('id_shared_files', $decodedToken->sharedFileId)->get();
        $zipname = time() . 'sharedFiles.zip';
        $zip = new \ZipArchive();
        $zip->open($zipname, \ZipArchive::CREATE);
        foreach ($sharedFiles as $sharedFile) {
            $file = $filePath . $sharedFile->files_path;
            $zip->addFile($file, $sharedFile->files_path);
            $viewFileLog = new ViewFileLogsTable();
            $viewFileLog->id_shared_file = $sharedFile->id;
            $viewFileLog->viewed = 1;
            $viewFileLog->save();
        }
        $zip->close();
        header('Content-Type: application/zip');
        header('Content-disposition: shared-files; filename=' . $zipname);
        header('Content-Length: ' . filesize($zipname));
        return readfile($zipname);
    }

}
