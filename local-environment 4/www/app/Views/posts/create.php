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
        <a href="/profile">Profile</a>
        <a href="/logout">Logout</a>
    </div>
</nav>

<main class="feed-container">
    <section class="create-post-box">
        <h2>Create a new post</h2>

        <form action="#" method="post" enctype="multipart/form-data">
            <label for="content">Post content</label>
            <textarea id="content" name="content" placeholder="What are you thinking about?"></textarea>

            <label for="image">Image</label>
            <input id="image" type="file" name="image">

            <div>
                <button type="button">Improve with AI</button>
                <button type="submit">Publish</button>
            </div>
        </form>
    </section>
</main>
</body>
</html>
