<?php
global $CONFIG_FILE, $options;

$options = getopt("", ["det:", "build-version:", "common-ui:", "pdi-host:", "pdi-port:", "mock-data:", "mock-data-dir:"]);

if($options) {
  saveConfiguration($options);
  exit;
} else {
  $options = loadConfiguration();
}

if(empty($options["det"])) {
  $options["det"] = getcwd();
} else {
  ensureTrailingSlash($options["det"]);
}

if(empty($options["build-version"])) {
  $options["build-version"] = '7.0-SNAPSHOT';
}

if(!empty($options["common-ui"])) {
  ensureTrailingSlash($options["common-ui"]);
}

if(empty($options["pdi-host"])) {
  $options["pdi-host"] = "localhost";
}

if(empty($options["pdi-port"])) {
  $options["pdi-port"] = 9050;
}

$options["mock-data"] = empty($options["mock-data"]) || filter_var($options["mock-data"], FILTER_VALIDATE_BOOLEAN);

if(empty($options["mock-data-dir"])) {
  $options["mock-data-dir"] = $tool_root.'/mock_data';
} else {
  ensureTrailingSlash($options["mock-data-dir"]);
}

function saveConfiguration($options) {
  global $CONFIG_FILE;

  $res = array();
  foreach($options as $key => $val) {
    if(is_array($val)) {
      $res[] = "[$key]";

      foreach($val as $skey => $sval) {
        $res[] = "$skey=".(is_numeric($sval) || !preg_match('/\s/', $val) ? $sval : '"'.$sval.'"');
      }
    } else {
      $res[] = "$key=".(is_numeric($val) || !preg_match('/\s/', $val) ? $val : '"'.$val.'"');
    }
  }

  if ($fp = fopen($CONFIG_FILE, 'w')) {
    fwrite($fp, implode("\r\n", $res));

    fclose($fp);
  }
}

function loadConfiguration() {
  global $CONFIG_FILE;

  return file_exists($CONFIG_FILE) ? parse_ini_file($CONFIG_FILE, false) : array();
}

function ensureTrailingSlash(&$value) {
  $value = rtrim($value, '/') . '/';
}
