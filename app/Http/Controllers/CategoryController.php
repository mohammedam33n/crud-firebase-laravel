<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Http\Traits\ApiResponseTrait;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    /*
    ## Realtime Database store ##
    The Realtime Database API currently does not support realtime event listeners.
    */

    public $firebaseService;
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        $categories = $this->firebaseService->realtimeDatabase()->getReference('categories');
        // $categories = $categories->getValue();
        // $categories = $categories->orderByChild('status')->equalTo('true')->getValue();
        // $categories = $categories->orderByChild('status')->equalTo('true')->limitToFirst(2)->getValue();
        // $categories = $categories->orderByChild('status')->equalTo('true')->limitToFirst(2)->getValue();
        $categories = $categories->orderByChild('price')->endAt(10)->limitToFirst(2)->getValue();
        /*
        "message": "Index not defined, add \".indexOn\": \"name\", for path \"/categories\", to the rules",

        you must add this in role
        like this
        "categories":{
            ".indexOn" :["status"]
        }
        */

        $data = [
            'categories' => $categories
        ];
        return $this->apiResponse(200, 'success', null, $data);
    }

    public function store(Request $request)
    {
        $category = $this->firebaseService->realtimeDatabase()->getReference('categories');
        $category->push([
            'name'   => 'mobile test',
            'status' => 'true',
        ]);

        return $this->apiResponse(200, 'Category created Successfully', null);
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
