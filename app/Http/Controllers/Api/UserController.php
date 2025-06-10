<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="API de Gestão de Usuários",
     *      description="Documentação oficial da API de gestão de usuários.",
     * )
     *
     * @OA\Tag(
     *     name="Users",
     *     description="Endpoints para gerenciamento de usuários"
     * )
     */
class UserController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/users",
     *      operationId="getUsersList",
     *      tags={"Users"},
     *      summary="Listar todos os usuários",
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Lista de usuários retornada com sucesso"
     *      ),
     *      @OA\Response(response=401, description="Não autorizado"),
     * )
     */
    public function index()
    {
        return User::with('roles', 'permissions')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return $user->load('roles', 'permissions');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6'
        ]);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Usuário removido']);
    }

    public function assignRoles(Request $request, User $user)
    {
        $data = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $user->syncRoles($data['roles']);
        return response()->json(['message' => 'Roles atribuídos']);
    }

    public function givePermissions(Request $request, User $user)
    {
        $data = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $user->syncPermissions($data['permissions']);
        return response()->json(['message' => 'Permissões atribuídas']);
    }
}
