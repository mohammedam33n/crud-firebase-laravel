<?php

namespace App\Http\Controllers;

use ErrorException;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Kreait\Firebase\Auth\UserQuery;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Validation\ValidationException;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Exception\FirebaseException;

class AuthController extends Controller
{

    use ApiResponseTrait;

    protected $firebaseService;
    public function __construct(FirebaseService $firebaseService)
    {

        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        /*
        $users =  $this->firebaseService->authFirebase()->listUsers();
        $users = collect($users);
        */

        /*
        # Building a user query object
        $userQuery = UserQuery::all()
            ->sortedBy(UserQuery::FIELD_USER_EMAIL)
            ->inDescendingOrder()
            // ->inAscendingOrder() # this is the default
            ->withOffset(1)
            ->withLimit(499); # The maximum supported limit is 500

        # Using an array
        $userQuery = [
            'sortBy' => UserQuery::FIELD_USER_EMAIL,
            'order' => UserQuery::ORDER_DESC,
            'order' => UserQuery::ORDER_DESC # this is the default
            'offset' => 1,
            'limit' => 499, # The maximum supported limit is 500
        ];
        */





        $users =  $this->firebaseService->authFirebase()->listUsers();
        $users = collect($users);
        $data = [
            'users' => $users,
        ];

        return $this->apiResponse(200, null, null, $data);
    }

    public function register(Request $request)
    {
        // dd('register');
        $user =  $this->firebaseService->authFirebase()->createUser([
            'displayName'   => 'wesamm omar',
            'email'         => 'wesamm@gmail.com',
            'emailVerified' => false,
            'phoneNumber'   => '+15555550118',
            'password'      => '123456',
            'photoUrl'      => 'http://www.example.com/12345678/photo.png',
            'disabled'      =>  false,
        ]);

        $data = [
            'user' => $user,
        ];

        return $this->apiResponse(200, 'User Created Successfully', null, $data);
    }

    public function login(Request $request)
    {
        $user =  $this->firebaseService->authFirebase()->signInWithEmailAndPassword('mohamm3dameen@gmail.com', '123456');

        $data = [
            'user' => $user->data(),
        ];

        return $this->apiResponse(200, 'User Login Successfully', null, $data);
    }


    public function update(Request $request)
    {
        $uid = '1CeBTs4jL8Xxw20N1e3bQSDWPAW2';

        $user = $this->firebaseService->authFirebase()->updateUser($uid, [
            "email"       => "user@gmail.com",
            "displayName" => "mohammed amin",
            "photoUrl"    => "http://www.example.com/12345678/photo.png",
            // "phoneNumber" => "+0154449439",
            'password'    => '123456',
        ]);

        $data = [
            'user' => $user,
        ];

        return $this->apiResponse(200, 'User Updated Successfully', null, $data);
    }


    public function logout()
    {
        // Auth::guard('instructor')->logout();

        return $this->apiResponse(200, 'User successfully signed out');
    }


    public function userProfile()
    {
        $user =  $this->firebaseService->authFirebase()->disableUser('1CeBTs4jL8Xxw20N1e3bQSDWPAW2');

        return $this->apiResponse(200, 'success', null, $user);
    }

    public function show(Request $request)
    {

        /*
            $this->firebaseService->authFirebase()->getUser('D2QBNaISUgeoioMKoTMP8FnH5Gu2');
            $this->firebaseService->authFirebase()->getUserByEmail('ahmed@gmail.com');
            $this->firebaseService->authFirebase()->getUserByPhoneNumber('+15555550112');
        */



        /*
        // show user by ID
        try {

            $user = $this->firebaseService->authFirebase()->getUser('D2QBNaISUgeoioMKoTMP8FnH5Gu2');
        } catch (FirebaseException $e) {
            throw ValidationException::withMessages(['massage' =>'ID User Invalid']);
        }
        */


        // /*
        // show user by email
        try {

            $user = $this->firebaseService->authFirebase()->getUserByEmail('mohamm3dameen@gmail.com');
        } catch (FirebaseException $e) {
            throw ValidationException::withMessages(['massage' => 'email Invalid']);
        }
        // */

        /*
        // show user by Phone Number
        try {
            $user = $this->firebaseService->authFirebase()->getUserByPhoneNumber('+15s555550112');
        } catch (FirebaseException $e) {
            return $this->apiResponse(401, 'error', ['massage' => 'Phone Number Invalid']);
        }
        */

        return $this->apiResponse(200, 'success', null, $user);
    }


    public function refresh()
    {
        // $user = Auth::guard('instructor')->user();

        // $data = [
        //     'user' => new UserResource($user),
        //     'authorisation' => [
        //         'token' => Auth::refresh(),
        //         'type'  => 'bearer',
        //     ]
        // ];

        return $this->apiResponse(200, 'success', null);
    }
    //
    // protected function login(Request $request)
    // {
    //     try {
    //         $signInResult = $this->auth->signInWithEmailAndPassword($request['email'], $request['password']);
    //         $user = new User($signInResult->data());
    //         $result = Auth::login($user);
    //         return redirect($this->redirectPath());
    //     } catch (FirebaseException $e) {
    //         throw ValidationException::withMessages([$this->username() => [trans('auth.failed')],]);
    //     }
    // }


    public function destroy()
    {
        $uid = 'UnoUQeQF4iNnVsdwG87YZtPFxTy1';

        /*
        //delete user
        try {
            $this->firebaseService->authFirebase()->deleteUser($uid);
        } catch (UserNotFound $e) {
            return $e->getMessage();
        }
        return $this->apiResponse(200, 'Deleting User', null);
        */

        try {
            $this->firebaseService->authFirebase()->deleteUser($uid);
        } catch (UserNotFound $e) {
            return $this->apiResponse(200, 'error', $e->getMessage());
        }
        return $this->apiResponse(200, 'Deleting User', null);
    }



    public function destroyMultiple()
    {
        $uids = ['uid-1', 'uid-2', '1CeBTs4jL8Xxw20N1e3bQSDWPAW2'];
        $forceDeleteEnabledUsers = true; // default: false //By default, only disabled users will be deleted. If you want to also delete enabled users, use true as the second argument.




        try {
            $this->firebaseService->authFirebase()->deleteUsers($uids, $forceDeleteEnabledUsers);
        } catch (UserNotFound $e) {
            return $this->apiResponse(200, 'error', $e->getMessage());
        }
        return $this->apiResponse(200, 'delete Users', null);
    }
}


//
