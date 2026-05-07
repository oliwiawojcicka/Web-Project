<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LSMiniSocial' ?></title>

    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="navbar">
    <div class="container navbar-content">
        <a href="/" class="logo">LSMiniSocial</a>

        <nav>
            <a href="/sign-in">Sign In</a>
            <a href="/sign-up">Sign Up</a>
        </nav>
    </div>
</header>

<main>
    <?= $this->renderSection('content') ?>
</main>

</body>
</html>