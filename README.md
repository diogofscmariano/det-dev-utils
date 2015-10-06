# det dev utils

Personal utils scripts to assist Data Exploration Tool development.

## dev web server

Needs local PHP >5.4.0 instalation.

Note: On Windows you [must enable php_fileinfo.dll](http://stackoverflow.com/questions/3579072/php-fileinfo-is-undefined-function/3579113#3579113) extension and ensure the [php command is in the system's path](http://php.net/manual/en/faq.installation.php#faq.installation.addtopath).

0. Preferably symlink serve-det.sh to somewhere in your path; on Windows add this repository folder to the system's path;
1. Compile and package pdi-det-assembly/target/pdi-det-assembly.kar;
2. Copy it to PDI's system/karaf/deploy folder;
3. Repeat the process if changing any maven dependency or modifying JAVA code;
4. Run Spoon if not already running;
5. Run serve-det.sh/serve-det.bat from det project root folder;
6. Open http://localhost:9000/DataExplorerTool/web/index.html in your browser;
7. Simply refresh the browser to load any changes to src/main/resources/web files.

TODO: Add livereload support
