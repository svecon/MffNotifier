<?php

namespace Svecon\MffNotifierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscription
 *
 * @ORM\Table(name="subscription")
 * @ORM\Entity
 */
class Subscription
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Website
     *
     * @ORM\ManyToOne(targetEntity="Website")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="website_id", referencedColumnName="id")
     * })
     */
    private $website;

    /**
     * @var \Subscriber
     *
     * @ORM\ManyToOne(targetEntity="Subscriber")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscriber_id", referencedColumnName="id")
     * })
     */
    private $subscriber;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set website
     *
     * @param \Svecon\MffNotifierBundle\Entity\Website $website
     * @return Subscription
     */
    public function setWebsite(\Svecon\MffNotifierBundle\Entity\Website $website = null)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return \Svecon\MffNotifierBundle\Entity\Website 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set subscriber
     *
     * @param \Svecon\MffNotifierBundle\Entity\Subscriber $subscriber
     * @return Subscription
     */
    public function setSubscriber(\Svecon\MffNotifierBundle\Entity\Subscriber $subscriber = null)
    {
        $this->subscriber = $subscriber;
    
        return $this;
    }

    /**
     * Get subscriber
     *
     * @return \Svecon\MffNotifierBundle\Entity\Subscriber 
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }
}