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
        $player->avater = 1;
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

    public function updateSettings(Request $request)
    {
        $request->validate([
            'playerId' => 'required|exists:players,id',
            'avatar' => 'required|integer'
        ]);

        $player = Player::find($request->playerId);
        $player->avatar = $request->avatar;
        $player->save();

        return response()->json(['message' => 'Settings updated successfully']);
    }

    public function addFriend(Request $request)
    {
        $request->validate([
            'playerId' => 'required|exists:players,id',
            'friendUsername' => 'required|exists:players,username'
        ]);

        $player = Player::find($request->playerId);
        $friend = Player::where('username', $request->friendUsername)->first();

        if ($player->friends()->where('friend_id', $friend->id)->exists()) {
            return response()->json(['message' => 'Already friends'], 400);
        }

        $player->friends()->attach($friend->id);

        return response()->json(['message' => 'Friend added successfully']);
    }

    public function getOnlineStatus($playerId)
    {
        $player = Player::find($playerId);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json(['onlineStatus' => $player->online_status]);
    }

    public function getPhotonRoom($playerId)
    {
        $player = Player::find($playerId);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json(['photonRoom' => $player->photon_room]);
    }

    public function updateStatusAndRoom(Request $request)
    {
        $request->validate([
            'playerId' => 'required|exists:players,id',
            'onlineStatus' => 'required|boolean',
            'photonRoom' => 'nullable|string'
        ]);

        $player = Player::find($request->playerId);
        $player->online_status = $request->onlineStatus;
        $player->photon_room = $request->photonRoom;
        $player->save();

        return response()->json(['message' => 'Status and room updated successfully']);
    }
}