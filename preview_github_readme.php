<?php
#!/usr/bin/env php
/**
 * Preview your local github Readme.md.
 * @author Akeda Bagus <admin@gedex.web.id>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

$url = 'https://api.github.com/markdown/raw';
$readme_file = null;
$base_dir = dirname( __FILE__ );
$template = file_get_contents( "{$base_dir}/preview_github_readme.html" );
$stylesheet = file_get_contents( "{$base_dir}/preview_github_readme.css" );

/**
 * Lookup readme file by checking query args if in browser or
 * argv if in cli. Lastly it checks common readme filenames
 * in current directory.
 */
function lookup_readme_file() {
    global $readme_file;

    if ( 'cli' === php_sapi_name() && isset( $argv[1] ) ) {
        if ( file_exists( $argv[1] ) ) {
            $readme_file = $argv[1];
            return;
        }
    }
    else if ( isset( $_GET['file'] ) && !empty( $_GET['file'] ) ) {
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

function cli_usage() {
    global $argv;


    echo "Usage: preview_github_readme [README.md file]\n";
    exit(1);
    die;
}

if ( !$readme_file ) {
    if ( 'cli' === php_sapi_name() )
        cli_usage();

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
    global $template, $response, $readme_file, $stylesheet;

    $tpl = file_get_contents($readme_file);

    $buffer = str_replace("%stylesheet%", "<style>{$stylesheet}</style>", $buffer);

    return (str_replace("%markdown%", $response, $buffer));
});

echo $template;

ob_end_flush();
