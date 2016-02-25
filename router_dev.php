<?php
$webroots = [
                'pentaho-det-core' => 'Implementation/Core/src/main/resources/web/',
                'pentaho-det-data-explorer-module' => 'Implementation/data-explorer-module/src/main/resources/web/'
            ];

$pattern = '/^\/([^\/]+)\/6\.1\-SNAPSHOT\/(.*)$/';
$matches = null;

if (strpos($_SERVER["REQUEST_URI"], '/webjars/') === 0) {
    header('Location: http://cdn.jsdelivr.net/webjars/org.webjars' . $_SERVER["REQUEST_URI"]);
    exit;
}

if (preg_match($pattern, $_SERVER["REQUEST_URI"], $matches)) {
    if(!empty($webroots[$matches[1]])) {
        $webroot = $webroots[$matches[1]];
        $file = $matches[2];
        if(($i = strpos($file, '?')) !== false) {
            $file = substr($file, 0, $i);
        }

        if(file_exists('./' . $webroot . $file)) {
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

            header("Content-type: " . $mime . finfo_file($finfo, './' . $webroot . $file));
            finfo_close($finfo);

            readfile('./' . $webroot . $file);
            exit;
        }
    }
}

$content = @file_get_contents('http://localhost:9050' . $_SERVER["REQUEST_URI"]);
if($content !== false) {
    foreach ($http_response_header as $value) {
        if (preg_match('/^Content-Type:/i', $value)) {
            // Successful match
            header($value,false);
        }
    }

    echo $content;
    exit;
}

header("HTTP/1.0 404 Not Found");
