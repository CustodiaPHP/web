<?php

if (is_dir(dirname(__DIR__) . '/install')) {
	return require_once dirname(__DIR__) . '/install/index.php';
}

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
