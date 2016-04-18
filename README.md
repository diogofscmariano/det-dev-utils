# det dev utils

Personal utils scripts to assist Data Exploration Tool development.

## dev web server

Needs local PHP >5.4.0 instalation.

Note: On Windows you [must enable php_fileinfo.dll](http://stackoverflow.com/questions/3579072/php-fileinfo-is-undefined-function/3579113#3579113) extension and ensure the [php command is in the system's path](http://php.net/manual/en/faq.installation.php#faq.installation.addtopath).

0. Preferably symlink serve-det.sh to somewhere in your path; on Windows add this repository folder to the system's path;
1. Configure using `--configure` argument (see [options](#configuration-options) bellow);
2. Compile and package https://github.com/pentaho/pentaho-det; copy it to PDI's system/karaf/deploy folder;
3. Repeat step 2 whenever changing any maven dependency or modifying JAVA code;
4. Run Spoon if not already running;
5. Run `serve-det.sh`/`serve-det.bat` (from DET project root folder if not explicitly configured `det` option);
6. Open http://localhost:9000/ in your browser;
7. Simply refresh the browser to load any changes to the various `resources/web` files.

### configuration options

The `--configure` argument allows to configure the tool, persisting the options in the `.serve-det` file (on the det dev utils folder).

These are the available options:

`--det=path`: [DET project](https://github.com/pentaho/pentaho-det) root folder; defaults to the current working directory;

`--build-version=version`: DET build version; defaults to `7.0-SNAPSHOT`;

`--common-ui=path`: [Common-UI project](https://github.com/pentaho/pentaho-platform-plugin-common-ui) root folder; defaults to using the embeded in PDI;

`--pdi-host=host`: Hostname of the machine running PDI; defaults to `localhost`;

`--pdi-port=port`: Port serving PDI web resources; defaults to `9050`;

`--mock-data=yes|no`: Whenever use a mock datasource or the generated in PDI; defaults to `true`;

`--mock-data-dir=path`: If mocking the datasource, where to find the data (in JSON format); defaults to the included `mock_data` folder.

Example:
```
serve-det.sh --configure --common-ui /Users/me/dev/common-ui --pdi-port=9051
```

*Note*: All paths must be absolute paths.

*Note*: `serve-det.bat` still doesn't support configuration options.

---
TODO: Add livereload support
