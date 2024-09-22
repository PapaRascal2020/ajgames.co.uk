<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use Illuminate\Support\Facades\Hash;

class PlayerController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:players',
            'password' => 'required'
        ]);

        $hashedEmailForAvatar = md5($request->username);

        $player = new Player();
        $player->username = $request->username;
        $player->password = Hash::make($request->password);
        $player->avater = "https://gravatar.com/avatar/$hashedEmailForAvatar";
        $player->save();

        return response()->json(['message' => 'Player registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $player = Player::where('username', $request->username)->first();

        if ($player && Hash::check($request->password, $player->password)) {
            $player->access_token = bin2hex(random_bytes(16));
            $player->save();

            return response()->json([
                'playerId' => $player->id,
                'username' => $player->username,
                'avatar' => $player->avatar,
                'accessToken' => $player->access_token
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}