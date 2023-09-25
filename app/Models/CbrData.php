<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @param int id
 * @param string xml_string
 * @param string data_date
 * @param Carbon created_at
 * @param Carbon updated_at
 */
class CbrData extends Model
{
    use HasFactory;

    protected $table = 'cbr_data';
    protected $fillable = ['xml_string', 'data_date'];

    public function createNewEntry(string $xmlString, string $dataDate): void
    {
        self::insert([
            'xml_string' => $xmlString,
            'data_date' => $dataDate
        ]);
    }

    public function countEntriesByDate(string $date): int
    {
        return self::where('data_date', $date)->get()->count();
    }

    public function getXmlStringByDate(string $date): string
    {
        return self::where('data_date', $date)->value('xml_string');
    }
}
