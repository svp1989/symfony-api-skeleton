<?php
namespace App\Security;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @param JWTEncoderInterface $jwtEncoder
     * @param EntityManager       $em
     */
    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManager $em)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
    }
    /**
     * @inheritdoc
     */
    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );
        $token = $extractor->extract($request);
        if (!$token) {
            return;
        }
        return $token;
    }
    /**
     * @inheritdoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider = null)
    {
        if (!$credentials)
        {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }

        $now = (new \DateTime())->getTimestamp();
        $tokenIsInvalid = $this->em
            ->getRepository('App:InvalidToken')
            ->hasActualToken($credentials, $now);

        if ($tokenIsInvalid) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }

        $data = $this->jwtEncoder->decode($credentials);
        if ($data === false) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
        $username = $data['username'];

        return $this->em
            ->getRepository('App:User')
            ->findOneBy(['username' => $username]);
    }
    /**
     * @param $credentials
     * @return int
     */
    public function getExpiration($credentials): int
    {
        $data = $this->jwtEncoder->decode($credentials);
        if ($data === false) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
        return $data['exp'];
    }
    /**
     * @inheritdoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }
    /**
     * @inheritdoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
    }
    /**
     * @inheritdoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }
    /**
     * @inheritdoc
     */
    public function supportsRememberMe()
    {
        return false;
    }
    /**
     * @inheritdoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('Token is missing!', Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request)
    {
    }
}