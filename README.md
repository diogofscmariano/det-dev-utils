# det dev utils

Personal utils scripts to assist Data Exploration Tool development.

## dev web server

Needs local PHP >5.4.0 instalation.

0. Preferably symlink serve-det.sh to somewhere in your path
1. Compile and package pdi-det-assembly/target/pdi-det-assembly.kar
2. Copy it to PDI's system/karaf/deploy folder
3. Repeat the process if changing any maven dependency or modifying JAVA code
4. Run Spoon if not already running
5. Run serve-det.sh from det project root folder
6. Open http://localhost:9000DataExplorerTool/web/index.html in your browser
7. Simply refresh the browser to load any changes to ´´*/src/main/resources/web´´ files

TODO: Add livereload support
