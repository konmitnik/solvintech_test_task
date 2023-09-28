<?php

namespace App\Http\Controllers;

use App\Models\CbrData;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use SimpleXMLElement;

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
            $dataFromCbr = $cbrData->getValutesByDate($date_req);
        } else {
            $dataFromCbr = $this->getXmlData($date_req);
            if ($dataFromCbr->count() == 0)  {
                return response()->json(['message' => 'Not Found', 'status' => 404], 404);
            }
            $dataFromCbr = ['Date' => $date_req, 'Valute' => $this->xmlToArray($dataFromCbr)['Valute']];
            if ($currentDate > $date_req) {
                $cbrData->createNewEntry($dataFromCbr['Valute'], $date_req);
            }
        }

        return response()->json([
            'message' => 'OK',
            'status' => 200,
            'data' => $dataFromCbr
        ], 200);
    }

    public function xmlToArray ($xmlObject): array
    {
        $result = [];
        foreach ((array)$xmlObject as $index => $node )
            if ($index != '@attributes') {
                $result[$index] = (is_object($node) || is_array($node)) ? $this->xmlToArray ($node) : $node;
            }
        return $result;
    }

    public function getXmlData(string $date): SimpleXMLElement
    {
        $textFromCbr = Http::get('https://www.cbr.ru/scripts/XML_daily.asp', ['date_req' => $date])->body();
        $textFromCbr = str_replace('windows-1251', 'utf-8', $textFromCbr);
        $textFromCbr = mb_convert_encoding($textFromCbr, 'utf-8', 'windows-1251');
        return simplexml_load_string($textFromCbr);
    }
}
