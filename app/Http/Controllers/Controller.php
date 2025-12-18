<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Récupère l'utilisateur ou interrompt la requête.
     *
     * * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function getUser(Request $request): \App\Models\User
    {
        $user = $request->user();
        if (is_null($user)) {
            abort(401, 'Utilisateur non authentifié.');
        }

        /** @var \App\Models\User $user */
        return $user;
    }
}
