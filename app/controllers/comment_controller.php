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
            $comments = $comment->getAll($id, $pagination['limit']);
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
        public function edit(){       
            check_user_logged_out();   
            $id = Param::get('thread_id');
            $thread = Thread::get($id);   
            $comment = new Comment();
            $comment->id = Param::get('comment_id');
            $comment->getComment();
            $page = Param::get('page_next', 'edit');
            switch ($page) {
                case 'edit':
                    break;
                case 'edit_end':
                    $comment->body = Param::get('body');
                    try {
                        $comment->save();
                    } catch (ValidationException $e) {
                        $page = 'edit';
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
            $id = Param::get('thread_id');
            $thread = Thread::get($id);
            $thread_title = $thread->title;
            Comment::delete($comment_id);  
            $this->set(get_defined_vars());
        }
    }

?>