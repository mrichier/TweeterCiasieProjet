<?php


require_once "mf/utils/ClassLoader.php";

use \mf\auth\Authentication;


class AuthenticationTest extends \PHPUnit\Framework\TestCase {

    public function __construct(){
        $prefix = '.';

        new \mf\utils\ClassLoader($prefix);
        parent::__construct();
    }

    function testAuthenticationSubclass(){
        $this->assertTrue(is_subclass_of('\mf\auth\Authentication', '\mf\auth\AbstractAuthentication'),
            "La classe Authentication doit hÃ©ritÃ© de AbstractAuthentication");
    }

    function testAuthenticationNoSession(){

        $a = new Authentication();

        $msg1 = "Test du constructeur : Lorsque aucun utilisateur n'est connectÃ© la variable de session \$SESSION['user_login'] n'est pas renseignÃ©e. ";

        $this->assertNull($a->user_login, $msg1.
            "Du coup l'attribut user_login doit avoir la valeur null.");
        $this->assertEquals($a->access_level, Authentication::ACCESS_LEVEL_NONE, $msg1.
            "Du coup l'attribut access_level doit valoir ACCESS_LEVEL_NONE.");
        $this->assertFalse($a->logged_in, $msg1.
            "Du coup l'attribut logged_in doit avoir la valeur false.");

    }

    function testAuthenticationSession(){

        $_SESSION['user_login']   = 'john';
        $_SESSION['access_level'] = 100;

        $a = new Authentication();

        $msg1 = "Test du constructeur : Lorsque un utilisateur est connectÃ© la variable de session \$SESSION['user_login'] contient sont identifiant ett \$_SESSION['access_level'] son niveau d'accÃ¨s. ";

        $this->assertEquals($a->user_login, $_SESSION['user_login'], $msg1.
            "L'attribut user_login doit avoir la valeur de \$_SESSION['user_login']");
        $this->assertEquals($a->access_level, $_SESSION['access_level'],$msg1.
            "L'attribut access_level doit avoir la valeur de \$_SESSION['ccess_level']");
        $this->assertTrue($a->logged_in, $msg1.
            "L'attribut logged_in doit avoir la valeur true");
    }


    function testUpdateSession(){

        $a = new Authentication();

        $username = 'john';
        $level = 900;

        $msg1 = "Test de la mÃ©thode updateSession : Lorsque un utilisateur se connecte, s'il est correctement authentifiÃ© les variables de session doivent Ãªtre correctement renseignÃ©es. ";

        $mth = self::getMethod('updateSession');

        $mth->invokeArgs($a,array($username, $level));

        $this->assertEquals($_SESSION['user_login'], $username, $msg1.
            "La variable de session \$_SESSION['user_login'] doit contenir son identifiant.");

        $this->assertEquals($_SESSION['access_level'], $level, $msg1.
            "La variable de session \$_SESSION['access_level'] doit contenir son niveau d'accÃ¨s.");

        $this->assertEquals($a->user_login, $username, $msg1.
            "L'attribut user_login doit contenir son identifiant.");

        $this->assertEquals($a->access_level, $level, $msg1.
            "L'attribut user_login doit contenir son niveau d'accÃ¨s.");
    }


    function testLogout(){

        $msg1 = "Test de la mÃ©thode logout : Lorsque un utilisateur se dÃ©connecte, les variables de session sont effacÃ©es et les attributs rÃ©initialisÃ©s. ";

        $_SESSION['user_login']   = 'john';
        $_SESSION['access_level'] = 100;

        $a = new Authentication();

        $a->logout();

        $this->assertFalse(isset($_SESSION['user_login']), $msg1.
            "La variable de session \$_SESSION['user_login'] doit Ãªtre effacÃ©e.");

        $this->assertFalse(isset($_SESSION['access_level']), $msg1.
            "La variable de session \$_SESSION['access_level'] doit Ãªtre effacÃ©e.");

        $this->assertNull($a->user_login, $msg1.
            "Du coup l'attribut user_login doit avoir la valeur null.");
        $this->assertEquals($a->access_level, Authentication::ACCESS_LEVEL_NONE, $msg1.
            "Du coup l'attribut access_level doit valoir ACCESS_LEVEL_NONE.");
        $this->assertFalse($a->logged_in, $msg1.
            "Du coup l'attribut logged_in doit avoir la valeur false.");

    }


    function testCheckAccessRightLogged(){
        $_SESSION['user_login']   = 'john';
        $_SESSION['access_level'] = 100;

        $a = new Authentication();

        $msg1 = "Test de la mÃ©thode checkAccessRight : elle prend le niveau d'accÃ¨s nÃ©cessaire en paramÃ¨tre et le compare au niveau de l'utilisateur.";

        $this->assertFalse($a->checkAccessRight(200), $msg1."Elle doit retourner false, si le niveau requis est supÃ©rieur au niveau d'accÃ¨s de l'utilisateur." );
        $this->assertTrue($a->checkAccessRight(100), $msg1."Elle doit retourner true, si le niveau requis est infÃ©rieur ou Ã©gale au niveau d'accÃ¨s de l'utilisateur." );

        unset($_SESSION['user_login']);
        unset($_SESSION['access_level']);

        $a = new Authentication();
        $this->assertFalse($a->checkAccessRight(200), $msg1."Si l'utilisateur n'est pas connectÃ©e, elle doit retourner false, si le niveau requis est supÃ©rieur au niveau minimum ACCESS_LEVEL_NONE." );
        $this->assertTrue($a->checkAccessRight(Authentication::ACCESS_LEVEL_NONE), $msg1."Si l'utilisateur n'est pas connectÃ©e, elle doit retourner true, si le niveau requis est le  niveau minimum ACCESS_LEVEL_NONE." );

    }





    protected static function getMethod($name) {
        $class = new \ReflectionClass('\mf\auth\Authentication');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    function testVerifyPassword(){

        $msg1 = "Test de la mÃ©thode verifyPassword : elle retourne vrai si le mot de passe et le hachÃ© qu'elle reÃ§oit en paramÃ¨tre correspondent. ";

        $a = new Authentication();
        $pass = "john";
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $vmth = self::getMethod('verifyPassword');

        $this->assertTrue($vmth->invokeArgs($a,array($pass, $hash)), $msg1."La vÃ©rification n'est pas correcte. VÃ©rifiez que les paramÃ¨tres passÃ©e sont les bons, et dans les bon ordre.");

    }

    function testHashPassword(){

        $msg1 = "Test de la mÃ©thode hashPassword : elle retourne le hachÃ© du mot de passe qu'elle reÃ§oit en paramÃ¨tre. ";

        $a = new Authentication();
        $pass = "john";
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $hmth = self::getMethod('hashPassword');
        $vmth = self::getMethod('verifyPassword');

        $h = $hmth->invokeArgs($a,array($pass));

        $this->assertTrue($vmth->invokeArgs($a,array($pass, $h)), $msg1."Le hachage n'est pas correcte. VÃ©rifiez que l'algorithme est bien renseignÃ© (PASSWORD_DEFAULT), et que le hachÃ© est bien retournÃ© par la mÃ©thodes.");

    }


    function testLogin(){

        $msg1 = "Test de la mÃ©thode login : elle effectue l'Authentication en vÃ©rifiant le mot de passe fourni est celui stockÃ© par l'application et met a jour les variable de session. ";

        $user = "john";
        $pass = "john";
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $level = 300;

        $a = new Authentication();

        $a->login($user, $hash, $pass, $level);

        $this->assertTrue(isset($_SESSION['user_login']), $msg1."La variable de session \$_SESSION['user_login'] doit Ãªtre renseignÃ©e.");

        $this->assertTrue(isset($_SESSION['access_level']), $msg1."La variable de session \$_SESSION['access_level'] doit Ãªtre renseignÃ©.");

        $this->assertEquals($_SESSION['user_login'], $user, $msg1."La variable de session \$_SESSION['user_login'] doit contenir son identifiant.");

        $this->assertEquals($_SESSION['access_level'], $level, $msg1."La variable de session \$_SESSION['access_level'] doit contenir son niveau d'accÃ¨s.");

        $this->assertEquals($a->user_login, $user, $msg1."L'attribut user_login doit contenir son identifiant.");

        $this->assertEquals($a->access_level, $level, $msg1."L'attribut user_login doit contenir son niveau d'accÃ¨s.");


    }


    function testLoginFail(){

        $msg1 = "Test de la mÃ©thode login : elle effectue l'Authentication en vÃ©rifiant le mot de passe fourni est celui stockÃ© par l'application et met a jour les variable de session. ";

        $user = "john";
        $pass = "john";
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $level = 300;
        $pass = "wrong";

        $a = new Authentication();
        try{
            $a->login($user, $hash, $pass, $level);
        }
        catch (\Exception $e){
            return;
        }

        $this->fail($msg1."Si le mot de passe fournie est incorrect, elle doit soulever une exception.");




    }







}
