<?php if ($comment->hasError()): ?>

<div class="alert alert-block">
    <h4 class="alert-heading">Validation error!</h4>
    <?php if (!empty($comment->validation_errors['body']['format'])): ?>
    
    <div><em>Comment</em> must have spaces to fit the screen.
    </div>
    
    <?php endif ?>

    <?php if (!empty($comment->validation_errors['body']['length'])): ?>
    
    <div><em>Comment</em> must be
        between
        <?php eh($comment->validation['body']['length'][1]) ?> and
        <?php eh($comment->validation['body']['length'][2]) ?> characters in length.
    </div>
    
    <?php endif ?>
</div>
<?php endif ?>

<h1><?php 
$title = "Comments on '" . $thread->title . "' thread";
eh($thread->title); ?></h1>

<p align='right' style='font-size:12px;line-height:12px;'>
    Total of <b><?= $totalRows; ?></b> comments<br/>
    Page <b><?= $pagination['cur_page'] ?></b> of <b><?= $pagination['last_page'] ?></b>
</p>

<?php foreach ($comments as $k => $v): ?>
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
    <?php if($v->username == $_SESSION['uname']): ?>
        <p align='right'><a href="<?php eh(url('comment/delete', array('comment_id' => $v->id , 'thread_id' => $thread->id)))?>">
            <button class="btn btn-danger">Delete</button>
        </a></p>
    <?php endif; ?>
</div>
<?php endforeach ?>
<center>
<?php 
/*---------------------------------PAGINATION CONTROLS-------------------------------*/ 
foreach ($pagination as $key => $value) {
        $$key = $value;
    }    
    if($last_page != 1){
        if($cur_page > 1){
            $previous = $cur_page - 1;?>
            <a href='<?php eh(url("comment/view", array( "thread_id" =>  $id, "pn" => $previous))) ?>'>Previous</a> &nbsp; &nbsp; 
            <?php
            for($i = $cur_page - 4 ; $i < $cur_page ; $i++){
                if($i > 0){ ?>
                <a href='<?php eh(url("comment/view", array( "thread_id" =>  $id, "pn" => $i)))?>'><?= $i ?></a> &nbsp; 
                <?php
                }
            }
        }
        echo "" . $cur_page . "&nbsp ";
        for($i = $cur_page + 1 ; $i <= $last_page ; $i++){ ?>
            <a href='<?php eh(url("comment/view", array( "thread_id" =>  $id, "pn" => $i)))?>'><?= $i?></a> &nbsp; 
            <?php
            if($i >= $cur_page + 4){
                break;
            }
        }
        if($cur_page != $last_page){
            $next = $cur_page + 1;?>
            &nbsp; &nbsp; <a href='<?php eh(url("comment/view", array( "thread_id" =>  $id, "pn" => $next)))?> '>Next</a>
            <?php
        }

    }
/*---------------------------END OF PAGINATION CONTROLS-------------------------------*/ 
?>
</center>


<hr>
<form class="well" method="post" action="<?php eh(url('comment/view', array('thread_id' => $thread->id))) ?>">
    <center>
    <input type="hidden" name="username" value="<?php eh($_SESSION['uname']) ?>">
    <textarea name="body" style='width:100%;'><?php eh(Param::get('body')) ?></textarea>
    <br />
    <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" style='float:right;' class="btn btn-primary">Add Comment</button>
    </center>
<a href="<?php eh(url('thread/index'))?>">
&larr; Back to All Threads
</a>
</form>