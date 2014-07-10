<?php // HERE ARE THE ERRORS FOR THE VALIDATION //
if ($comment->hasError()): ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>
        <?php if (!empty($comment->validation_errors['body']['format'])): ?>            
            <div>
                <em>Comment</em> must have spaces to fit the screen.
            </div>        
        <?php endif ?>

        <?php if (!empty($comment->validation_errors['body']['length'])): ?>        
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
        $title = "Comments on '" . $thread->title . "' thread";
        eh($thread->title); 
    ?>
</h1>

<p align='right' style='font-size:12px;line-height:12px;'>
    Total of 
        <b>
            <?php eh($num_rows); ?>
        </b>
    comments<br/>
    Page 
        <b>
            <?php eh($pagination['cur_page']); ?>
        </b> 
    of 
        <b>
            <?php eh($pagination['last_page']); ?>
        </b>
</p>

<?php foreach ($all_comments as $k => $v): ?>
    <div class="alert alert-info">
        <div class="meta">
            <b><?php eh($v->username) ?></b> said:  
            <i style='font-size:12px;float:right;'>
                <?php eh(date_format(new DateTime($v->created),"F d, Y h:ia")) ?>
            </i>
        </div>    

        <p style='padding:10px;margin-left:30px;'>
            <?php echo readable_text($v->body) ?>
        </p>

        <?php // FOR EDIT AND DELETE IF THE COMMENT CAME FROM THE USER LOGGED IN //
            if($v->username == $_SESSION['uname']): ?>
            <p align='right'>
                <a style='color:green;' href="<?php eh(url('comment/edit', array('thread_id' => $thread->id, 'comment_id' => $v->id)))?>">
                    Edit
                </a> | 
                <a style='color:red;' href="<?php eh(url('comment/delete', array('thread_id' => $thread->id, 'comment_id' => $v->id)))?>">
                    Delete
                </a>
            </p>
        <?php endif; ?>
    </div>
<?php endforeach ?>

<center>
<?php 
// PAGINATION CONTROLS // 
echo $pagination['controls'];
// END OF PAGINATION CONTROLS // 
?>
</center>
<?php // THE EDIT AND DELETE CONTROLS FOR THE THREAD, ACCESSED ONLY BY THE USER WHO CREATED IT
if($thread->user_created == $_SESSION['user_id']): ?>
    <p align='left'>
        <a href="<?php eh(url('thread/edit', array('thread_id' => $thread->id)))?>">
            <button class="btn btn-success">Edit Title</button>
        </a>
        <a href="<?php eh(url('thread/delete', array('thread_id' => $thread->id)))?>">
            <button class="btn btn-danger">
                Delete Thread
            </button>
        </a>
    </p>
<?php endif; ?>
<hr/>
<form class="well" method="post" action="<?php eh(url('comment/view', array('thread_id' => $thread->id))) ?>">
    <center>
    <input type="hidden" name="username" value="<?php eh($_SESSION['uname']) ?>">
    <textarea name="body" style='width:100%;'><?php eh(Param::get('body')) ?></textarea>
    <br/>
    <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" style='float:right;' class="btn btn-primary">Add Comment</button>
    </center>
    <a href="<?php eh(url('thread/index'))?>">
        &larr; Back to All Threads
    </a>
</form>