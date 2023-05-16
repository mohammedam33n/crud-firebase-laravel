<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{

    public $firebase;
    public function __construct()
    {
        $this->firebase = (new Factory)->withServiceAccount(base_path('firebase.json'));
    }

    public function authFirebase()
    {
        return $this->firebase->createAuth();
    }

    public function realtimeDatabase()
    {
        return $this->firebase->withDatabaseUri(config('firebase.projects.app.database.url'))->createDatabase();
    }

    public function firebaseDatabase()
    {
        return $this->firebase->createFirestore()->database();
    }


}
