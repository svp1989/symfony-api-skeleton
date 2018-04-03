<?php

namespace App\Controller;

use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityRoleController
 * Role management
 * @package App\Controller
 */
class SecurityRoleController extends Controller
{
    /**
     * Add new role
     * @SWG\Tag(
     *     name="Role"
     * )
     * @SWG\Response(
     *     response="200",
     *     description="Add role from user",
     *     @SWG\Schema(
     *          type="object",
     *          example={
     *              "code": 200,
     *              "success": "ok"
     *          },
     *          @SWG\Property(property="token", type="object", description="Try reset lifetime JWT token"),
     *     )
     * )
     *
     * @SWG\Parameter(
     *      name="ROLE",
     *      in="body",
     *      required=true,
     *      description="Add new role to user only admin",
     *      @SWG\Schema(
     *          type="object",
     *          example={"user_id": "0", "role": "admin"}
     *      )
     * )
     * @SWG\Response(
     *      response=401,
     *      description="Permission denied"
     * )
     * @Route("/role/add", methods={"put"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getRoleAddAction(Request $request): JsonResponse
    {
        /**
         * @var $roleService \App\Service\RoleService
         */
        $roleService = $this->container->get('user_role_provider');
        $content = json_decode($request->getContent());
        try {
            $roleService->addRole($content->role, $content->user_id);
            return new JsonResponse(['code' => 200, 'success' => 'ok']);
        } catch (\Exception $e) {
            return new JsonResponse(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove role
     * @SWG\Tag(
     *     name="Role"
     * )
     * @SWG\Response(
     *     response="200",
     *     description="Remove role from user",
     *     @SWG\Schema(
     *          type="object",
     *          example={
     *              "code": 200,
     *              "success": "ok"
     *          },
     *          @SWG\Property(property="token", type="object", description="Try reset lifetime JWT token"),
     *     )
     * )
     *
     * @SWG\Parameter(
     *      name="ROLE",
     *      in="body",
     *      required=true,
     *      description="Add new role to user only admin",
     *      @SWG\Schema(
     *          type="object",
     *          example={"user_id": "0", "role": "lawyer"}
     *      )
     * )
     * @SWG\Response(
     *      response=401,
     *      description="Permission denied"
     * )
     * @Route("/role/remove", methods={"delete"})
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteRoleRemoveAction(Request $request): JsonResponse
    {
        /**
         * @var $roleService \App\Service\RoleService
         */
        $roleService = $this->container->get('user_role_provider');
        $content = json_decode($request->getContent());
        try {
            $roleService->removeRole($content->role, $content->user_id);
            return new JsonResponse(['code' => 200, 'success' => 'ok']);
        } catch (\Exception $e) {
            return new JsonResponse(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }


}
