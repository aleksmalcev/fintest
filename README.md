# fintest
Example of MVC application by PHP and MySql without using frameworks and ORM libraries.
Only back-end part, front-end is simple, without any js and css 

Init database sql in \db\fintestdb.sql 

Database connection options: \src\Fintest\App.php - 
static function getDbConnectionProp()
    $res = [
        'host' => 'localhost',
        'user' => 'test',
        'psw' => 'test',
        'db' => 'fintest',
        
 Need run composer install before start using (don't use external library, only composer autoload)


Example of Fintest app: http://fintest.hotelroom.ru/
login, psw for test user in fintest app - 
login: test@test.test
psw: test 
