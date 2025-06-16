<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

/**
 * @OA\Tag(
 *     name="Permissions",
 *     description="Endpoints para gerenciamento de permissões"
 * )
 */

class PermissionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/permissions",
     *      operationId="getPermissionsList",
     *      tags={"Permissions"},
     *      summary="Listar todas as permissões",
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Lista de permissões retornada com sucesso",
     *          
     *      ),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function index()
    {
        return Permission::all();
    }

    /**
     * @OA\Post(
     *      path="/api/permissions",
     *      operationId="createPermission",
     *      tags={"Permissions"},
     *      summary="Criar nova permissão",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="create-users")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Permissão criada com sucesso",
     *          
     *      ),
     *      @OA\Response(response=422, description="Erro de validação"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|unique:permissions,name']);
        $permission = Permission::create($data);
        return response()->json($permission, 201);
    }

    /**
     * @OA\Get(
     *      path="/api/permissions/{id}",
     *      operationId="getPermissionById",
     *      tags={"Permissions"},
     *      summary="Exibir uma permissão específica",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID da permissão",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Detalhes da permissão",
     *          
     *      ),
     *      @OA\Response(response=404, description="Permissão não encontrada"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function show(Permission $permission)
    {
        return $permission;
    }

    /**
     * @OA\Put(
     *      path="/api/permissions/{id}",
     *      operationId="updatePermission",
     *      tags={"Permissions"},
     *      summary="Atualizar permissão",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID da permissão",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="edit-users")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Permissão atualizada com sucesso",
     *          
     *      ),
     *      @OA\Response(response=422, description="Erro de validação"),
     *      @OA\Response(response=404, description="Permissão não encontrada"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate(['name' => 'required|unique:permissions,name,' . $permission->id]);
        $permission->update($data);
        return response()->json($permission);
    }

    /**
     * @OA\Delete(
     *      path="/api/permissions/{id}",
     *      operationId="deletePermission",
     *      tags={"Permissions"},
     *      summary="Remover permissão",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID da permissão",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Permissão removida com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Permissão removida")
     *          )
     *      ),
     *      @OA\Response(response=404, description="Permissão não encontrada"),
     *      @OA\Response(response=401, description="Não autorizado")
     * )
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['message' => 'Permissão removida']);
    }
}
