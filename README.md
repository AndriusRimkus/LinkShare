Instaliavimo instrukcijos:

composer update
sukuriu duombazę per phpMyAdmin
php app/console doctrine:migrations:diff
php app/console doctrine:migrations:migrate
susireguliuoti mailer parameters.yml (aš naudojau Gmail paslaugas, jam reikėjo nuimti apsaugas)