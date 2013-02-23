<?php

namespace Svecon\MffNotifierBundle\Controller;

use Sunra\PhpSimple\HtmlDomParser;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CronController extends Controller {

    protected $websiteCache = array();
    protected $emailCache = array();

    /**
     * @Route("/cron")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $subscriptions = $em->getRepository('SveconMffNotifierBundle:Subscription');

        $entries = $subscriptions->createQueryBuilder('sb')
                ->leftJoin('sb.subscriber', 's')
                ->leftJoin('sb.section', 'se')
//                ->leftJoin('se.website', 'w', 'WITH', 'se')
                ->select(array('sb', 's', 'se'))
//                ->orderBy('sb.website', 'ASC')
                ->getQuery()
                ->getResult();

        $counterSections = 0;
        $counterSubscribers = 0;

        foreach ($entries as $entry) {
            $webID = $entry->getSection()->getWebsite()->getId();

            // Cache website (using with multiple sections and users)
            if (!isset($this->websiteCache[$webID])) {
                $this->websiteCache[$webID] = HtmlDomParser::file_get_html($entry->getSection()->getWebsite()->getUrl());

                // Whole page didn't change
                if (md5($this->websiteCache[$webID]) == $entry->getSection()->getWebsite()->getHash())
                    continue;

                // Update hashes
                $entry->getSection()->getWebsite()->setHash(md5($this->websiteCache[$webID]));
                $em->persist($entry->getSection()->getWebsite());
                $em->flush();
            }

            // Section didn't change
            $section = $this->websiteCache[$webID]->find($entry->getSection()->getSelector(), $entry->getSection()->getOrdered());
            if (md5($section) == $entry->getSection()->getHash())
                continue;

            // Update hashes
            $entry->getSection()->setHash(md5($section));
            $em->persist($entry->getSection());
            $em->flush();

            $counterSections++;

            // Cache user email -> send only one email per user
            $userID = $entry->getSubscriber()->getId();
            $emailBody = "<h2>{$entry->getSection()->getWebsite()->getTitle()}: {$entry->getSection()->getTitle()}</h2>{$section->outertext}";

            if (isset($this->emailCache[$userID])) {
                $this->emailCache[$userID]->setBody(
                        $this->emailCache[$userID]->getBody()
                        .
                        $emailBody
                );
            } else {
                $this->emailCache[$userID] = Swift_Message::newInstance()
                        ->setSubject('MFF notificator')
                        ->setFrom('notificator@svecon.cz')
                        ->setTo($entry->getSubscriber()->getEmail())
                        ->setBody($emailBody, 'text/html')
                ;
            }
        }

        // Send emails
        foreach ($this->emailCache as $email) {
            $this->get('mailer')->send($email);
            $counterSubscribers++;
        }



        return new Response("<body>$counterSections webistes changed, $counterSubscribers emails sent</body>");
    }

}

