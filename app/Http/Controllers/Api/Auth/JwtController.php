<?php

namespace App\Http\Controllers\Api\Auth;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JwtController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->all(['email', 'password']);

        $validator = Validator::make(
            $credentials,
            [
                'email' => 'required|string|email',
                'password' => 'required|string'
            ],
            [
                'email.required' => 'Identicação de usuário é obrigtório',
                'email.email' => 'Endereço de email não é valido',
                'password.required' => 'Senha não informada'
            ],
            [
                'email' => 'Email',
                'password' => 'Senha'
            ]
        )->validate();
        // if ($validator->fails()) {
        //     return response()->json($validator);
        // }

        if (!$token = auth('api')->attempt($credentials)) {
            $messages = new ApiMessages("Não autorizado.");
            return response()->json($messages->getMessage(), 401);
        }

        // return response()->json(['token' => 'Bearer ' . $token]);
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Usuário desconectado.']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
