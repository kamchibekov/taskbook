<?php if (!empty($tasks)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"><a href="?sort[name]=<?= $sort['user_name'] ?>">Name</a></th>
                <th scope="col"><a href="?sort[name]=<?= $sort['email'] ?>">Email</a></th>
                <th scope="col">Task</th>
                <th scope="col"><a href="?sort[name]=<?= $sort['status'] ?>">Status</a></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task) : ?>
                <tr>
                    <th scope="row"><?= $task->id; ?></th>
                    <td><?= htmlspecialchars($task->user_name) ?></td>
                    <td><?= htmlspecialchars($task->email) ?></td>
                    <td><?= htmlspecialchars($task->task_text) ?></td>
                    <td>
                        <?php if ($task->status) : ?>
                            <span class="badge rounded-pill bg-success">Done &#10003;</span>
                        <?php else : ?>
                            <span class="badge rounded-pill bg-light text-dark">In progress</span>
                        <?php endif; ?>
                        <?php if ($task->content_changed) : ?>
                            <span class="badge rounded-pill bg-info">Changed by Admin</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<br>
<?php if ($count > $limit) : ?>
    <nav class="d-flex justify-content-center">
        <ul class="pagination">
            <?php if ($page > 1) : ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= ceil($count / $limit); $i++) : ?>
                <li class="page-item <?= $page == $i ? 'active' : $i ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < ceil($count / $limit)) : ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>

<div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/task/create" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_name" class="form-label">Full name</label>
                        <input type="text" name="user_name" class="form-control" id="user_name" aria-describedby="user_name_help" placeholder="Ex: Spider Man" required>
                        <div id="user_name_help" class="form-text">Please enter your full name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="user_email" class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" id="user_email" aria-describedby="user_email_help" placeholder="Ex: nowayhome@queens.ny" required>
                        <div id="user_email_help" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="task_text" class="form-label">Task text</label>
                        <textarea name="task_text" class="form-control" id="task_text" rows="3" aria-describedby="task_text_help" required placeholder="Ex: Be a friendly neighbor."></textarea>
                        <div id="task_text_help" class="form-text">Please enter the task description above.</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>