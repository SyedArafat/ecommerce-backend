<?php

namespace App\Http\Controllers;

use App\Ecommerce\Auth\AuthStatic;
use App\Ecommerce\Auth\UserRepository;
use App\Ecommerce\Statics\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class AuthController extends Controller
{
    /**
    * Get a JWT via given credentials.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(['token' => $token, 'info' => $this->me()]);
    }

    /**
     * Register a User.
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function register(Request $request, UserRepository $userRepository): JsonResponse
    {
        $validator = Validator::make($request->all(), AuthStatic::registerValidateRules());

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        $user = $userRepository->create(array_merge($validator->validated(),
            ['password' => bcrypt($request->password), 'role' => UserRole::CUSTOMER]));

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }
}
