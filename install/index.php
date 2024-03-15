<!DOCTYPE html>
<html lang="en">
<head>
    <title>CustodiaPHP | Installer</title>
	<?php
	$buildIterator = new FilesystemIterator(dirname(__DIR__) . '/public/build');
	foreach ($buildIterator as $fileinfo) {
		if (str_ends_with($fileinfo->getFilename(), '.css')) {
			$path = '/build/' . $fileinfo->getFilename();
			echo "<link rel='stylesheet' type='text/css' href='{$path}'>";
		}
		if (str_ends_with($fileinfo->getFilename(), '.js')) {
			if (str_starts_with($fileinfo->getFilename(), 'vendor')) {
				continue;
			}
			$path = '/build/' . $fileinfo->getFilename();
			echo "<script defer src='{$path}'></script>";
		}
	}
	?>
</head>
<body class="dark:bg-gray-700 bg-gray-100 overflow-hidden">
    <main class="min-h-screen">
        <div class="bg-white h-1/2 w-1/2 mx-auto my-10 p-5 rounded-lg shadow-lg">
            <?php
            $requestUri = $_SERVER['REQUEST_URI'];

            switch ($requestUri) {
                case 'database':
                case '/database':
				    define("STEP", 1);
				    require_once dirname(__DIR__) . '/install/elements/progress.php';
				    require_once dirname(__DIR__) . '/install/pages/database.php';
                    break;
                case 'user':
                case '/user':
                    define("STEP", 2);
				    require_once dirname(__DIR__) . '/install/elements/progress.php';
                    require_once dirname(__DIR__) . '/install/pages/user.php';
                    break;
                default:
                    define("STEP", 0);
                    require_once dirname(__DIR__) . '/install/pages/welcome.php';
            }
            ?>
    </main>
</body>
</html>

<?php

function showProgress(int $step): string
{

}
