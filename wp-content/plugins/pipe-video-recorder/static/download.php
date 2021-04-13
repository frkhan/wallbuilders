<?php
if (isset($_GET['key']) && $_GET['key'] === sha1('addpipe')) {
    if (strpos($_GET['video'], 'addpipevideos.s3.amazonaws.com') > 1 || strpos($_GET['video'], 'eu1-addpipe.s3.eu-central-1.amazonaws.com') > 1 || strpos($_GET['video'], 'us1-addpipe.s3-us-west-1.amazonaws.com') > 1) {
        $videoUrl = filter_var($_GET['video'], FILTER_SANITIZE_URL);
        $fileName = explode('/', $videoUrl);
        $fileName = 'addpipe_' . $fileName[4];
        header('Pragma: public');    // required
        header('Expires: 0');        // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: video/mp4');
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');
        readfile($videoUrl);        // push it out
        exit();
    } else {
        header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden");
        http_response_code(403);
        die('403 Forbidden');
    }
} else {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    http_response_code(404);
    die('404 Not found');
}
/*
 * File used to download videos (mp4) from the AddPipe CDN
 */