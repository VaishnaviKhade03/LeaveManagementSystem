<?php include 'templates/header.php'; ?>

<section class="hero-banner d-flex align-items-center justify-content-center">
    <div class="container-fluid text-center ">
        <h1>Leave Management System</h1>
        <p>College Project</p>
        <button class="btn btn-primary login">
            <a href="login.php">Login</a>
        </button>
    </div>
</section>

<?php include 'templates/footer.php'; ?>

<style>
    *{
    padding: 0;
    margin: 0;
}

section.hero-banner{
    background-image: url(./assets/images/backimg.avif);
    height: 100vh;
    background-size: cover;
}
section.hero-banner div{
    color: #fff;
    text-shadow: 2px 2px black;
}
section.hero-banner div button{
    font-size: 20px;
}
button.login a{
    color: #fff;
    text-decoration: none;
}


</style>