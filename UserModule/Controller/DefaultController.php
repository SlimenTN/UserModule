<?php
namespace app\UserModule\Controller;

use app\UserModule\Entity\User;
use app\UserModule\Repository\UserRepository;
use framework\core\Controller\AppController;
use framework\core\Exception\RuntimeException;
use framework\core\Request\HTTPHandler;

class DefaultController extends AppController
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var array
     */
    private $configuration;
    
    public function __construct()
    {
        parent::__construct();
        $this->repository = $this->getRepository('User:User');
        $this->configuration = include __DIR__.'/../Config/module_configuration.php';
    }

    /**
     * Login command
     * @throws \Exception
     */
    public function loginCommand(){

        $routeAfterLogin = $this->configuration['after_login_route'];

        $session = $this->session();
        
        if($this->userConnected()){
            $this->redirectToRoute($routeAfterLogin);
        }else{
            $errorMsg = '';
            $data = $this->getHttpHandler()->get(HTTPHandler::POST);

            if (!empty($data)) {
                $pass = $this->cryptPassword($data['password']);
                $user = $this->repository->authenticate($data['login'], $pass);
                if($user != null){
                    $session->push('user', $user);
                    if($session->exist('old_url')){//---if old url exist redirect to it
                        
                        $olrUrl = $session->get('old_url');
                        $session->remove('old_url');
                        header('Location: ' . $olrUrl);
                        exit;
                        
                    }else{//--else redirect to route after login
                        
                        $this->redirectToRoute($routeAfterLogin);
                        
                    }

                }else{
                    $errorMsg = 'Login ou mot de passe incorrect !';
                }
            }

            $this->paintView('User:login.html.twig', array(
                'error' => $errorMsg,
            ));
        }
    }

    /**
     * Register new user
     * @throws RuntimeException
     * @throws \Exception
     */
    public function registrationCommand(){
        $user = new User();

        $form = $this->buildForm('User:User', $user);

        if($form->isPosted()){
            $p = $this->cryptPassword($user->getPassword());
            $user->setPassword($p);
            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();

            $this->redirectToRoute('login');
        }

        $this->paintView('User:registration.html.twig', array(
            'form' => $form,
        ));
    }

    /**
     * @throws \Exception
     */
    public function profileCommand(){
        $this->call('user.secure.command');

        $user = $this->connectedUser();
        $user = $this->repository->find($user->getId());
        $oldPassword = $user->getPassword();

        $form = $this->buildForm('User:UserUpdate', $user);

        $message = null;
        if($form->isPosted()){
            $data = $form->getData();
            $confirmationPassword = $this->cryptPassword($data['oldPassword']);
           if($confirmationPassword != $oldPassword){
               $message = 'The confirmation password does not match with the user\'s password. Please check it and retry again.';
           }else{
               if($user->getPassword() == ''){
                   $user->setPassword($oldPassword);
               }else{
                   $user->setPassword($this->cryptPassword($user->getPassword()));
               }
               $em = $this->getEntityManager();

               $em->persist($user);
               $em->flush();

               $this->session()->push('user', $user);//---push updated user to the session

               $message = 'The user has been successfully update.';
           }
        }

        $this->paintView('User:profile.html.twig', array(
            'form' => $form,
            'message' => $message,
        ));

    }

    /**
     * Get connected user
     * @return User
     */
    private function connectedUser(){
        return ($this->session()->exist('user')) ? $this->session()->get('user') : null;
    }

    /**
     * Encrypt given password using the encryption method 
     * existing in the configuration file
     * @param $password
     * @return string
     * @throws RuntimeException
     */
    private function cryptPassword($password){
        $cryptMethod = $this->configuration['encryption'];
        $encryptedPassword = null;

        switch ($cryptMethod){
            case 'md5':
                $encryptedPassword = md5($password);
                break;
            case 'bcrypt':
                $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
                break;
            case 'sha1':
                $encryptedPassword = sha1($password);
                break;
            default:
                throw new RuntimeException('No encryption method is defined! Please check the configuration file of the UserModule.');
                break;
        }

        return $encryptedPassword;
    }

    /**
     * Check if user is connected
     * @return mixed
     */
    private function userConnected(){
        return $this->session()->exist('user');
    }

    /**
     * Logout command
     */
    public function logoutCommand(){
        $this->session()->remove('user');
        $this->redirectToRoute('login');
    }
}