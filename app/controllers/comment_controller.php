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
            $thread = Thread::get(Param::get('thread_id'));
            $comment = new Comment();
            $comment->thread_id = $thread->id;
            // FOR PAGINATION //
            $cur_page = Param::get('pn');
            $num_rows = $comment->getNumRows();
            $pagination = getPageLimit($num_rows, $cur_page);
            // PASS LIMIT TO THE COMMENT QUERY //
            $all_comments = $comment->getAll($pagination['limit']);
            $page = Param::get('page_next', 'view');
            switch ($page) {
                case 'view':
                    break;
                case 'write_end':
                    $comment->username = Param::get('username');
                    $comment->body = Param::get('body');
                    try {
                        $comment->createNew($thread);
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
        public function edit()
        {       
            check_user_logged_out();   
            $thread = Thread::get(Param::get('thread_id'));   
            $comment = Comment::get(Param::get('comment_id'));
            $page = Param::get('page_next', 'edit');
            switch ($page) {
                case 'edit':
                    break;
                case 'edit_end':
                    $comment->body = Param::get('body');
                    try {
                        $comment->setBody();
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
        public function delete()
        {
            check_user_logged_out();
            $thread = Thread::get(Param::get('thread_id'));
            $comment = Comment::get(Param::get('comment_id'));
            $comment->delete();  
            $this->set(get_defined_vars());
        }
    }

?>