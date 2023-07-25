<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller {
    public function index( Request $request ) {
        $user_id = $request->header( 'user_id' );
        return Todo::where( 'user_id', $user_id )->get();
    }

    public function createTodo( Request $request ) {
        try {
            $validator = Validator::make( $request->all(), ['title' => 'required'] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 400 );
            }
            if ( Todo::create( array_merge( $request->only( 'title', 'description' ), ['user_id' => $request->header( 'user_id' )] ) ) ) {
                return response()->json( ['status' => 'success', 'message' => 'Todo Created Successfully'], 201 );
            }
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went Wrong'], 500 );
        }
    }

    public function updateTodo( Request $request ) {
        try {
            $validator = Validator::make( $request->all(), ['title' => 'required'] );
            if ( $validator->fails() ) {
                return response()->json( ['status' => 'failed', 'message' => $validator->errors()], 400 );
            }
            if ( Todo::where( ['user_id' => $request->header( 'user_id' ), 'id' => $request->id] )->update( $request->only( 'title', 'description', 'status' ) ) ) {
                return response()->json( ['status' => 'success', 'message' => 'Todo Updated Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Not found'], 404 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went Wrong'], 500 );
        }
    }

    public function deleteTodo( Request $request ) {
        try {
            if ( Todo::where( ['user_id' => $request->header( 'user_id' ), 'id' => $request->id] )->delete() ) {
                return response()->json( ['status' => 'success', 'message' => 'Todo Deleted Successfully'], 200 );
            }
            return response()->json( ['status' => 'failed', 'message' => 'Not found'], 404 );
        } catch ( \Throwable $th ) {
            return response()->json( ['status' => 'failed', 'message' => 'Something went Wrong'], 500 );
        }
    }
}
