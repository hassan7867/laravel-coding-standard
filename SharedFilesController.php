<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use services\SharedFilesRepository;

class SharedFilesController extends Controller
{
    public function getSharedByMeFiles($userId){
        try{
            $userFilesRepo = new SharedFilesRepository();
            $data = $userFilesRepo->getSharedByMeFiles($userId);
            return json_encode(['status' => true, 'data' => $data]);

        }catch (\Exception $exception){
            return json_encode(['status' => false, 'message' => 'Server Error']);
        }
    }

    public function getSharedWithMeFiles($userId){
        try{
            $userFilesRepo = new SharedFilesRepository();
            $data = $userFilesRepo->getSharedWithMeFiles($userId);
            return json_encode(['status' => true, 'data' => $data]);

        }catch (\Exception $exception){
            return json_encode(['status' => false, 'message' => 'Server Error']);
        }
    }

    public function getSharedFile($fileId){
        try{
            $userFilesRepo = new SharedFilesRepository();
            $data = $userFilesRepo->getSharedFile($fileId);
            return $data;

        }catch (\Exception $exception){
            return json_encode(['status' => false, 'message' => 'Server Error']);
        }
    }
}
