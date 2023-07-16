<?php

namespace App\Repositories;

use App\Constantes;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($userId)
    {
        return User::findOrFail($userId);
    }

    public function deleteUser($userId)
    {
        //User::destroy($userId);
    }

    public function createUser(array $userDetails)
    {
        //We are storing the user data in database
        $user = User::create($userDetails);

        //Update old existing User Group (usefull when updating)
        DB::table('groupe_user')->where(['user_id' => $user->id, 'actif' => Constantes::ETAT_ACTIF])
            ->update(['actif'=>Constantes::ETAT_INACTIF]);

        //Store User Group
        $user->groupes()->attach($userDetails->groupe_id, [
            'actif' => Constantes::ETAT_ACTIF
        ]);

        //Store User Apostolats
        DB::table('apostolat_user')->where(['user_id' => $user->id])->delete();
        foreach($request->input('apostolat_id') as $apostolat_id){
            $user->apostolats()->attach([
                'apostolat_id' => $apostolat_id
            ]);
        }

        return $user;
    }

    public function updateUser($userId, array $newDetails)
    {
        return User::whereId($userId)->update($newDetails);
    }

    public function getFulfilledUsers()
    {
        return User::where('is_fulfilled', true);
    }
}
