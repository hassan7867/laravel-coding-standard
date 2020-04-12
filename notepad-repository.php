<?php

namespace services\notepad;

use App\NotepadTable;
use Illuminate\Http\Request;

class NotepadRepository
{
    public function showNotepad(Request $request)
    {
        if (NotepadTable::where('url', $request->url)->exists()) {
            if (NotepadTable::where('url', $request->url)->first()['user_id'] == $request->userId) {
                $note = NotepadTable::where('url', $request->url)->first();
                $passwordStatus = false;
                $text = $note->text;
                if (!empty($note->password)) {
                    $passwordStatus = true;
                    $text = '';
                }
                return ['notAccess' => false, 'text' => $text, 'passwordStatus' => $passwordStatus, 'url' => $note->url];
            } else {
                return ['notAccess' => true];
            }
        } else {
            return false;
        }
    }

    public function notepadList(Request $request)
    {
        $notepadDetails = [];
        $notepadList = NotepadTable::where('user_id', $request->userId)->get();
        for ($i = 0; $i < count($notepadList); $i++) {
            $notepadDetails[$i]['id'] = $notepadList[$i]->id;
            $notepadDetails[$i]['url'] = $notepadList[$i]->url;
            $notepadDetails[$i]['last_updated'] = $notepadList[$i]->updated_at;
            $notepadDetails[$i]['password'] = (empty($notepadList[$i]->password)) ? false : true;
        }
        return $notepadDetails;
    }

    public function store(string $url, string $text, int $userId)
    {
        if (!NotepadTable::where('url', $url)->exists()) {
            $note = new NotepadTable();
            $note->text = $text;
            $note->url = $url;
            $note->user_id = $userId;
            return json_encode($note->save());
        } else {
            $note = NotepadTable::where(['url' => $url])->first();
            $note->text = $text;
            return json_encode($note->update());
        }
    }

    public function checkPasswordExist(Request $request)
    {
        if (NotepadTable::where(['url' => $request->url, 'password' => $request->password])->exists()) {
            $note = NotepadTable::where('url', $request->url)->first();
            return ['status' => true, 'text' => $note->text];
        } else {
            return ['status' => false];
        }
    }

    public function removeNotepadPassword(Request $request)
    {
        return (NotepadTable::where('url', $request->url)->exists()) ? NotepadTable::where('url', $request->url)->update(['password' => '']) : false;
    }

    public function notepadPassword(string $url, string $password, int $userId)
    {
        if (NotepadTable::where('url', $url)->exists()) {
            $note = NotepadTable::where('url', $url)->first();
            $note->password = $password;
            return $note->update();
        } else {
            $note = new NotepadTable();
            $note->password = $password;
            $note->url = $url;
            $note->user_id = $userId;
            return $note->save();
        }
    }

    public function notepadUrl(string $newUrl, string $oldUrl, $text, int $userId)
    {
        if (NotepadTable::where('url', $newUrl)->exists()) {
            return ['urlExists' => true];
        } else {
            if (NotepadTable::where('url', $oldUrl)->exists()) {
                $note = NotepadTable::where('url', $oldUrl)->first();
                $note->url = $newUrl;
                $note->text = $text;
                return $note->update();
            } else {
                $note = new NotepadTable();
                $note->text = $text;
                $note->url = $newUrl;
                $note->user_id = $userId;
                return $note->save();
            }
        }
    }

    public function deleteNotepad(Request $request)
    {
        $deletedRecords = [];
        for ($i = 0; $i < count($request->deleteIds); $i++) {
            array_push($deletedRecords, NotepadTable::where('id', $request->deleteIds[$i])->delete());
        }
        return ['status' => true, 'deletedRecords' => $deletedRecords];
    }
}
