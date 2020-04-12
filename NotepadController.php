<?php

namespace App\Http\Controllers;

use App\NotepadTable;
use Illuminate\Http\Request;
use paid_api\notepad\Password;
use paid_api\notepad\Url;
use services\notepad\NotepadRepository;

class NotepadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function showNotepad(Request $request)
    {
        $notepadRepository = new NotepadRepository();
        return json_encode($notepadRepository->showNotepad($request));
    }

    public function notepadList(Request $request)
    {
        $notepadRepository = new NotepadRepository();
        return json_encode($notepadRepository->notepadList($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = new Url(new \NonEmptyString($request->url));
        $notepadRepository = new NotepadRepository();
        return json_encode($notepadRepository->store($url->value(), $request->text, $request->userId));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $link)
    {
        $url = new Url(new \NonEmptyString($request->url));
        $notepadRepository = new NotepadRepository();
        if ($request->requestType) {
            $password = new Password(new \NonEmptyString($request->password));
            return json_encode($notepadRepository->notepadPassword($url->value(), $password->value(), $request->userId));
        } else {
            $oldUrl = new Url(new \NonEmptyString($request->oldUrl));
            return json_encode($notepadRepository->notepadUrl($url->value(), $oldUrl->value(), $request->text, $request->userId));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkPasswordExist(Request $request)
    {
        $notepadRepository = new NotepadRepository();
        return json_encode($notepadRepository->checkPasswordExist($request));
    }

    public function returnText($url)
    {
        $note = NotepadTable::where('url', $url)->first();
        return json_encode(['text' => $note->text]);
    }

    public function removeNotepadPassword(Request $request)
    {
        $notepadRepository = new NotepadRepository();
        return json_encode($notepadRepository->removeNotepadPassword($request));
    }

    public function deleteNotepad(Request $request)
    {
        $notepadRepository = new NotepadRepository();
        return json_encode($notepadRepository->deleteNotepad($request));
    }
}