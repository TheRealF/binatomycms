<?php

//Controller che gestisce e renderizza le view per il servizio di pagination
class PaginationController extends Controller {

  public function main ($params) {
    switch ($params[0]) {  //Seleziono la pagination richiesta a seconda dell'URL
      case "homepage":
      $this->renderBlogs($_GET['pageno']);
      break;
      case "blog":
      $this->renderPosts($_GET['pageno'], urldecode($_GET['blogname']));
      break;
      case "post":
      $this->renderComments($_GET['pageno'], urldecode($_GET['blogname_post']));
      break;
      default:
      exit();
    }
  }

  //Rendering dei blog
  public function renderBlogs($pagenum){
    $number = 9;
    $blogManager = new BlogManager;

    $offset = ($pagenum - 1) * $number;
    $blogs = $blogManager->getBlogsLimited($offset, $number);
    for ($i=0; $i < count($blogs); $i++) {
      $blogs[$i]['url'] = urlencode($blogs[$i]["name"]);
      $blogs[$i]['imageurl'] = PicUtils::getBgPicPath("blog", $blogs[$i]["name"]);
    }
    $this->data['blogs'] = array_reverse($blogs);
    $this->data['noLayout'] = true;
    $this->view = 'blogs';
  }

  //Rendering dei post
  public function renderPosts($pagenum, $blogName){
    $blogManager = new BlogManager;
    $postManager = new PostManager;
    $number = 3;
    $offset = ($pagenum - 1) * $number;
    $posts = $postManager->getPosts($blogName, $offset, $number);
    for ($i=0; $i < count($posts); $i++) {
      $posts[$i]['url'] = urlencode($posts[$i]["title"] . $posts[$i]["timestamp"]);
    }
    $this->data['posts'] = $posts;
    $this->data['noLayout'] = true;
    $this->data['layout'] = json_decode($blogManager->getLayout($blogName));
    $this->view = 'posts';
  }

  //Rendering dei commenti
  public function renderComments($pagenum, $blogName){
    $number = 3;
    $commentManager = new CommentManager;
    $userManager = new UserManager;
    $user = $userManager->getUser();
    $offset = ($pagenum - 1) * $number;
    $comments = $commentManager->getComments($blogName, $_SESSION["timestampPost"], $offset, $number);
    $this->data['currentUser'] = $user["username"];
    $this->data['comments'] = array_reverse($comments);
    $this->data['noLayout'] = true;
    $this->view = 'comments';
  }
}
?>
