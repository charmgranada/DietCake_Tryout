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

<?php if($num_rows > 0): ?>
    <p align='right' style='font-size:12px;line-height:12px;'>
        Total of 
            <b>
                <?php eh($num_rows); ?>
            </b>
        <?php echo ($num_rows==1) ? "comment" : "comments"; ?><br/>
        Page 
            <b>
                <?php eh($pagination['cur_page']); ?>
            </b> 
        of 
            <b>
                <?php eh($pagination['last_page']); ?>
            </b>
    </p>
<?php endif; ?>

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
            if($v->user_id == $_SESSION['user_id']): ?>
            <p align='right'>
                <a style='color:green;' href="<?php eh(url('comment/edit', array('thread_id' => $thread->thread_id, 'comment_id' => $v->comment_id)))?>">
                    Edit
                </a> | 
                <a style='color:red;' href="<?php eh(url('comment/delete', array('thread_id' => $thread->thread_id, 'comment_id' => $v->comment_id)))?>">
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
<hr/>
<form class="well" method="post" action="<?php eh(url('')) ?>">
    <center>
    <textarea name="body" style='width:100%;'><?php eh(Param::get('body')) ?></textarea>
    <br/>
    <input type="hidden" name="thread_id" value="<?php eh($thread->thread_id) ?>">
    <button type="submit" style='float:right;' class="btn btn-primary">Add Comment</button>
    </center>
    <a href="<?php eh(url('thread/index'))?>">
        &larr; Back to All Threads
    </a>
</form>