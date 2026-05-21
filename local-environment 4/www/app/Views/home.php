<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="feed-container container">

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <section class="create-post-box">
        <h2><?= lang('App.create_post_title') ?></h2>
        <form action="/posts" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <label for="home-content"><?= lang('App.label_post_content') ?></label>
            <textarea id="home-content" name="content" placeholder="..."><?= old('content') ?></textarea>
            <label for="home-image"><?= lang('App.label_image') ?></label>
            <input id="home-image" type="file" name="image" accept="image/*">
            <div class="post-actions">
                <button type="button" class="btn btn-secondary" id="ai-improve-btn"><?= lang('App.btn_improve_ai') ?></button>
                <button type="submit" class="btn btn-primary"><?= lang('App.btn_publish') ?></button>
            </div>
            <div id="ai-suggestion-box" style="display:none;" class="ai-suggestion">
                <p><strong><?= lang('App.ai_suggestion') ?></strong></p>
                <p id="ai-suggestion-text"></p>
                <div class="post-actions">
                    <button type="button" class="btn btn-primary" id="ai-accept-btn"><?= lang('App.btn_accept') ?></button>
                    <button type="button" class="btn btn-secondary" id="ai-reject-btn"><?= lang('App.btn_reject') ?></button>
                </div>
            </div>
        </form>
    </section>

    <section class="feed-heading">
        <h2><?= lang('App.feed_title') ?></h2>
    </section>

    <section class="posts">
        <?php if (! empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="post-card" data-post-id="<?= esc($post['id']) ?>">
                    <div class="post-header">
                        <?php if (! empty($post['profile_pic']) && $post['profile_pic'] !== 'default.png'): ?>
                            <img src="<?= base_url($post['profile_pic']) ?>" alt="avatar" class="avatar-img">
                        <?php else: ?>
                            <div class="avatar"><?= esc(strtoupper(substr($post['username'] ?? 'U', 0, 1))) ?></div>
                        <?php endif; ?>
                        <div>
                            <strong><?= esc($post['username'] ?? 'Unknown') ?></strong>
                            <small><?= esc($post['created_at']) ?></small>
                        </div>
                    </div>

                    <p class="post-content"><?= esc($post['content']) ?></p>

                    <?php if (! empty($post['image'])): ?>
                        <img class="post-image" src="<?= base_url($post['image']) ?>" alt="Post image">
                    <?php endif; ?>

                    <p class="post-stats">
                        <span class="likes-count"><?= esc($post['likes_count']) ?></span> <?= lang('App.likes') ?> ·
                        <span class="comments-count"><?= esc($post['comments_count']) ?></span> <?= lang('App.comments') ?>
                    </p>

                    <div class="post-actions">
                        <button type="button"
                            class="btn btn-secondary like-btn <?= $post['liked_by_user'] ? 'liked' : '' ?>"
                            data-post-id="<?= esc($post['id']) ?>"
                            data-liked="<?= $post['liked_by_user'] ? '1' : '0' ?>"
                            data-label-like="<?= lang('App.btn_like') ?>"
                            data-label-unlike="<?= lang('App.btn_unlike') ?>">
                            <?= $post['liked_by_user'] ? lang('App.btn_unlike') : lang('App.btn_like') ?>
                        </button>
                        <button type="button" class="btn btn-secondary toggle-comments-btn" data-post-id="<?= esc($post['id']) ?>">
                            <?= lang('App.btn_comments') ?>
                        </button>
                        <?php if ((int) $post['user_id'] === (int) session()->get('user_id')): ?>
                            <a href="/post/edit/<?= esc($post['id']) ?>" class="btn btn-secondary"><?= lang('App.btn_edit') ?></a>
                            <button type="button" class="btn btn-danger delete-post-btn" data-post-id="<?= esc($post['id']) ?>"><?= lang('App.btn_delete') ?></button>
                        <?php endif; ?>
                    </div>

                    <div class="comments-box" id="comments-box-<?= esc($post['id']) ?>" style="display:none;">
                        <div class="comments-list" id="comments-list-<?= esc($post['id']) ?>">
                            <?php foreach ($post['comments'] as $comment): ?>
                                <div class="comment" data-comment-id="<?= esc($comment['id']) ?>">
                                    <strong><?= esc($comment['username'] ?? 'User') ?></strong>
                                    <p><?= esc($comment['content']) ?></p>
                                    <small><?= esc($comment['created_at']) ?></small>
                                    <?php if ((int) $comment['user_id'] === (int) session()->get('user_id')): ?>
                                        <button type="button" class="btn-link delete-comment-btn"
                                            data-comment-id="<?= esc($comment['id']) ?>"
                                            data-post-id="<?= esc($post['id']) ?>"><?= lang('App.btn_delete') ?></button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($post['comments'])): ?>
                                <p class="empty-comments" id="no-comments-<?= esc($post['id']) ?>"><?= lang('App.no_comments') ?></p>
                            <?php endif; ?>
                        </div>
                        <form class="comment-form" data-post-id="<?= esc($post['id']) ?>">
                            <?= csrf_field() ?>
                            <input type="text" name="content" placeholder="<?= lang('App.comment_placeholder') ?>" required>
                            <button type="submit" class="btn btn-primary"><?= lang('App.btn_send') ?></button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <article class="post-card">
                <h2><?= lang('App.no_posts') ?></h2>
                <p class="empty-comments"><?= lang('App.no_posts_desc') ?></p>
                <a href="/post/create" class="btn btn-primary"><?= lang('App.btn_create_first') ?></a>
            </article>
        <?php endif; ?>
    </section>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const CSRF_NAME = '<?= csrf_token() ?>';
    const CSRF_HASH = '<?= csrf_hash() ?>';
</script>
<script src="<?= base_url('assets/js/home.js') ?>"></script>
<?= $this->endSection() ?>
