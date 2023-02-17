## Introduction

This is an Unofficial PHP Api Client for [TrackSim](https://www.tracksim.app/).
This client will allow you to interact with the TrackSim API in a simple and easy way.

## Installation

You can install the package via composer:

```bash
composer require huckinb/tracksim-php-client
```

## Usage

### Authentication

```dotenv
TRACKSIM_API_KEY=your-api-key
```

### Example

Add a Driver to Company.
```php
$client = new Client();

$client->addDriver($steam_id);
```


## Credits

- [HuckinB](https://github.com/HuckinB)
- [TrackSim Team](https://tracksim.app/)


## License

The GNU General Public License v3.0. Please see [License File](LICENCE.md) for more information.