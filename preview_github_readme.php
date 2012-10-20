<?php
/**
 * Preview your local github Readme.md.
 * @author Akeda Bagus <admin@gedex.web.id>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

$url = 'https://api.github.com/markdown/raw';
$readme_file = null;
$template = file_get_contents("preview_github_readme.html");

/**
 * Lookup readme file by checking query args and common filename
 * on current directory
 */
function lookup_readme_file() {
    global $readme_file;

    // First check is via GET param
    if ( isset( $_GET['file'] ) && !empty( $_GET['file'] ) ) {
        $readme_file = $_GET['file'];
        if ( file_exists($readme_file) ) {
            return;
        }
    }

    $readme_candidates = array( 'README.md', 'Readme.md', 'readme.md' );
    foreach ( $readme_candidates as $readme ) {
        if ( file_exists($readme) ) {
            $readme_file = $readme;
            return;
        }
    }

    $readme_file = null;
}
lookup_readme_file();

if ( !$readme_file ) {
    trigger_error("No README.md file found!", E_USER_ERROR);
}

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($readme_file));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/plain'));

$response = curl_exec($ch);
curl_close($ch);

ob_start(function($buffer) {
    global $template, $response;

    $tpl = file_get_contents($readme_file);
    return (str_replace("%markdown%", $response, $buffer));
});

echo $template;

ob_end_flush();
