<?php

namespace App\Traits;

use App\Classes\Responses\ValidResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

trait OauthTrait
{
    public static function login(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * logs in a socialite user or registers a new user if not already registered
     * @param $_user socialite user object
     **/
    public static function loggedInUser($_user)
    {
        $user = User::where('email', '=', $_user->email)->first();
        if(!$user)
        {
            $newUser = new User(['email' => $_user->email, 'name' => $_user->name, 'password' => Hash::make(random_bytes(24))]);
            $newUser->save();
            Auth::login($newUser, true);
            return $newUser->createToken(IdTrait::requestTokenId(), ['artist', 'album', 'music'])->plainTextToken;
        }
        Auth::login($user, true);
        return $user->createToken(IdTrait::requestTokenId(), ['artist', 'album', 'music'])->plainTextToken;
    }

}
