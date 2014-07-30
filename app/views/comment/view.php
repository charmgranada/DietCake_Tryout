<?php // HERE ARE THE ERRORS FOR THE VALIDATION //
if ($comment->hasError()): ?>
    <div class='alert alert-block'>
        <h4 class='alert-heading'>Validation error!</h4>
        <?php if ($comment->validation_errors['body']['format']): ?>            
            <div>
                <em>Comment</em> has invalid amount of spaces.
            </div>        
        <?php endif ?>

        <?php if ($comment->validation_errors['body']['length']): ?>
            <div>
                <em>Comment</em> must be between
                <?php eh($comment->validation['body']['length'][1]) ?> and
                <?php eh($comment->validation['body']['length'][2]) ?> characters in length.
            </div>
        <?php endif ?>
    </div>
<?php endif ?>
<font color='#aee'>
<h1>
    <?php // THIS IS THE START OF THE THREAD COMMENTS //
        $title = "Comments on '{$thread->title}' thread";
        eh($thread->title); 
    ?>
</h1>
    <?php eh($thread->description)?>
    <form method='get' action='<?php eh(url('')) ?>' align='right' style='margin:0;'>
        <input type='hidden' name='thread_id' value='<?php eh($thread->thread_id) ?>'>
        <select name='filter_by' style='width:auto;' class='btn btn-small btn-inverse' 
        onchange='this.form.submit()'>
            <?php
                foreach ($filter_options as $filter_option) {
                    $selected = ($filter_option == $filter_by) ? 'selected' : '';
                    echo "<option {$selected}>{$filter_option}</option>";
                }
            ?>
        </select>
    </form>

<div id='autoreload'>
    <?php if($num_rows): ?>
        <p align='right' style='font-size:12px;line-height:12px;'>
            Total of 
                <b>
                    <?php eh($num_rows); ?>
                </b>
            <?php echo ($num_rows==1) ? 'comment' : 'comments'; ?><br/>
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

    <?php foreach ($all_comments as $comment): ?>
        <div class='alert alert-info'>
            <div class='meta'>
                <b><?php eh($comment->username) ?></b> said:  
                <font style='float:right;font-style:italic;font-size:9px;'>
                    Created: <?php eh(date('F d, Y h:ia', strtotime($comment->created))) ?>
                </font>
            </div>    

            <p style='padding:10px;margin-left:30px;'>
                <?php echo readable_text($comment->body) ?>
            </p>
            <p align='right' style='font-size:9px;font-style:italic;line-height:12px;'><?php 
                    if ($comment->updated != $comment->created) {
                        echo "<br/>Updated: " . date('F d, Y h:ia', strtotime($comment->updated));
                    } 
                ?>
            </p>
            <?php // FOR EDIT AND DELETE IF THE COMMENT CAME FROM THE USER LOGGED IN //
                if($comment->user_id == $_SESSION['user_id']): ?>
                <p align='right' style='line-height:5px;'>
                    <a style='color:green;' href='<?php eh(url('comment/edit', 
                    array('thread_id' => $thread->thread_id, 'comment_id' => $comment->comment_id)))?>'>
                        Edit
                    </a> | 
                    <a style='color:red;' href='<?php eh(url('comment/delete', 
                    array('thread_id' => $thread->thread_id, 'comment_id' => $comment->comment_id)))?>'>
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
</div>
</center>
<hr/>
<form class='well' method='post' action='<?php eh(url('')) ?>'>
    <center>
    <textarea name='body' style='width:100%;'><?php eh($body) ?></textarea>
    <br/>
    <input type='hidden' name='thread_id' value='<?php eh($thread->thread_id) ?>'>
    <button type='submit' style='float:right;' class='btn btn-primary'>Add Comment</button>
    </center>
    <a href='<?php eh(url('thread/index'))?>'>
        &larr; Back to All Threads
    </a>
</form>