<?php

namespace App\Service;

use App\Security\TokenAuthenticator;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use App\Entity\Profile as ProfileEntity;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Profile
 * @package App\Service
 */
class Profile
{
    private $user;
    private $entityManager;
    private $profile;

    /**
     * Profile constructor.
     * @param TokenAuthenticator $authToken
     * @param RequestStack $requestStack
     * @param EntityManager $entityManager
     */
    public function __construct(TokenAuthenticator $authToken, RequestStack $requestStack, EntityManager $entityManager)
    {
        $request = $requestStack->getCurrentRequest();
        $token = $authToken->getCredentials($request);
        $this->user = $authToken->getUser($token);
        $this->entityManager = $entityManager;
        $this->profile = new ProfileEntity();
    }

    /**
     * @param $content
     * @return array|bool
     */
    public function create($content)
    {
        try {
            $profile = $this->user->getProfile();
            if (isset($profile)) {
                throw new \Exception("The profile is already created");
            }

            foreach ($content as $field => $value) {
                $method = 'set' . Inflector::classify($field);
                if ($field == 'patronymic') {
                    $this->profile->setPatronymic($content->patronymic);
                } elseif ($field == 'birthday') {
                    $this->profile->setBirthday(new \DateTime($content->birthday));
                } else {
                    $this->profile->$method($value);
                }
            }

            $this->user->setProfile($this->profile);
            $this->entityManager->persist($this->user);

            $this->entityManager->persist($this->profile);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            return ['code' => $e->getCode(), 'message' => $e->getMessage()];
        }
    }

    /**
     * @param $content
     * @return array|bool
     */
    public function update($content)
    {
        try {
            $profile = $this->user->getProfile();
            if (!isset($profile)) {
                return $this->create($content);
            }

            foreach ($content as $field => $value) {
                $method = 'set' . Inflector::classify($field);
                if ($field == 'patronymic') {
                    $profile->setPatronymic($content->patronymic);
                } elseif ($field == 'birthday') {
                    $profile->setBirthday(new \DateTime($content->birthday));
                } else {
                    $profile->$method($value);
                }
            }

            $this->entityManager->persist($profile);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            return ['code' => $e->getCode(), 'message' => $e->getMessage()];
        }

    }

}