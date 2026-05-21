<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'LSMiniSocial') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <?= $this->renderSection('styles') ?>
</head>
<body>

<header class="navbar">
    <div class="container navbar-content">
        <a href="/" class="logo">LSMiniSocial</a>
        <nav>
            <?php if (session()->get('isLoggedIn')): ?>
                <a href="/home" <?= uri_string() === 'home' ? 'class="active"' : '' ?>><?= lang('App.nav_home') ?></a>
                <a href="/post/create" <?= uri_string() === 'post/create' ? 'class="active"' : '' ?>><?= lang('App.nav_new_post') ?></a>
                <a href="/profile" <?= uri_string() === 'profile' ? 'class="active"' : '' ?>><?= lang('App.nav_profile') ?></a>
                <a href="/sign-out"><?= lang('App.nav_sign_out') ?></a>
            <?php else: ?>
                <a href="/sign-in" <?= uri_string() === 'sign-in' ? 'class="active"' : '' ?>><?= lang('App.nav_sign_in') ?></a>
                <a href="/sign-up" <?= uri_string() === 'sign-up' ? 'class="active"' : '' ?>><?= lang('App.nav_sign_up') ?></a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main>
    <?= $this->renderSection('content') ?>
</main>

<?= $this->renderSection('scripts') ?>

</body>
</html>
