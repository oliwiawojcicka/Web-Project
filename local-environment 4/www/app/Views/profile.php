<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile | LSMiniSocial</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/home.css') ?>">
</head>
<body>
<nav class="navbar">
    <h1>LSMiniSocial</h1>
    <div>
        <a href="/home">Home</a>
        <a href="/post/create">Create Post</a>
        <a href="/logout">Logout</a>
    </div>
</nav>

<main class="feed-container">
    <section class="create-post-box">
        <h2>Your Profile</h2>
        <p>This page will allow users to update their username, password and profile picture.</p>

        <form>
            <label>Username</label>
            <input type="text" placeholder="Your username">

            <label>Email</label>
            <input type="email" placeholder="your.email@students.salle.url.edu" readonly>

            <label>Profile picture</label>
            <input type="file">

            <button type="submit">Save changes</button>
        </form>
    </section>
</main>
</body>
</html>
