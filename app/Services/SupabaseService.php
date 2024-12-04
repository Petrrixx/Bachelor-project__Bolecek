<?php
namespace App\Services;

use PHPSupabase\Service;
use PHPSupabase\Auth;
use PHPSupabase\Database;

class SupabaseService
{
    protected $service;
    protected $auth;
    protected $database;

    public function __construct()
    {
        $this->service = new Service(
            env('SUPABASE_KEY'),
            env('SUPABASE_URL')
        );

        $this->auth = $this->service->createAuth();
        $this->database = $this->service->createDatabase();
    }

    // Vytvorenie používateľa
    public function createUser($email, $password, $user_metadata = [])
    {
        try {
            $this->auth->createUserWithEmailAndPassword($email, $password, $user_metadata);
            return 'User has been created!';
        } catch (\Exception $e) {
            return $this->auth->getError();
        }
    }

    // Prihlásenie používateľa
    public function signInUser($email, $password)
    {
        try {
            $this->auth->signInWithEmailAndPassword($email, $password);
            $data = $this->auth->data();
            return $data;
        } catch (\Exception $e) {
            return $this->auth->getError();
        }
    }

    // Aktualizácia údajov o používateľovi
    public function updateUserData($access_token, $updateData)
    {
        try {
            $this->auth->setBearerToken($access_token);
            // Predpokladáme, že používateľ je uložený v databáze a má unikátny ID
            $userId = $this->auth->getUser($access_token)['id'];
            $userTable = $this->database->from('users');
            $userTable->update($updateData)->where('id', '=', $userId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Vymazanie používateľa
    public function deleteUser($access_token)
    {
        try {
            $this->auth->setBearerToken($access_token);
            $userId = $this->auth->getUser($access_token)['id'];
            // Vymažeme používateľa z databázy
            $this->database->from('users')->delete()->where('id', '=', $userId);
            // Tiež vymažeme používateľa z Auth služby
            $this->auth->deleteUser();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
