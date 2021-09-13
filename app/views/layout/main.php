<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <title>Taskbook</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="/">Taskbook</a>
                <div class="d-flex">
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#createTaskModal">Create task</button>
                    &emsp;
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userLoginModal">Log In</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <br>
    <div class="container">
        <div class="row">
            <?php if (!empty($flashMessages)) : ?>
                <?php foreach ($flashMessages as $flashMessage) : ?>
                    <div class="alert alert-<?= $flashMessage['key'] ?> alert-dismissible fade show" role="alert">
                        <?= $flashMessage['value'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?= $content ?>
        </div>
    </div>
    <div class="modal fade" id="userLoginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Log in</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/auth/login" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="auth_username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="auth_username" aria-describedby="auth_username_help" placeholder="Ex: superman" required>
                            <div id="auth_username_help" class="form-text">Please enter your username.</div>
                        </div>
                        <div class="mb-3">
                            <label for="auth_password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="auth_password" aria-describedby="auth_password_help" required>
                            <div id="auth_password_help" class="form-text">Please enter your password.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/public/js/app.js"></script>
</body>

</html>