<?php

namespace Svecon\MffNotifierBundle\Controller;

use Sunra\PhpSimple\HtmlDomParser;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CronController extends Controller {

    /**
     * @Route("/cron")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $subscriptions = $em->getRepository('SveconMffNotifierBundle:Subscription');

        $entries = $subscriptions->createQueryBuilder('sb')
                ->leftJoin('sb.subscriber', 's')
                ->leftJoin('sb.website', 'w')
                ->select(array('sb', 's', 'w'))
                ->orderBy('sb.website', 'ASC')
                ->getQuery()
                ->getResult();

        $counterWebistes = 0;
        $counterSubscribers = 0;
        $prevWebiste = -1;
        foreach ($entries as $entry) {
            if ($prevWebiste != $entry->getWebsite()->getId()) {
                $html = HtmlDomParser::file_get_html($entry->getWebsite()->getUrl());

                $snippet = $html->find($entry->getWebsite()->getSelector(), $entry->getWebsite()->getOrdered());

                $md5 = md5($snippet);
                if ($md5 == $entry->getWebsite()->getHash())
                    continue;

                $entry->getWebsite()->setHash($md5);
                $em->persist($entry->getWebsite());
                $em->flush();

                $counterWebistes++;
            }

            $message = Swift_Message::newInstance()
                    ->setSubject('MFF notificator')
                    ->setFrom('notificator@svecon.cz')
                    ->setTo($entry->getSubscriber()->getEmail())
                    ->setBody("<h2>{$entry->getWebsite()->getTitle()}</h2>{$snippet->outertext}", 'text/html')
            ;
            $this->get('mailer')->send($message);

            $counterSubscribers++;
            $prevWebiste = $entry->getWebsite()->getId();
        }

        return new Response("$counterWebistes webistes changed, $counterSubscribers emails sent");
    }

}
