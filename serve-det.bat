SET DIR=%~dp0

php -S localhost:9000 -t . %DIR%router_dev.php
