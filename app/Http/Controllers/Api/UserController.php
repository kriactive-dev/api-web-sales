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

    /**
     * @OA\Post(
     *      path="/api/users",
     *      operationId="createUser",
     *      tags={"Users"},
     *      summary="Criar novo usuário",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", example="João Silva"),
     *              @OA\Property(property="email", type="string", example="joao@email.com"),
     *              @OA\Property(property="password", type="string", example="123456")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Usuário criado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="João Silva"),
     *              @OA\Property(property="email", type="string", example="joao@email.com")
     *          )
     *      ),
     *      @OA\Response(response=422, description="Erro de validação"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
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

    /**
     * @OA\Get(
     *      path="/api/users/{id}",
     *      operationId="getUserById",
     *      tags={"Users"},
     *      summary="Exibir um usuário específico",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID do usuário",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Detalhes do usuário retornados com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="João Silva"),
     *              @OA\Property(property="email", type="string", example="joao@email.com"),
     *              @OA\Property(
     *                  property="roles",
     *                  type="array",
     *                  @OA\Items(type="string", example="admin")
     *              ),
     *              @OA\Property(
     *                  property="permissions",
     *                  type="array",
     *                  @OA\Items(type="string", example="create-users")
     *              )
     *          )
     *      ),
     *      @OA\Response(response=404, description="Usuário não encontrado"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function show(User $user)
    {
        return $user->load('roles', 'permissions');
    }

    /**
     * @OA\Put(
     *      path="/api/users/{id}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Atualizar informações de um usuário",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID do usuário",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="João Atualizado"),
     *              @OA\Property(property="email", type="string", example="joao.atualizado@email.com"),
     *              @OA\Property(property="password", type="string", example="novasenha123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Usuário atualizado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="João Atualizado"),
     *              @OA\Property(property="email", type="string", example="joao.atualizado@email.com")
     *          )
     *      ),
     *      @OA\Response(response=422, description="Erro de validação"),
     *      @OA\Response(response=404, description="Usuário não encontrado"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
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

    /**
     * @OA\Delete(
     *      path="/api/users/{id}",
     *      operationId="deleteUser",
     *      tags={"Users"},
     *      summary="Remover um usuário",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID do usuário",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Usuário removido com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Usuário removido")
     *          )
     *      ),
     *      @OA\Response(response=404, description="Usuário não encontrado"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Usuário removido']);
    }

    /**
     * @OA\Post(
     *      path="/api/users/{id}/roles",
     *      operationId="assignRolesToUser",
     *      tags={"Users"},
     *      summary="Atribuir roles a um usuário",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID do usuário",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"roles"},
     *              @OA\Property(
     *                  property="roles",
     *                  type="array",
     *                  @OA\Items(type="string", example="admin")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Roles atribuídos com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Roles atribuídos")
     *          )
     *      ),
     *      @OA\Response(response=422, description="Erro de validação"),
     *      @OA\Response(response=404, description="Usuário não encontrado"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function assignRoles(Request $request, User $user)
    {
        $data = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $user->syncRoles($data['roles']);
        return response()->json(['message' => 'Roles atribuídos']);
    }

    /**
     * @OA\Post(
     *      path="/api/users/{id}/permissions",
     *      operationId="assignPermissionsToUser",
     *      tags={"Users"},
     *      summary="Atribuir permissões a um usuário",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID do usuário",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"permissions"},
     *              @OA\Property(
     *                  property="permissions",
     *                  type="array",
     *                  @OA\Items(type="string", example="create-users")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Permissões atribuídas com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Permissões atribuídas")
     *          )
     *      ),
     *      @OA\Response(response=422, description="Erro de validação"),
     *      @OA\Response(response=404, description="Usuário não encontrado"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
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
