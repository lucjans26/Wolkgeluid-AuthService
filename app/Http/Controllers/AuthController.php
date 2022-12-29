<?php

namespace App\Http\Controllers;

use App\Classes\Responses\Response;
use App\Classes\Responses\ValidResponse;
use App\Models\User;
use App\Traits\MessageTrait;
use App\Traits\OauthTrait;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect()
    {
        return OauthTrait::login('google');
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $pat = OauthTrait::loggedInUser($googleUser);
        $user = User::where('email', $googleUser->email)->first();
        $token = PersonalAccessToken::where('token', $pat)->first();
        MessageTrait::publish('user', json_encode(['action'=>'login', 'token' => ['token' => $pat, 'user_id' => $user->id, 'abilities' => ['artist', 'album', 'music']]]));
        $response = new ValidResponse([$user, $token, 'accessToken'=>$pat]);
        return redirect("http://127.0.0.1:3000")->with('response', $response);
    }

    public function deleteUser($request)
    {
        $user = auth()->user();
        $user_id = $user->id;
        $user->tokens()->delete();
        $user->delete();

        MessageTrait::publish('user', json_encode(['action'=>'delete', 'user_id' => $user_id]));
        $response = new ValidResponse(['message' => 'User deleted']);
        return response()->json($response, 200);
    }

    public function logout()
    {
        $user = auth()->user();
        $tokens = PersonalAccessToken::where('tokenable_id', $user['id'])->get();
        foreach ($tokens as $token)
        {
            $token->delete();
        }
        MessageTrait::publish('user', json_encode(['action' => 'logout', 'user_id' => $user['id']]));
        return redirect()->to('http://localhost:5500/login.html');
    }
}
