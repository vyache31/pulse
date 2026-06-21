<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class GitHubController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }
 
    public function callback()
    {
        $githubUser = Socialite::driver('github')->user();
        // $githubUser даёт: getId(), getName(), getEmail(), getNickname(), ...
 
        $user = User::updateOrCreate(
            ['github_id' => $githubUser->getId()],
            [                                         
                'username'     => $githubUser->getName() ?: $githubUser->getNickname(),
                'email'    => $githubUser->getEmail(),
                'password' => bcrypt(uniqid()), 
				'role' => UserRole::USER,
           ]
        );
 
        Auth::login($user, remember: true);
 
        return redirect()->route('workspaces.index');
    }
}
