<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Http\Traits\ApiResponseTrait;
use Kreait\Firebase\Factory;

class UserController extends Controller
{
    use ApiResponseTrait;

    /*
    ## Cloud Fire store - Google ##
    Cloud Fire store is a NoSQL document database that lets you easily store, sync, and query data for your mobile and web apps - at global scale.

    */

    public $firebaseService;
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        $users = $this->firebaseService->firebaseDatabase()->collection('users');
        // $users = $users->getValue();
        // $categories = $categories->orderByChild('status')->equalTo('true')->getValue();
        // $categories = $categories->orderByChild('status')->equalTo('true')->limitToFirst(2)->getValue();
        // $categories = $categories->orderByChild('status')->equalTo('true')->limitToFirst(2)->getValue();
        // $users = $users->orderByChild('price')->endAt(10)->limitToFirst(2)->getValue();
        /*
        "message": "Index not defined, add \".indexOn\": \"name\", for path \"/users\", to the rules",

        you must add this in role
        like this
        "users":{
            ".indexOn" :["status"]
        }
        */

        $data = [
            'users' => $users
        ];
        return $this->apiResponse(200, 'success', null, $data);
    }

    public function store(Request $request)
    {



        $user = $this->firebaseService->firebaseDatabase()->collection('users')->newDocument();
        $user->set([
            'name' => 'HMADA'
        ]);

        return $this->apiResponse(200, 'User created Successfully', null);
    }

    public function show($id)
    {
        $category = $this->firebaseService->realtimeDatabase()->getReference('categories');
        $category = $category->orderByChild('id')->equalTo(2)->getValue();

        $data = [
            'category' => $category
        ];
        return $this->apiResponse(200, 'success', null, $data);
    }

    public function update(Request $request, $id)
    {

        $category = $this->firebaseService->realtimeDatabase()->getReference('categories');
        $category = $category->orderByChild('id')->equalTo(2)->getValue();

        $key = array_key_first($category);

        // /*
        $category = $this->firebaseService->realtimeDatabase()->getReference('categories/' . $key);

        $category = $category->update([
            'status' => "true"
        ]);
        // */

        /*
        ## update one filled ##
        $category = $this->firebaseService->realtimeDatabase()->getReference('categories/' . $key.'/status');

        $category = $category->set('false');
        */

        return $this->apiResponse(200, 'Category updated Successfully');
    }

    public function destroy($id)
    {

        $category = $this->firebaseService->realtimeDatabase()->getReference('categories');


        // /*
        // delete category by id
        $category = $category->orderByChild('id')->equalTo(2)->getValue();

        $key = array_key_first($category);

        $category = $this->firebaseService->realtimeDatabase()->getReference('categories/' . $key);

        $category = $category->remove();
        // */

        /*
        // delete all categories
        $category->set(null);
        */

        return $this->apiResponse(200, 'Category deleted Successfully', null, null);
    }
}
