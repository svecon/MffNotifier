<?php

namespace Svecon\MffNotifierBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sunra\PhpSimple\HtmlDomParser;
use Swift_Attachment;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
            $selector = $entry->getSection()->getSelector();

            // Cache website (using with multiple sections and users)
            if (!isset($this->websiteCache[$webID])) {
                $contents = file_get_contents($entry->getSection()->getWebsite()->getUrl());
                if ($selector == 'pdf') {
                    $this->websiteCache[$webID] = $contents;
                } else {
                    $this->websiteCache[$webID] = HtmlDomParser::str_get_html($contents);
                }

                // Whole page didn't change
                if (md5($this->websiteCache[$webID]) == $entry->getSection()->getWebsite()->getHash())
                    continue;

                // Update hashes
                $entry->getSection()->getWebsite()->setHash(md5($this->websiteCache[$webID]));
                $em->persist($entry->getSection()->getWebsite());
                $em->flush();
            }

            // Whole page didn't change
            if (md5($this->websiteCache[$webID]) == $entry->getSection()->getWebsite()->getHash())
                continue;

            // Getting section
            if ($selector == 'pdf') {
                $section = $this->websiteCache[$webID];
            } else {
                $section = $this->websiteCache[$webID]->find($selector, $entry->getSection()->getOrdered());
            }
            
            // Section didn't change
            if (md5($section) == $entry->getSection()->getHash())
                continue;

            // Update hashes if not PDF
            if ($selector != "pdf") {
                $entry->getSection()->setHash(md5($section));
                $em->persist($entry->getSection());
                $em->flush();
            }

            $counterSections++;

            // Cache user email -> send only one email per user
            $userID = $entry->getSubscriber()->getId();

            $title = "{$entry->getSection()->getWebsite()->getTitle()}: {$entry->getSection()->getTitle()}";
            $emailBody = "<h2>{$title}</h2>";
            if ($selector == 'pdf') {
                $emailBody .= "(viz příloha)";
            } else {
                $emailBody .= $section->outertext;
            }

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

            if ($selector == 'pdf') {
                $this->emailCache[$userID]->attach(
                        Swift_Attachment::newInstance($section, $this->url($title . ".pdf"), "application/pdf")
                );
            }
        }

        // Send emails
        foreach ($this->emailCache as $email) {
            $this->get('mailer')->send($email);
            $counterSubscribers++;
        }

        return new Response("<body>$counterSections webistes changed, $counterSubscribers emails sent</body>");
    }

    function url($url) {
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
        $url = trim($url, "-");
        $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
        return $url;
    }

}

