<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $fields = $request->validate([
            'name' => 'sometimes|required|max:255',
            'numTelemovel' => 'sometimes|required|max:20',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'morada' => 'sometimes|required|max:255',
            'codPostal' => 'sometimes|required|max:20',
            'localidade' => 'sometimes|required|max:55',
            'observacoes' => 'sometimes|required|max:255',
            'password' => 'sometimes|required|confirmed',
        ]);

        if (isset($fields['password'])) {
            $fields['password'] = bcrypt($fields['password']);
        }

        $user->update($fields);

        return response()->json([
            'message' => 'Utilizador atualizado com sucesso.',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    // Soft delete do utilizador
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'message' => 'Utilizador apagado (soft delete) com sucesso.'
        ]);
    }
}
