<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @param int id
 * @param string login
 * @param string password
 * @param string token
 * @param Carbon created_at
 * @param Carbon updated_at
 */
class Users extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $fillable = ['login', 'password', 'token'];

    public function createNewUser(string $login, string $password): void
    {
        self::create([
            'login' => $login,
            'password' => md5($password),
            'token' => bin2hex(openssl_random_pseudo_bytes(rand(16, 32)))
        ]);
    }

    public function getUserToken(string $login, string $password): ?string
    {
        return self::where('login', $login)->where('password', md5($password))->value('token');
    }

    public function isUserExistByToken(string $token): bool
    {
        return self::where('token', $token)->count() > 0;
    }

    public function generateNewToken(string $currentToken): string
    {
        $newToken = bin2hex(openssl_random_pseudo_bytes(rand(16, 32)));
        self::where('token', $currentToken)->update(['token' => $newToken]);
        
        return $newToken;
    }
}
