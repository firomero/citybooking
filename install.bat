php app\console doctrine:database:drop --verbose >> install.log
php app\console doctrine:database:create --verbose  >> install.log
php app\console doctrine:schema:create --verbose >> install.log
php app\console assets:install --verbose >> install.log

echo "La aplicación se ha inicializado con éxito. Acceda con un navegador web http://localhost/citybooking"
