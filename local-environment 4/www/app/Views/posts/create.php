<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="feed-container container">

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <section class="create-post-box">
        <h2><?= lang('App.creategi_title') ?></h2>
        <p><?= lang('App.create_subtitle') ?></p>

        <form action="/posts" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <label for="content"><?= lang('App.label_post_content') ?></label>
            <textarea id="content" name="content" required placeholder="..."><?= old('content') ?></textarea>
            <label for="image"><?= lang('App.label_image') ?></label>
            <input id="image" type="file" name="image" accept="image/*">
            <div class="post-actions">
                <button type="button" class="btn btn-secondary" id="ai-improve-btn"><?= lang('App.btn_improve_ai') ?></button>
                <button type="submit" class="btn btn-primary"><?= lang('App.btn_publish_post') ?></button>
            </div>
        </form>

        <div id="ai-suggestion-box" style="display:none;" class="ai-suggestion">
            <p><strong><?= lang('App.ai_suggestion') ?></strong></p>
            <p id="ai-suggestion-text"></p>
            <div class="post-actions">
                <button type="button" class="btn btn-primary" id="ai-accept-btn"><?= lang('App.btn_accept') ?></button>
                <button type="button" class="btn btn-secondary" id="ai-reject-btn"><?= lang('App.btn_reject') ?></button>
            </div>
        </div>
    </section>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CSRF_NAME = '<?= csrf_token() ?>';
    const CSRF_HASH = '<?= csrf_hash() ?>';
</script>
<script src="<?= base_url('assets/js/ai-improve.js') ?>"></script>
<?= $this->endSection() ?>
