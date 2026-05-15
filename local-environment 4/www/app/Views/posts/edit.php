<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post | LSMiniSocial</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/home.css') ?>">
</head>
<body>
<nav class="navbar">
    <h1>LSMiniSocial</h1>
    <div>
        <a href="/home" class="<?= uri_string() === 'home' ? 'active' : '' ?>">Home</a>
        <a href="/post/create" class="<?= uri_string() === 'post/create' ? 'active' : '' ?>">Create Post</a>
        <a href="/profile" class="<?= uri_string() === 'profile' ? 'active' : '' ?>">Profile</a>
        <a href="/logout">Logout</a>
    </div>
</nav>

<main class="feed-container">
    <section class="create-post-box">
        <h2>Edit post</h2>
        <p>Update the content of your post. Only the owner of the post will be allowed to edit it.</p>

        <form action="#" method="post" enctype="multipart/form-data">
            <label for="content">Post content</label>
            <textarea id="content" name="content" required>This is the current content of post #<?= esc($postId) ?>.</textarea>

            <label for="image">Change image optional</label>
            <input id="image" type="file" name="image">

            <div class="ai-suggestion-box">
                <h3>AI suggestion</h3>
                <p>Your improved post suggestion will appear here.</p>

                <div class="post-actions">
                    <button type="button" class="secondary-button">Accept suggestion</button>
                    <button type="button" class="secondary-button">Reject suggestion</button>
                </div>
            </div>

            <div class="post-actions">
                <button type="button" class="secondary-button">Improve with AI</button>
                <button type="submit">Save changes</button>
                <a href="/home" class="button-link secondary-button">Cancel</a>
            </div>
        </form>
    </section>
</main>
</body>
</html>
