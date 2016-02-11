Backlog Scraper
===================

A scraper for [Backlog](http://www.backlog.jp/) via Backlog API.

## HOW TO USE

First of all, this library requires [composer](https://getcomposer.org/).

```
composer install
```

In your PHP file:

```php
// Include auto loader.
require 'vendor/autoload.php';
// Initialize with local JSON file.
$request = new Hametuha\Backlog\Request( './config.json' );
// Get all milestones
$response = $request->get( "/api/v2/projects/MYPROJECT/versions" );
// Do something.
foreach( $response as $milestone ){
   // Do stuff.
}
```

You can find this library at [Packagist](). In your composer.json, write this.

```json
"require": {
    "hametuha/backlog-scraper": "1.*",
}
```

## REFERENCE

`Hametuha\Backlog\Request` class has these methods.

### __constructor

__@params__ string|array `$config` JSON file path or array. Required parama is `apiKey` and `base`. You can get api kye [like this](http://www.backlog.jp/help/usersguide/personal-settings/userguide2378.html). `base` means your backlog's url. Normally, it will be like `https://your-account.backlog.jp`. Sample JSON will be:

```
{
  "apiKey": "yourapikeyitslenghtwillbeverylong",
  "base": "https://your-account.backlog.jp"
}
```

### get

Make GET request to Backlog. 

__@params__ string `$endpoint` Endpoint URL, for example `/api/v2/projects/project-name/version`. You can find it at [nulab Developers](http://developer.nulab-inc.com/ja/docs/backlog/aut).

__@params__ array `$params` An request parameters. On GET request, it will be converted to query params.

### post

Same as `get` but it sends POST request.

### put

Same as `get` but it sends PUT request.

### patch

Same as `get` but it sends PATCH request.

### delete

Same as `get` but it sends DELETE request.

## CHANGELOG

- 1.0.0 First release.

## LICENSE

The MIT License (MIT)
Copyright (c) 2016 Hametuha INC.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.