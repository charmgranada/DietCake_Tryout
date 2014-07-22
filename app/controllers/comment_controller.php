<?php
class CommentController extends AppController
{
    const COMMENTS_PER_PAGE = 5;

    /**
     *VIEW ALL COMMENTS OF A THREAD
     *@throws ValidationException
     */
    public function view()
    {       
        check_user_logged_out();     
        $thread = Thread::get(Param::get('thread_id'));
        $comment = new Comment();
        $comment->thread_id = $thread->thread_id;
        $body = Param::get('body');
        // FOR PAGINATION //
        $cur_page = Param::get('pn');
        $num_rows = $comment->getNumRows();
        $pagination = pagination($num_rows, $cur_page, self::COMMENTS_PER_PAGE, array("thread_id" => $thread->thread_id));
        // PASS LIMIT TO THE COMMENT QUERY //
        $all_comments = $comment->getAll($pagination['limit']);
        if ($body) {
            try {
                $comment->user_id = $_SESSION['user_id'];
                $comment->body = $body;
                $comment->create($thread);
                redirect("comment", "view", array("thread_id" => $thread->thread_id));
            } catch (ValidationException $e) {

            }
        }
        $this->set(get_defined_vars());
    }

    /**
     *EDIT A COMMENT
     *@throws ValidationException
     */
    public function edit()
    {       
        check_user_logged_out();   
        $thread = Thread::get(Param::get('thread_id'));   
        $comment = Comment::get(Param::get('comment_id'));
        $body = Param::get('body');
        if ($body) {
            try {
                $comment->body = Param::get('body');
                $comment->update();
                redirect("comment", "view", array("thread_id" => $thread->thread_id));
            } catch (ValidationException $e) {

            }
        }
        $this->set(get_defined_vars());
    }
    
    /**
     *DELETE A COMMENT
     */
    public function delete()
    {
        check_user_logged_out();
        $thread = Thread::get(Param::get('thread_id'));
        $comment = Comment::get(Param::get('comment_id'));
        $comment->delete(); 
        redirect("comment", "view", array("thread_id" => $thread->thread_id));
        $this->set(get_defined_vars());
    }
}