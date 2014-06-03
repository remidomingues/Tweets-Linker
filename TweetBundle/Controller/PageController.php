<?php

namespace UCDTweet\TweetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;

class PageController extends Controller
{
    public function updateAtsAndHashtags()
    {
	$tweets = $this->getTweets();
	foreach($tweets as $tweet)
	{
		$jsonArray = json_decode($tweet->getJsonBlob());
		$hashtags = '';
		$ats = '';
		$separator = ',';

		foreach($jsonArray->{'entities'}->{'hashtags'} as $hashtagToken)
		{
			$hashtags = $hashtags . $hashtagToken->{'text'} . $separator;
		}
		$tweet->setHashtags(strtolower(substr($hashtags, 0 , -1)));

		foreach($jsonArray->{'entities'}->{'user_mentions'} as $userMentionToken)
		{
			$ats = $ats . $userMentionToken->{'screen_name'} . $separator;
		}
		$tweet->setAts(strtolower(substr($ats, 0, -1)));
	}

	$em = $this->getDoctrine()->getManager()->flush();
    }	


    public function getUserFromLogin($login)
    {
	return $this->getDoctrine()->getManager()->createQueryBuilder()
		->select('u')
		->from('UCDTweetTweetBundle:User', 'u')
		->where('u.login = ?1')
		->setParameter(1, $login)
		->getQuery()->getSingleResult();
    }


    public function getUserCount($login)
    {
	return $this->getDoctrine()->getManager()->createQueryBuilder()
		->select('count(u.id)')
		->from('UCDTweetTweetBundle:User', 'u')
		->where('u.login = ?1')
		->setParameter(1, $login)
		->getQuery()->getSingleScalarResult();
    }

 

    public function getTweets()
    {
	return  $this->getDoctrine()->getManager()->createQueryBuilder()
		->select('t')
		->from('UCDTweetTweetBundle:Tweet', 't')
		->addOrderBy('t.created')
		->getQuery()
		->getResult();
    }


    public function getTweetCount()
    {
	return $this->getDoctrine()->getManager()->createQueryBuilder()
		->select('count(t.id)')
		->from('UCDTweetTweetBundle:Tweet', 't')
		->getQuery()->getSingleScalarResult();
    }

 
    public function getTweetFromId($tweetId)
    {
	return  $this->getDoctrine()->getManager()->createQueryBuilder()
		->select('t')
		->from('UCDTweetTweetBundle:Tweet', 't')
		->where('t.id = ?1')
		->setParameter(1, $tweetId)
		->getQuery()
		->getSingleResult();
    }
 

    public function getTweetsFromTimeframe($startDate, $endDate)
    {
	return  $this->getDoctrine()->getManager()->createQueryBuilder()
		->select('t')
		->from('UCDTweetTweetBundle:Tweet', 't')
		->where('t.created BETWEEN ?1 AND ?2')
		->setParameter(1, $startDate, \Doctrine\DBAL\Types\Type::DATETIME)
		->setParameter(2, $endDate, \Doctrine\DBAL\Types\Type::DATETIME)
		->addOrderBy('t.created')
		->getQuery()
		->getResult();
    }

    
    public function isTweetTagged($userId, $tweetId)
    {
	return $this->getDoctrine()->getManager()->createQueryBuilder()
		->select('count(t.id)')
		->from('UCDTweetTweetBundle:Tag', 't')
		->where('t.user = ?1 and t.tweet = ?2')
		->setParameter(1, $userId)
		->setParameter(2, $tweetId)
		->getQuery()->getSingleScalarResult();
    }


    public function getTypeEvents()
    {
	return $this->getDoctrine()->getManager()->createQueryBuilder()
		->select('t')
		->from('UCDTweetTweetBundle:TypeEvent', 't')
		->addOrderBy('t.typeEvent')
		->getQuery()
		->getResult();
    }


    public function getTypeEventFromId($typeId)
    {
	return $this->getDoctrine()->getManager()->createQueryBuilder()
		->select('t')
		->from('UCDTweetTweetBundle:TypeEvent', 't')
		->where('t.id = ?1')
		->setParameter(1, $typeId)
		->getQuery()
		->getSingleResult();
    }

  
    public function persistTag($user, $tweetId, $relevant, $values)
    {
	if($this->isTweetTagged($user->getId(), $tweetId))
	{
		return;
	}

	$tag = new \UCDTweet\TweetBundle\Entity\Tag();

	if($relevant)
	{
		$type = $values['tweet_type'];
		$type_highlighted = $values['tweet_type_highlighted'];
		$loc_lat = $values['tweet_location_lon'];
		$loc_lon = $values['tweet_location_lat'];
		$loc_radius = $values['tweet_location_radius'];
		$loc_highlighted = $values['tweet_location_highlighted'];
		$useStartDateTime = $values['tweet_start_datetime_use'];
		$useEndDateTime = $values['tweet_end_datetime_use'];
		$date_begin = $values['tweet_date_begin'];
		$time_begin = $values['tweet_time_begin'];
		$date_end = $values['tweet_date_end'];
		$time_end = $values['tweet_time_end'];
		$time_highlighted = $values['tweet_time_highlighted'];
		$linked = $values['tweet_linked'];
		$strIrrelevantTweets = $values['tweet_irrelevant'];
		echo "strIrrelevant=$strIrrelevantTweets";

		if(strlen($strIrrelevantTweets) != 0)
		{
			foreach(preg_split('/,/', $strIrrelevantTweets) as $irrelevantTweet)
			{
				$dbTweet = $this->getTweetFromId($irrelevantTweet);
				$this->persistTag($user, $dbTweet->getId(), false, null);
			}
		}

		$dbType = $this->getTypeEventFromId($type);		
	
		if($useStartDateTime == '1')
		{
			$startDate = \DateTime::createFromFormat('d/m/Y H:i', $date_begin . ' ' . $time_begin);
		}
		else
		{
			$startDate = new \DateTime();
			$startDate->setTimestamp(0);
		}

		if($useEndDateTime == '1')
		{
			$endDate = \DateTime::createFromFormat('d/m/Y H:i', $date_end . ' ' . $time_end);
		}
		else
		{
			$endDate = new \DateTime();
			$endDate->setTimestamp(0);
		}

		$tag->setCenterLat((float)$loc_lat);
		$tag->setCenterLon((float)$loc_lon);
		$tag->setRadius((float)$loc_radius);
		$tag->setStartTimestamp($startDate);
		$tag->setEndTimestamp($endDate);
		$tag->setHighlightedTime($time_highlighted);
		$tag->setHighlightedLocation($loc_highlighted);
		$tag->setHighlightedType($type_highlighted);
		$tag->setType($dbType);

		if(strlen($linked) != 0)
		{
			$linkedTweets = preg_split('/,/', $linked);
			foreach($linkedTweets as $linkedTweet)
			{
				$dbTweet = $this->getTweetFromId($linkedTweet);
				$tag->addLinkedTweet($dbTweet);
			}
		}
	}

	$tag->setRelevant($relevant);
	$tag->setUser($user);
	$tag->setTweet($this->getTweetFromId($tweetId));

	$em = $this->getDoctrine()->getManager();
	$em->persist($tag);
	$em->flush();
    }
	

    public function persistUser(\UCDTweet\TweetBundle\Entity\User $user)
    {
	$em = $this->getDoctrine()->getManager();
	$em->persist($user);
	$em->flush();
    }


    public function loginAction(Request $request)
    {
	$login = $this->getRequest()->getSession()->get('login');
	
	if($login)
	{
		return $this->redirect($this->generateUrl('tweetslinker'));	
	}

	$error = '';
	$user = new \UCDTweet\TweetBundle\Entity\User();
	$formBuilder = $this->createFormBuilder();
	$formBuilder
	    ->add('login', 'text')
	    ->add('password', 'password');
	 
	$form = $formBuilder->getForm();
 
	if($request->getMethod() === 'POST')
	{
		$form->bind($request);
		$login = $form['login']->getData();
		$password = $form['password']->getData();

		$error = '';
		if($login != null)
		{
			if($this->getUserCount($login) == 1)
			{	
				$dbUser = $this->getUserFromLogin($login);
				if($dbUser->getPassword() === $password)
				{
					$session = $this->getRequest()->getSession();
					$session->set('login', $login);
					$session->set('userId', $dbUser->getId());

					return $this->redirect($this->generateUrl('tweetslinker'));	
				}
				$error = 'Wrong password. Please try again.';
			}
			else
			{	
				$error = 'Unknown login. Please try again.';
			}
		}
	}
  
	return $this->render('UCDTweetTweetBundle:Page:login.html.twig', 
		array('form' => $form->createView(), 'error' => $error)); 
    }


    public function signupAction(Request $request)
    {
	$login = $this->getRequest()->getSession()->get('login');
	
	if($login)
	{
		return $this->redirect($this->generateUrl('tweetslinker'));	
	}
	
	$user = new \UCDTweet\TweetBundle\Entity\User();
	$formBuilder = $this->createFormBuilder($user);
	$formBuilder
	    ->add('login', 'text')
	    ->add('password', 'repeated', array(
    		'type' => 'password',
		    'invalid_message' => 'The password fields must match.',
		    'options' => array('attr' => array('class' => 'password-field')),
		    'required' => true,
		    'first_options'  => array('label' => 'Password'),
		    'second_options' => array('label' => 'Repeat Password'),
		))
	    ->add('email', 'text')
	    ->add('trust', 'choice', array(
                'choices' => array('good' => 'I am really familiar with the Irish road network',
                'medium' => 'I live in Ireland but I can make mistakes',
                'low' => 'I have never been to Ireland before')
                ));
	 
	$form = $formBuilder->getForm();
	$error = '';
		
	if($this->getRequest()->getMethod() === 'POST')
	{
		$form->bind($request);
		$login = $user->getLogin();
		$password = $user->getPassword();
		$email = $user->getEmail();

		if($this->getUserCount($login) != 0)
		{
			$error = 'This login already exists';
		}
		else if($password === $login)
		{
			$error = 'Your password must not be equal to your login';
		}
		else if($form->isValid())
		{
			$this->persistUser($user);
			$userDb = $this->getUserFromLogin($login);
			$session = $this->getRequest()->getSession();
			$session->set('login', $login);
			$session->set('userId', $userDb->getId());

			return $this->redirect($this->generateUrl('tweetslinker'));	
		}
	}

	return $this->render('UCDTweetTweetBundle:Page:signup.html.twig', 
			array('form' => $form->createView(), 'error' => $error));
    }


    public function tweetslinkerAction(Request $request)
    {
	$error = '';
	$login = $this->getRequest()->getSession()->get('login');	

	if(!$login)
	{
		return $this->redirect($this->generateUrl('login'));	
	}

	$userId = $this->getRequest()->getSession()->get('userId');

	$tweetsFrame = array();
	$randomTweet = null;
	$randomTweetIndex = 0;
	$message = '';
	
	$tagForm = $this->getTagForm($request);
	$searchForm = $this->getSearchForm($request);

	$typeEvents = $this->getTypeEvents();
	if($this->getRequest()->getMethod() === 'POST')
	{
		$tagValues = $tagForm->getData();
		$action = $tagValues['tweet_action'];

		$searchValues = $searchForm->getData();
		$searchAction = $searchValues['tweet_action_search'];

		if($action === "validateTweet")
		{
			$tweets = $this->getTweets();
			$tweetsLength = count($tweets);
			$message = 'Thanks for tagging !';

			$tweetId = $tagValues['tweet_id'];
			$relevant = $tagValues['tweet_relevant'] == '1' ? true : false;

			$this->persistTag($this->getUserFromLogin($login), $tweetId, $relevant, $tagValues);
			$this->getRandomTweet($userId, $tweets, $tweetsLength, $randomTweet, $randomTweetIndex);
			$this->getRandomTweetsFrame($userId, $tweets, $tweetsLength, $randomTweetIndex, $tweetsFrame);
		}
		else if($action === 'changeTweet')
		{
			$tweets = $this->getTweets();
			$tweetsLength = count($tweets);

			$this->getRandomTweet($userId, $tweets, $tweetsLength, $randomTweet, $randomTweetIndex);
			$this->getRandomTweetsFrame($userId, $tweets, $tweetsLength, $randomTweetIndex, $tweetsFrame);
		}
		else if($searchAction === 'getTweets')
		{
			$tweetId = $searchValues['tweet_id_search'];
			$useTimeframe = $searchValues['tweet_use_timeframe_search'];
			$strStartDate = $searchValues['tweet_start_date_search'];
			$strStartTime = $searchValues['tweet_start_time_search'];
			$strEndDate = $searchValues['tweet_end_date_search'];
			$strEndTime = $searchValues['tweet_end_time_search'];
			$at = $searchValues['tweet_at_search'];
			$hashtag = $searchValues['tweet_hashtag_search'];
			$content = $searchValues['tweet_content_search'];
			
			$randomTweet = $this->getTweetFromId($tweetId);
			$tweetsFrame = $this->getSearchTweetsFrameAction($userId, $tweetId, $useTimeframe, $strStartDate, $strStartTime, $strEndDate, $strEndTime, $at, $hashtag, $content);
			$return = array("responseCode"=>200, "responseHTML"=>$this->renderView('UCDTweetTweetBundle:Page:tweetslist.html.twig', array('tweets' => $tweetsFrame)));
			$return = json_encode($return);
			return new Response($return,200,array('Content-Type'=>'application/json'));	
		}
	}
	else
	{	
		$tweets = $this->getTweets();
		$tweetsLength = count($tweets);

		$this->getRandomTweet($userId, $tweets, $tweetsLength, $randomTweet, $randomTweetIndex);
		$this->getRandomTweetsFrame($userId, $tweets, $tweetsLength, $randomTweetIndex, $tweetsFrame);
	}

	return $this->render('UCDTweetTweetBundle:Page:tweetslinker.html.twig', array('message' => $message, 'tweets' => $tweetsFrame, 'error' => $error, 'login' => $login, 'userId' => $userId, 'typeEvents' => $typeEvents, 'randomTweet' => $randomTweet, 'tagForm' => $tagForm->createView(), 'searchForm' => $searchForm->createView()));
    }


    public function getSearchTweetsFrameAction($userId, $tweetId, $useTimeframe, $strStartDate, $strStartTime, $strEndDate, $strEndTime, $at, $hashtag, $content)
    {
	$at = trim($at);
	$at = preg_replace('/\s+/', ' ', $at);
	$at = preg_replace('/\@/', '', $at);
	$at = strtolower($at);
	$atSearch = strlen($at) != 0 ? true : false;	

	$hashtag = trim($hashtag);
	$hashtag = preg_replace('/\s+/', ' ', $hashtag);
	$hashtag = preg_replace('/\#/', '', $hashtag);
	$hashtag = strtolower($hashtag);
	$hashtagSearch = strlen($hashtag) != 0 ? true : false;

	$content = trim($content);
	$content = preg_replace('/\s+/', ' ', $content);
	$content = strtolower($content);

	if($content != '')
	{
		$contentArray = preg_split('/\s/', $content);
		$contentCount = count($contentArray);
	}
	else
	{
		$contentCount = 0;
	}


	if($useTimeframe === '0' and !$atSearch and !$hashtagSearch and $contentCount == 0)
	{
		return;
	}

	$tweetsFrame = array();
	$start = $strStartDate . ' ' . $strStartTime;
	$end = $strEndDate . ' ' . $strEndTime;

	$startDate = \DateTime::createFromFormat('d/m/Y H:i', $strStartDate . ' ' . $strStartTime);
	$endDate = \DateTime::createFromFormat('d/m/Y H:i', $strEndDate . ' ' . $strEndTime);

	if($useTimeframe === '0')
	{
		$tweets = $this->getTweets();
	}
	else
	{
		$tweets = $this->getTweetsFromTimeframe($startDate, $endDate);
	}

	foreach($tweets as $tweet)
	{
		if((($atSearch and strpos($tweet->getAts(), $at) !== false) or !$atSearch) and (($hashtagSearch and strpos($tweet->getHashtags(), $hashtag) !== false) or !$hashtagSearch) and $tweet->getId() != $tweetId)
		{
			$found = true;
			$tweetContent = strtolower($tweet->getTweetText());

			if($contentCount != 0)
			{
				foreach($contentArray as $contentValue)
				{
					if(strpos($tweetContent, $contentValue) === false)
					{
						$found = false;
						break;
					}
				}
			}

			if($found)
			{
				$tagIdx = 0;
				$found = false;
				$add = true;
				$tmpTags = $tweet->getTags();

				while($tagIdx < count($tmpTags) && !$found)
				{
					$tmpTag = $tmpTags[$tagIdx];
					if($tmpTag->getUser()->getId() == $userId)
					{
						if($tmpTag->getRelevant() == false)
						{
							$add = false;
						}
						$found = true;
					}
					$tagIdx += 1;
				}

				if($add)
				{
					array_push($tweetsFrame, $tweet);
				}
			}
		}
	}

	return $tweetsFrame;
    }


    public function getRandomTweet($userId, &$tweets, &$tweetsLength, &$randomTweet, &$randomTweetIndex)
    {
	$randomTweetIndex = rand(0, $tweetsLength - 1);
	$randomTweet = $tweets[$randomTweetIndex];
	
	$isTagged = $this->isTweetTagged($userId, $randomTweet->getId());

	while($this->isTweetTagged($userId, $randomTweet->getId()))
	{
		unset($tweets[$randomTweetIndex]);
		$tweetsLength -= 1;
		$randomTweetIndex = rand(0, $tweetsLength - 1);
		$randomTweet = $tweets[$randomTweetIndex];
	}
    }


    public function getRandomTweetsFrame($userId, &$tweets, $tweetsLength, $randomTweetIndex, &$tweetsFrame)
    {
	$frameSize = 30;

	if($tweetsLength > 1)
	{
		$currentFrameSize = 0;

		$index = $randomTweetIndex - 1;
		while($currentFrameSize < $frameSize/2 && $index >= 0)
		{
			$tweet = $tweets[$index];
			
			if($this->isTweetTagged($userId, $tweet->getId()) == 0)
			{
				array_unshift($tweetsFrame, $tweet);
				$currentFrameSize += 1;
			}	
			$index -= 1;
		}
		
		$index = $randomTweetIndex + 1;
		while($currentFrameSize != $frameSize && $index < $tweetsLength)
		{
			$tweet = $tweets[$index];

			if($this->isTweetTagged($userId, $tweet->getId()) == 0)
			{
				array_push($tweetsFrame, $tweet);
				$currentFrameSize += 1;
			}	
			$index += 1;
		}
	}
    }


    public function logoutAction(Request $request)
    {
	$this->getRequest()->getSession()->set('login', null);
	$this->getRequest()->getSession()->set('userId', null);
	$this->getRequest()->getSession()->invalidate();
	return $this->redirect($this->generateUrl('login'));	
    }


    public function getTagForm(Request $request)
    {
	$formBuilder = $this->createFormBuilder();
	$formBuilder
	    ->add('tweet_action', 'hidden')
	    ->add('tweet_id', 'hidden')
	    ->add('tweet_relevant', 'hidden')
	    ->add('tweet_type', 'hidden')
	    ->add('tweet_type_highlighted', 'hidden')
	    ->add('tweet_location', 'hidden')
	    ->add('tweet_location_highlighted', 'hidden')
	    ->add('tweet_location_lon', 'hidden')
	    ->add('tweet_location_lat', 'hidden')
	    ->add('tweet_location_radius', 'hidden')
	    ->add('tweet_time_highlighted', 'hidden')
	    ->add('tweet_start_datetime_use', 'hidden')
	    ->add('tweet_end_datetime_use', 'hidden')
	    ->add('tweet_date_begin', 'hidden')
	    ->add('tweet_date_end', 'hidden')
	    ->add('tweet_time_begin', 'hidden')
	    ->add('tweet_time_end', 'hidden')
	    ->add('tweet_linked', 'hidden')
	    ->add('tweet_irrelevant', 'hidden');
	 
	$form = $formBuilder->getForm();
	$form->bind($request);
   
	return $form;
    }

    public function getSearchForm(Request $request)
    {
	$formBuilder = $this->createFormBuilder();
	$formBuilder
	    ->add('tweet_action_search', 'hidden')
	    ->add('tweet_id_search', 'hidden')
	    ->add('tweet_use_timeframe_search', 'hidden')
	    ->add('tweet_start_date_search', 'hidden')
	    ->add('tweet_start_time_search', 'hidden')
	    ->add('tweet_end_date_search', 'hidden')
	    ->add('tweet_end_time_search', 'hidden')
	    ->add('tweet_at_search', 'hidden')
	    ->add('tweet_hashtag_search', 'hidden')
	    ->add('tweet_content_search', 'hidden');
	 
	$form = $formBuilder->getForm();
	$form->bind($request);
   
	return $form;
    }
}
