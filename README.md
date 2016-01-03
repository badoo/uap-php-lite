# uap-php-lite
PHP implementation of ua-parser without runtime dependencies.

About 1000 times faster that [PHP get_browser](http://php.net/manual/en/function.get-browser.php) implementation and more accurate (at least for mobile browsers).

## Production dependencies
- None

## Development dependencies
- [uap-core](https://github.com/ua-parser/uap-core.git) - The regex file necessary to build language ports of Browserscope's user agent parser.
- [spyc](https://github.com/mustangostang/spyc) - A simple YAML loader/dumper class for PHP

## Usage instructions

```php
~> php -a
php > require "./prod/ua_parse.php";
php > $_SERVER['HTTP_USER_AGENT'] = "Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4";
php > $result = ua_parse($_SERVER['HTTP_USER_AGENT']);
php > print_r($result);
Array
(
    [ua] => Mobile Safari,8,0
    [os] => iOS,8,0
    [device] => iPhone
)
```

## Update/debug

- Use `git clone --recursive` to clone with submodules.
- Use `./dev/update_regexes.sh` to update uap-core regex file and generate new php snapshot of `regexes.yaml`.
- Use `php ./dev/ua_test.php user_agents_list.txt` to run test against list of user agents you want.

## Reasoning for own implementation

We needed a simple solution with minimal dependincies, as fast as possible.

Alternatives:
- [Optimization discussion](https://github.com/tobie/ua-parser/issues/306)
- [Nginx module](https://github.com/MySiteApp/nginx-ua-parse-module)
corrupts memory, provide too simplified results (no versions just groups and families)
- [php implementation](https://github.com/ua-parser/uap-php)
depends on composer, symphony and others
- [lua implementation](https://github.com/tobie/ua-parser/compare/master...sunblock:master)
again too simplified version returning groups and families instead browsers and devices, author says it is terrebly slow

### Comparison with [uap-php]()

Preformance gain is insignificant:
```sh
dpp@dpp-mac ~/src/uap-php-lite> time php dev/ua_test.php test_uap user_agents_uniq.txt
test_uap: 7.020 seconds
php dev/ua_test.php test_uap user_agents_uniq.txt  6.45s user 0.06s system 92% cpu 7.049 total
dpp@dpp-mac ~/src/uap-php-lite> time php dev/ua_test.php test_uap_php user_agents_uniq.txt
test_uap_php: 8.457 seconds
php dev/ua_test.php test_uap_php user_agents_uniq.txt  7.72s user 0.07s system 91% cpu 8.491 total
dpp@dpp-mac ~/src/uap-php-lite> wc -l user_agents_uniq.txt
3295 user_agents_uniq.txt
dpp@dpp-mac ~/src/uap-php-lite>
```

