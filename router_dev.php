<?php
global $CONFIG_FILE, $options;

$tool_root = dirname(__FILE__);

$CONFIG_FILE = $tool_root . "/.serve-det";

include("config.php");

if ($_SERVER["REQUEST_URI"] === '/') {
  header('Location: /pentaho-det-core/' . $options["build-version"] . '/index.html');
  return;
}

$webroots = [
  'pentaho-det-core' => $options["det"].'/Implementation/Core/src/main/resources/web/',
  'pentaho-det-data-explorer-module' => $options["det"].'/Implementation/data-explorer-module/src/main/resources/web/'
];

$common_ui = false;

if(!empty($options["common-ui"])) {
  $webroots['common-ui'] = $options["common-ui"].'package-res/';

  $common_ui = true;
}

$pattern = '/^\/([^\/]+)\/' . preg_quote($options["build-version"]) . '\/(.*)$/';
$matches = null;

if ($common_ui && $_SERVER["REQUEST_URI"] === '/requirejs-manager/js/require-init.js') {
  header('Content-Type: text/javascript');
  $content = @file_get_contents('http://'.$options["pdi-host"].':' . $options["pdi-port"] . $_SERVER["REQUEST_URI"]);

  $i = strpos($content, "/* Following configurations are");

  echo substr($content, 0, $i) . "\n";
  readfile($webroots['common-ui'] . 'resources/web/common-ui-require-js-cfg.js');
  echo "\nrequireCfg.baseUrl = '/';\nrequire.config(requireCfg);\n";
  exit;
}

if (preg_match($pattern, $_SERVER["REQUEST_URI"], $matches)) {
  if(!empty($webroots[$matches[1]])) {
    $webroot = $webroots[$matches[1]];
    $file = $matches[2];
  }
} else if($common_ui && strpos($_SERVER["REQUEST_URI"], '/content/common-ui/') === 0) {
  $webroot = $webroots['common-ui'];
  $file = substr($_SERVER["REQUEST_URI"], 19);
}

if(!empty($webroot)) {
  if(($i = strpos($file, '?')) !== false) {
    $file = substr($file, 0, $i);
  }

  if($file === 'index.html') {
    $webroot = $webroot . '../../../../target/classes/web/';
  }

  if(file_exists($webroot . $file)) {
    $mime = '';
    if(substr_compare($file, '.js', strlen($file)-3, 3) === 0) {
      $finfo = finfo_open(FILEINFO_MIME_ENCODING);
      $mime = 'application/javascript; ';
    } else if(substr_compare($file, '.css', strlen($file)-4, 4) === 0) {
      $finfo = finfo_open(FILEINFO_MIME_ENCODING);
      $mime = 'text/css; ';
    } else {
      $finfo = finfo_open(FILEINFO_MIME);
    }

    header("Content-type: " . $mime . finfo_file($finfo, $webroot . $file));
    finfo_close($finfo);

    readfile($webroot . $file);
    exit;
  }

  error_log($_SERVER["REQUEST_URI"] . ' 404 (' . $webroot . $file . ')');
} else if ($options["mock-data"] && strpos($_SERVER["REQUEST_URI"], '/cxf/DataExplorerTool/det/dataSources') === 0) {
  header('Content-Type: application/json');

  if ($_SERVER["REQUEST_URI"] === '/cxf/DataExplorerTool/det/dataSources') {
    header('Content-Type: application/json');
    echo "[\n";
    $single = true;
    foreach(glob($options["mock-data-dir"] . '/*', GLOB_ONLYDIR) as $dir) {
      if(!$single) {
        echo ", ";
      }
      readfile($dir . '/info.json');
      $single = false;
    }
    echo "]";
    exit;
  } else {
    $pattern = '/^\/cxf\/DataExplorerTool\/det\/dataSources\/([^\/]+)\/?([^\/]+)?$/';
    $matches = null;

    if (preg_match($pattern, $_SERVER["REQUEST_URI"], $matches)) {
      if(count($matches) === 2) {
        readfile($options["mock-data-dir"] . '/' . $matches[1] . '/info.json');
        exit;
      } else if($matches[2] === 'data') {
        readfile($options["mock-data-dir"] . '/' . $matches[1] . '/data.json');
        exit;
      }
    }
  }
} else {
  $content = @file_get_contents('http://'.$options["pdi-host"].':' . $options["pdi-port"] . $_SERVER["REQUEST_URI"]);
  if($content !== false) {
    foreach ($http_response_header as $value) {
      if (preg_match('/^Content-Type:/i', $value)) {
        // Successful match
        header($value,false);
      }
    }

    error_log('FROM PDI - ' . $_SERVER["REQUEST_URI"]);

    echo $content;
    exit;
  }

  error_log($_SERVER["REQUEST_URI"] . ' 404 (' . 'http://'.$options["pdi-host"].':' . $options["pdi-port"] . $_SERVER["REQUEST_URI"] . ')');
}

header("HTTP/1.0 404 Not Found");
