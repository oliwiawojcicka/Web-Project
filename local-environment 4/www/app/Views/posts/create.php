<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post | LSMiniSocial</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/home.css') ?>">
</head>
<body>
<nav class="navbar">
    <h1>LSMiniSocial</h1>
    <div>
        <a href="/home">Home</a>
        <a href="/post/create" class="active">Create Post</a>
        <a href="/profile">Profile</a>
        <a href="/logout">Logout</a>
    </div>
</nav>

<main class="feed-container">
    <section class="create-post-box">
        <h2>Create a new post</h2>
        <p>Share an update with the La Salle community. You can also improve your text with AI before publishing.</p>

        <form action="#" method="post" enctype="multipart/form-data">
            <label for="content">Post content</label>
            <textarea id="content" name="content" placeholder="What would you like to share?"></textarea>

            <label for="image">Optional image</label>
            <input id="image" type="file" name="image">

            <div class="post-actions">
                <button type="button">Improve with AI</button>
                <button type="submit">Publish post</button>
            </div>
        </form>
    </section>
</main>
</body>
</html>
