<?php

namespace UCDTweet\TweetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UCDTweetTweetBundle:Default:index.html.twig', array('name' => $name));
    }

    public function loginAction()
    {
	return $this->render('UCDTweetTweetBundle:Page:login.html.twig'); 
    }
}
