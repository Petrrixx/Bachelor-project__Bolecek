<?php

namespace App\Services;

use PHPSupabase\Service;
use Exception;

class SupabaseService
{
    protected $service;
    protected $database;

    public function __construct()
    {
        // Inicializácia služby pre pripojenie k Supabase
        $this->service = new Service(
            env('SUPABASE_API_KEY'),
            env('SUPABASE_URL')
        );

        // Inicializácia databázy pre pripojenie k tabuľke 'reservations'
        $this->database = $this->service->initializeDatabase('reservations', 'id');
    }

    // Funkcia na vloženie novej rezervácie
    public function createReservation($reservationData)
    {
        try {
            $data = $this->database->insert([$reservationData]); // Vloženie dát do tabuľky 'reservations'
            return $data;
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    // Funkcia na načítanie všetkých rezervácií
    public function getAllReservations()
    {
        try {
            $data = $this->database->fetchAll(); // Načítanie všetkých rezervácií
            return $data;
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
