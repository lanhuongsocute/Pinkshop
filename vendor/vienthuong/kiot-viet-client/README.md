# KiotViet Client

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

A Small PHP Client package for working with KiotViet API

## Install

Via Composer

``` bash
$ composer require vienthuong/kiot-viet-client
```

## Usage

``` php
$client = new VienThuong\KiotVietClient(<YOUR_CLIENT_ID>, <YOUR_CLIENT_SECRET>);
// Fetch access token
$token = $client->fetchAccessToken();

// Fetch list product

$productResource = new ProductResource($client);
$productList = $productResource->list();

```

Check more in Examples directory, remember to change your credentials in auth.php

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Feel free to create a new merge request into this repository.

## Security

If you discover any security related issues, please email levienthuong@gmail.com instead of using the issue tracker.

## Credits

- [vienthuong][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/vienthuong/kiot-viet-client.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/vienthuong/kiot-viet-client.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/vienthuong/kiot-viet-client.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/vienthuong/kiot-viet-client
[link-travis]: https://travis-ci.org/vienthuong/kiot-viet-client
[link-downloads]: https://packagist.org/packages/vienthuong/kiot-viet-client
[link-author]: https://github.com/vienthuong
[link-contributors]: ../../contributors
