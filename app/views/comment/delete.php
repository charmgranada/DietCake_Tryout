<h2>
    <?php
        $title = "Successfully deleted Comment on '" . $thread->title . "' thread";
        eh($thread->title); 
    ?>
</h2>
<p class="alert alert-success">
    You successfully deleted this comment.
</p>
<a href="<?php eh(url('comment/view', array('thread_id' => $thread->id))) ?>">
    &larr; Back to thread
</a>