<?php

require __DIR__ . "/../prod/ua_parse.php";

function time_elapsed($comment)
{
    static $last = null;
    $now = microtime(true);
    if ($last != null) {
        printf("$comment: %.3lf seconds\n", $now - $last);
    }
    $last = $now;
}

define('DEBUG', false);

function debugmsg($msg)
{
    if (DEBUG) {
        echo $msg;
    }
}

function test_uap($test)
{
    foreach ($test as $ua) {
        $result = ua_parse($ua);
        debugmsg("$ua\n" . $result['ua'] . '@' . $result['os'] . '@' . $result['device'] . "\n");
    }
}

function test_browscap($test)
{
    foreach ($test as $ua) {
        $browscap = get_browser($ua, true);
        debugmsg("$ua\n" . $browscap['parent'] . " @ " . $browscap['platform'] . "\n");
    }
}

function test_uap_php($test)
{
    require_once '../uap-php/vendor/autoload.php';

    $parser = \UAParser\Parser::create();

    foreach ($test as $ua) {
        $result = $parser->parse($ua);
        debugmsg("$ua\n" . $result->ua->toString() . '@' . $result->os->toString() . '@' . $result->device->toString() . "\n");
    }
}

function main($test_name, $ua_filename)
{
    if (empty($test_name)) {
        echo "Usage: php ua_test.php <test_name> [<ua_filename>]\n";
        echo "  test_name: (test_uap|test_uap_php|test_browscap)\n";
        exit(1);
    }
    if ($ua_filename) {
        $test_agents = file($ua_filename);
    } else {
        $test_agents = [
            "Mozilla/5.0 (Linux; U; en-us; KFAPWI Build/JDQ39) AppleWebKit/535.19 (KHTML, like Gecko) Silk/3.13 Safari/535.19 Silk-Accelerated=true",
            "Mozilla/5.0 (iPad; CPU OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53",
            "Mozilla/5.0 (iPad; CPU OS 7_0_4 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11B554a Safari/9537.53",
            "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_2_1 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148 Safari/6533.18.5",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53",
            "Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4",
            "Mozilla/5.0 (PlayBook; U; RIM Tablet OS 2.1.0; en-US) AppleWebKit/536.2+ (KHTML like Gecko) Version/7.2.1.0 Safari/536.2+",
            "Mozilla/5.0 (BB10; Touch) AppleWebKit/537.10+ (KHTML, like Gecko) Version/10.0.9.2372 Mobile Safari/537.10+",
            "Mozilla/5.0 (Linux; Android 4.3; Nexus 10 Build/JSS15Q) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2307.2 Safari/537.36",
            "Mozilla/5.0 (Linux; U; Android 4.3; en-us; SM-N900T Build/JSS15J) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30",
            "Mozilla/5.0 (Linux; Android 5.1.1; Nexus 6 Build/LYZ28E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.20 Mobile Safari/537.36",
            "Mozilla/5.0 (PlayBook; U; RIM Tablet OS 2.1.0; en-US) AppleWebKit/536.2+ (KHTML like Gecko) Version/7.2.1.0 Safari/536.2+",
        ];
    }
    time_elapsed('');
    call_user_func($test_name, $test_agents);
    time_elapsed($test_name);
}

main($argv[1], $argv[2]);

