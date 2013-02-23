<?php

namespace Svecon\MffNotifierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscription
 *
 * @ORM\Table(name="subscription",uniqueConstraints={@ORM\UniqueConstraint(name="subscriber_section", columns={"section_id", "subscriber_id"})}))
 * @ORM\Entity
 */
class Subscription {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Section
     *
     * @ORM\ManyToOne(targetEntity="Section")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="section_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $section;

    /**
     * @var \Subscriber
     *
     * @ORM\ManyToOne(targetEntity="Subscriber")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subscriber_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $subscriber;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set section
     *
     * @param \Svecon\MffNotifierBundle\Entity\Section $section
     * @return Subscription
     */
    public function setSection(\Svecon\MffNotifierBundle\Entity\Section $section = null) {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return \Svecon\MffNotifierBundle\Entity\Section 
     */
    public function getSection() {
        return $this->section;
    }

    /**
     * Set subscriber
     *
     * @param \Svecon\MffNotifierBundle\Entity\Subscriber $subscriber
     * @return Subscription
     */
    public function setSubscriber(\Svecon\MffNotifierBundle\Entity\Subscriber $subscriber = null) {
        $this->subscriber = $subscriber;

        return $this;
    }

    /**
     * Get subscriber
     *
     * @return \Svecon\MffNotifierBundle\Entity\Subscriber 
     */
    public function getSubscriber() {
        return $this->subscriber;
    }

}