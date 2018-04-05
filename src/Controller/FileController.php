<?php

namespace App\Controller;

use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\UploadLimits;
use App\Entity\Files;

/**
 * Class FileController
 * @package App\Controller
 */
class FileController extends Controller
{
    /**
     * Upload file on the server
     * @SWG\Tag(
     *      name="Files"
     * )
     * @SWG\Parameter(
     *      name="file",
     *      in="formData",
     *      required=true,
     *      type="file",
     *      format="binary",
     *      description="File data",
     * )
     * @SWG\Parameter(
     *      name="description",
     *      in="formData",
     *      type="string",
     *      description="File description"
     * )
     * @SWG\Response(
     *      response=200,
     *      description="File uploaded",
     *      @SWG\Schema(
     *          type="object",
     *          example={"success": "ok", "created": true, "id": 1},
     *          @SWG\Property(property="success", type="string", description="File uploaded"),
     *          @SWG\Property(property="created", type="boolean", description="File created"),
     *          @SWG\Property(property="id", type="int", description="File identifier")
     *      )
     * )
     * @SWG\Response(
     *      response=204,
     *      description="No content, file empty",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @SWG\Response(
     *      response=401,
     *      description="Bad credentials",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @SWG\Response(
     *      response=409,
     *      description="File upload error",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @SWG\Response(
     *      response=413,
     *      description="Payload too large",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @SWG\Response(
     *      response=415,
     *      description="Unsupported media type",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @Route(
     *      "/files/upload",
     *      methods={"POST"},
     *      defaults={"_api_resource_class"="App\Entity\Files"}
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getFilesUploadAction(Request $request): JsonResponse
    {
        $preAuthToken = $this->container->get('token_authenticator');
        $token = $preAuthToken->getCredentials($request);
        $user = $preAuthToken->getUser($token);
        $uploaded = $request->files->get('file');
        $description = (string)$request->request->get('description');
        $code = (int)$request->request->get('code');
        $workDir = dirname($this->container->getParameter('kernel.root_dir'));
        $uploadDir = getenv('FILES_UPLOAD_DIR');

        if (!$uploadDir) {
            $uploadDir = 'var/public';
        }

        if (!$uploaded or $uploaded->getClientSize() === 0) // загрузка пустых файлов отключена
        {
            return new JsonResponse(array(
                'code' => 204,
                'message' => 'No content, file empty'
            ), 204);
        }

        if (!$uploaded->isValid())
        {
            return new JsonResponse(array(
                'code' => 409,
                'message' => 'File upload error'
            ), 409);
        }

        $limits = new UploadLimits($uploaded);
        $mimeTypes = $limits->getMimeTypes();

        if (!empty($mimeTypes) && !in_array($uploaded->getMimeType(), $mimeTypes))
        {
            return new JsonResponse(array(
                'code' => 415,
                'message' => 'Unsupported media type'
            ), 415);
        }

        $clientSize = $uploaded->getClientSize();

        if ($clientSize > $limits->getMaxFilesize())
        {
            return new JsonResponse(array(
                'code' => 413,
                'message' => 'Payload too large'
            ), 413);
        }

        $uploadDir = "$workDir/$uploadDir";
        $fileName = $uploaded->getClientOriginalName();
        $mimeType = $uploaded->getMimeType();
        $hash = hash_file('sha256', $uploaded->getRealPath());
        $uploaded->move($uploadDir, $hash);
        unset($uploaded);



        $now = new \DateTime();

            $file = new Files();
            $file->setHash($hash)
                ->setOwner($user) // у файла есть владелец со своим подкаталогом
                ->setCreatedAt($now);
            $created = true;


        $file->setCode($code)
            ->setName($fileName)
            ->setDescription($description)
            ->setType($mimeType)
            ->setSize($clientSize);

        $em = $this->getDoctrine()->getManager();
        $em->persist($file);
        $em->flush();

        return new JsonResponse(array(
            'success' => 'ok',
            'created' => $created,
            'id' => $file->getId()
        ));
    }

}
