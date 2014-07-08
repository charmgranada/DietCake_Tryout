<?php
    class CommentController extends AppController
    {
        /**
         *VIEW ALL COMMENTS OF A THREAD
         *@throws NotFoundException
         */
        public function view()
        {       
            check_user_logged_out();     
            $comment = new Comment();
            $thread_id = Param::get('thread_id');
            $thread = Thread::get($thread_id);
            // FOR PAGINATION //
            $cur_page = Param::get('pn');
            $totalRows = Comment::getTotalCount($thread_id);
            $pagination = getPageLimit($totalRows, $cur_page);
            // PASS LIMIT TO THE COMMENT QUERY //
            $comments = $comment->getAll($thread_id, $pagination['limit']);
            $page = Param::get('page_next', 'view');
            switch ($page) {
                case 'view':
                    break;
                case 'write_end':
                    $comment->thread_id = $thread_id;
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

        /**
         *EDIT A COMMENT
         *@throws NotFoundException
         */
        public function edit(){       
            check_user_logged_out();   
            $thread_id = Param::get('thread_id');
            $thread = Thread::get($thread_id);   
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
        
        /**
         *DELETE A COMMENT
         */
        public function delete(){
            check_user_logged_out();
            $comment = new Comment;
            $comment->id = Param::get('comment_id');
            $comment->delete();  
            $thread_id = Param::get('thread_id');
            $thread = Thread::get($thread_id);
            $this->set(get_defined_vars());
        }
    }

?>