# Coolpie Util

Selected of PHP utility classes handmade from 10+ years of experience.
- Date Time manipulation, timezone conversion
- String utility

# Requirements

PHP 7.2 and later.

# Composer

```bash
composer require TODO
```

# Example - Coolpie Date

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

use Coolpie\Util\Date;

$dt = CDate::create()->fromLiteral("2026-11-12 11:22:33");
$dt->addHour(7);
echo $dt->toLiteral();

```