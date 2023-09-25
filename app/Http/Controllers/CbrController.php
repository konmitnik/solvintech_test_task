<?php

namespace App\Http\Controllers;

use App\Models\CbrData;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CbrController extends Controller
{
    public function getCbrCurs(string $date_req, Request $request, Users $user, CbrData $cbrData)
    {
        $validator = Validator::make(
            ['date' => $date_req],
            ['date' => 'required|date_format:d.m.Y']
        );
        if (empty($request->bearerToken()) || !$user->isUserExistByToken($request->bearerToken())) {
            return response()->json(['message' => 'Unauthorized', 'status' => 401], 401);
        } elseif ($validator->fails()) {
            return response()->json([
                    'message' => 'Incorrect date format. Required DD.MM.YYYY',
                    'status' => 400
                ], 400);
        }

        $currentDate = date('d.m.Y');
        if ($cbrData->countEntriesByDate($date_req) > 0) {
            $dataFromCbr = simplexml_load_string($cbrData->getXmlStringByDate($date_req));
        } else {
            $textFromCbr = Http::get('https://www.cbr.ru/scripts/XML_daily.asp', ['date_req' => $date_req])->body();
            $textFromCbr = str_replace('windows-1251', 'utf-8', $textFromCbr);
            $textFromCbr = mb_convert_encoding($textFromCbr, 'utf-8', 'windows-1251');
            $dataFromCbr = simplexml_load_string($textFromCbr);
            if ($dataFromCbr->count() == 0)  {
                return response()->json(['message' => 'Not Found', 'status' => 404], 404);
            }
            if ($currentDate > $date_req) {
                $cbrData->createNewEntry($dataFromCbr->asXML(), $date_req);
            }
        }

        return response()->json([
            'message' => 'OK',
            'status' => 200,
            'data' => json_encode($dataFromCbr)
        ], 200);
    }
}
