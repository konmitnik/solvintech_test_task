<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @param int id
 * @param string data_date
 * @param string valute_num_code
 * @param string valute_char_code
 * @param string nominal
 * @param string valute_name
 * @param string value
 * @param string vunit_rate
 * @param Carbon created_at
 * @param Carbon updated_at
 */
class CbrData extends Model
{
    use HasFactory;

    protected $table = 'cbr_data';
    protected $fillable = [
        'data_date',
        'valute_num_code',
        'valute_char_code',
        'nominal',
        'valute_name',
        'value',
        'vunit_rate'
    ];

    /**
     * @param array $values Массив должен содержать в себе следующие ключи и занчения для них:
     * data_date, valute_num_code, valute_char_code, nominal, valute_name, value, vunit_rate
     * @return void
     */
    public function createNewEntry(array $values, string $date): void
    {
        foreach ($values as $valute) {
            self::insert([
                'data_date' => $date,
                'valute_num_code' => $valute['NumCode'],
                'valute_char_code' => $valute['CharCode'],
                'nominal' => $valute['Nominal'],
                'valute_name' => $valute['Name'],
                'value' => $valute['Value'],
                'vunit_rate' => $valute['VunitRate']
            ]);
        }
        
    }

    public function countEntriesByDate(string $date): int
    {
        return self::where('data_date', $date)->get()->count();
    }

    public function getValutesByDate(string $date): array
    {
        $data = self::where('data_date', $date)->get();
        $result = ['Date' => $date, 'Valute' => []];
        foreach ($data as $valute) {
            array_push($result['Valute'], [
                'NumCode' => $valute->valute_num_code,
                'CharCode' => $valute->valute_char_code,
                'Nominal' => $valute->nominal,
                'Name' => $valute->valute_name,
                'Value' => $valute->value,
                'VunitRate' => $valute->vunit_rate
            ]);
        }
        return $result;
    }
}
