<?php // HERE ARE THE ERRORS FOR THE VALIDATION //
if ($comment->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>
        <?php  // ERRORS FOR TOO LONG CHARACTERS WITH NO SPACES AND CAN'T FIT THE SCREEN ANYMORE //
        if (!empty($comment->validation_errors['body']['format'])): ?>
            <div>
                <em>Comment</em> must have spaces to fit the screen.
            </div>        
        <?php endif ?>

        <?php  // ERRORS FOR BODY LENGTH VALIDATION //
        if (!empty($comment->validation_errors['body']['length'])): ?>        
            <div>
                <em>Comment</em> must be between
                <?php eh($comment->validation['body']['length'][1]) ?> and
                <?php eh($comment->validation['body']['length'][2]) ?> characters in length.
            </div>        
        <?php endif ?>
    </div>
<?php endif ?>

<h1>
    <?php // THIS IS THE START OF THE THREAD COMMENTS //
        $title = "Edit my comment on '" . $thread->title . "' thread";
    ?>
    Edit comment on '<?php eh($thread->title); ?>' thread
</h1>

<form class="well" method="post" action="<?php eh(url('comment/edit', array('thread_id' => $thread->id, 'comment_id' => $comment->id))) ?>">
    <center>
        <textarea name="body" style='width:100%;'><?php eh($comment->body) ?></textarea><br/>
        <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
        <input type="hidden" name="page_next" value="edit_end">
        <button type="submit" style='float:right;' class="btn btn-primary">Save Changes</button>
    </center>
    <a href="<?php eh(url('comment/view', array("thread_id" => $thread->id)))?>">
        &larr; Back to thread
    </a>
</form>