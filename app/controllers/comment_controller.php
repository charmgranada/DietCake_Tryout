<?php
	class CommentController extends AppController{

	    public function view()
        {       
            check_user_logged_out(); 	
	    	$comment = new Comment();
	    	$id = Param::get('thread_id');
            $thread = Thread::get($id);
            // FOR PAGINATION //
            $rows_seen = 5;
            $pn = Param::get('pn');
            $totalRows = Comment::getTotalCount($id);
            $pagination = getPageLimit($totalRows, $rows_seen, $pn);
            // PASS LIMIT TO THE COMMENT QUERY //
            $comments = $comment->getComments($id, $pagination['limit']);
            $page = Param::get('page_next', 'view');
            switch ($page) {
                case 'view':
                    break;
                case 'write_end':
                    $comment->id = $id;
                    $comment->username = Param::get('username');
                    $comment->body = Param::get('body');
                    try {
                        $comment->write($thread);
                    } catch (ValidationException $e) {
                        $page = 'view';
                    }
                    break;
                default:
                    throw new NotFoundException("{$page} is not found");
                    break;
            }
            $this->set(get_defined_vars());
            $this->render($page);
        }
        // DELETES A COMMENT //
        public function delete(){
            check_user_logged_out();
        	$comment_id = Param::get('comment_id');
        	$thread_id = Param::get('thread_id');
        	Comment::delete($comment_id);
        	redirect('comment','view', array("thread_id" => $thread_id));
        }
	}

?>