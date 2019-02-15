<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for all user CRUDs.
    | All returning json responses will have "error" property for better error
    | checking on the client side
    |
    */

    /**
     * Retrieving all users.
     * the json will have "users" and "number_of_result" properties
     * 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $all_users = User::all();
        return response()->json([
            'users'             => $all_users,
            'number_of_result'  => count($all_users),
            'error'             => false
            ]);
    }
    
    
    /**
     * Creating a new user.
     * 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addUser(Request $request)
    {
        try {
            // Validating the inputs. Exception (ValidationException) occurs if validation fails
            $validatedData = $request->validate([
                'email'         => 'required|email|unique:users', // email should be unique and valid
                'first_name'    => 'required', // the request should have first_name
                'last_name'     => 'required' // the request should have last_name
            ]);
            
            $new_user               = new User;
            $new_user->first_name   = $request->first_name;
            $new_user->last_name    = $request->last_name;
            $new_user->email        = $request->email;
            $new_user->password     = bcrypt($request->password);
            $new_user->save();
            
            return response()->json([
                'message'   => 'User successfully created',
                'user_id'   => $new_user->id,
                'error'     => false
                ]);
                
        } catch(ValidationException $exception) {

            // if first_name or last_name not provided returns error
            if (empty($request->first_name) || empty($request->last_name)) {
                return response()->json([
                    'message'   => 'first_name and last_name cannot be null',
                    'error'     => true
                    ]);
            }

            // otherwise email is not valid or already in use
            return response()->json([
                'message'   => 'Email is not valid or already in use',
                'error'     => true
                ]);
            
        }
    }
    
    
    /**
     * Updating the user.
     * 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  $user_id
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request, $user_id)
    {
        
        try {
            // Validating the inputs. Exception (ValidationException) occurs if validation fails
            // email should be unique and valid but it is not required for this method
            $validatedData = $request->validate([
                'email' => 'email|unique:users' 
            ]);
            
            // At least one field should have value
            if (empty($request->first_name) && empty($request->last_name) && 
                empty($request->password) && empty($request->email) ) {
                return response()->json([
                    'message'   => 'Body must contains at least one field',
                    'error'     => true
                ]);
            }
            
            // By using findOrFail the exception (ModelNotFoundException) will occur if the user not found
            $user   = User::findOrFail($user_id);
        
            // update first_name if the first_name has value
            if ( !empty($request->first_name) ) {
                $user->first_name   = $request->first_name;
            }

            // update last_name if the last_name has value
            if ( !empty($request->last_name) ) {
                $user->last_name   = $request->last_name;
            }

            // update password if the password has value
            if ( !empty($request->password) ) {
                $user->password     = bcrypt($request->password);
            }

            
            // update email if the email has value
            if ( !empty($request->email) ) {
                $user->email        = $request->email;
            }
        
            $user->save();
        
            return response()->json([
                'message'   => 'User successfully updated',
                'error'     => false
                ]);
            
        } catch(ModelNotFoundException $exception) {
            
            // User not found
            return response()->json([
                'message'   => 'User not found',
                'error'     => true
                ]);
            
        } catch(ValidationException $exception) {
            
            // Email is not valid or already in use
            return response()->json([
                'message'   => 'Email is not valid or already in use',
                'error'     => true
                ]);
            
        }
        
    }    
    
}
