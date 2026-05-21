<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<section class="hero">
    <div class="hero-content">
        <h1><?= lang('App.landing_title') ?></h1>
        <p><?= lang('App.landing_subtitle') ?></p>
        <div class="hero-buttons">
            <a href="/sign-in" class="btn btn-primary"><?= lang('App.landing_sign_in') ?></a>
            <a href="/sign-up" class="btn btn-secondary"><?= lang('App.landing_register') ?></a>
        </div>
    </div>
</section>

<section class="features container">
    <article class="feature-card">
        <h2><?= lang('App.feature_posts_title') ?></h2>
        <p><?= lang('App.feature_posts_desc') ?></p>
    </article>
    <article class="feature-card">
        <h2><?= lang('App.feature_interact_title') ?></h2>
        <p><?= lang('App.feature_interact_desc') ?></p>
    </article>
    <article class="feature-card">
        <h2><?= lang('App.feature_ai_title') ?></h2>
        <p><?= lang('App.feature_ai_desc') ?></p>
    </article>
</section>

<?= $this->endSection() ?>
