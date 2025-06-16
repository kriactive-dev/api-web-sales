<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * @OA\Tag(
 *     name="Roles",
 *     description="Endpoints para gerenciamento de papéis (roles)"
 * )
 */
class RoleController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/roles",
     *      operationId="getRolesList",
     *      tags={"Roles"},
     *      summary="Listar todos os papéis (roles)",
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Lista de papéis retornada com sucesso",
     *      ),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
   public function index()
    {
        return Role::with('permissions')->get();
    }

    /**
     * @OA\Post(
     *      path="/api/roles",
     *      operationId="createRole",
     *      tags={"Roles"},
     *      summary="Criar novo papel (role)",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="admin")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Papel criado com sucesso",
     *      ),
     *      @OA\Response(response=422, description="Erro de validação"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|unique:roles,name']);
        $role = Role::create($data);
        return response()->json($role, 201);
    }

    /**
     * @OA\Get(
     *      path="/api/roles/{id}",
     *      operationId="getRoleById",
     *      tags={"Roles"},
     *      summary="Exibir um papel (role) específico",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do papel (role)",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Detalhes do papel retornado com sucesso",
     *      ),
     *      @OA\Response(response=404, description="Papel não encontrado"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function show(Role $role)
    {
        return $role->load('permissions');
    }

    /**
     * @OA\Put(
     *      path="/api/roles/{id}",
     *      operationId="updateRole",
     *      tags={"Roles"},
     *      summary="Atualizar um papel (role)",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do papel (role)",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="editor")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Papel atualizado com sucesso",
     *      ),
     *      @OA\Response(response=422, description="Erro de validação"),
     *      @OA\Response(response=404, description="Papel não encontrado"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);
        $role->update($data);
        return response()->json($role);
    }

    /**
     * @OA\Delete(
     *      path="/api/roles/{id}",
     *      operationId="deleteRole",
     *      tags={"Roles"},
     *      summary="Remover um papel (role)",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID do papel (role)",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Papel removido com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Role removida")
     *          )
     *      ),
     *      @OA\Response(response=404, description="Papel não encontrado"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Role removida']);
    }
}
