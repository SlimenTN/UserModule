# UserModule
A module to manage users for Limpid

 1- Download the zip file and paste it inside your Limpid project under app directory<br>
 
 2- Launch (in your cmd) `php console schema:update -force` to update your database schema with the new user entity.
 
 3- Add this service to `settings.php` file under `framework/config` inside the service section:<br>
    <pre>//-services configuration
    'services' => [
        'user.secure.command' => [
            'class' => 'app\UserModule\Service\SecureCommand',
        ]
    ],</pre>
    
This service secure your commands form visitors (only connected user can access this command), what you need to do is to call this service whenever you want to secure a command:

    
    public function indexCommand(){
        $this->call('user.secure.command');
        
        echo 'You can access this command');
    }
 
The UserModule will check if the user is connected then it will give him access to this command otherwise the visitor will be redirected to the login page.

4- In the UserModule directory you will find a configuration file under `UserModule/Config` named `module_configuration.php` where you can set the default route after user is logged in and the encryption methode that you want to use.
