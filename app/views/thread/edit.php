<?php $title = "Edit '". $thread_title ."' Thread" ?>
<h1>Edit '<?= $thread_title; ?>' thread</h1>
<?php // Checks for validation errors in the inputs passed
if ($thread->hasError() || $comment->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>        
        <?php // ERRORS FOR TITLE LENGTH VALIDATION //
        if (!empty($thread->validation_errors['title']['length'])): ?>
            <div>
                <em>Title</em> must be between
                <?php eh($thread->validation['title']['length'][1]) ?> and
                <?php eh($thread->validation['title']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>

        <?php // ERRORS FOR BODY LENGTH VALIDATION //
        if (!empty($comment->validation_errors['body']['length'])): ?>
            <div>
                <em>Comment</em> must be between
                <?php eh($comment->validation['body']['length'][1]) ?> and
                <?php eh($comment->validation['body']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>

<form class="well" method="post" action="<?php eh(url('')) ?>">
    <label>Title</label>
    <input type="text" class="span2" name="title" value="<?= $thread->title; ?>">
    <input type="hidden" name="username" value="<?php eh($_SESSION['uname']) ?>">

    <label>Comment</label>
    <textarea name="body"><?php eh(Param::get('body')) ?></textarea>
    <br />

    <input type="hidden" name="page_next" value="edit_end">
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>
