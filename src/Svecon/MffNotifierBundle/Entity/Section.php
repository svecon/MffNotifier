<?php

namespace Svecon\MffNotifierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Section
 *
 * @ORM\Table(name="section")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Section {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="selector", type="string", length=255, nullable=false)
     */
    private $selector;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordered", type="integer", nullable=true)
     */
    private $ordered;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, nullable=true)
     */
    private $hash;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update", type="datetime", nullable=true)
     */
    private $lastUpdate;

    /**
     * @var \Website
     *
     * @ORM\ManyToOne(targetEntity="Website")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="website_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $website;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Section
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set selector
     *
     * @param string $selector
     * @return Section
     */
    public function setSelector($selector) {
        $this->selector = $selector;

        return $this;
    }

    /**
     * Get selector
     *
     * @return string 
     */
    public function getSelector() {
        return $this->selector;
    }

    /**
     * Set ordered
     *
     * @param integer $ordered
     * @return Section
     */
    public function setOrdered($ordered) {
        $this->ordered = $ordered;

        return $this;
    }

    /**
     * Get ordered
     *
     * @return integer 
     */
    public function getOrdered() {
        return $this->ordered;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Section
     */
    public function setHash($hash) {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash() {
        return $this->hash;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     * @return Section
     */
    public function setLastUpdate($lastUpdate) {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime 
     */
    public function getLastUpdate() {
        return $this->lastUpdate;
    }

    /**
     * Set website
     *
     * @param \Svecon\MffNotifierBundle\Entity\Website $website
     * @return Section
     */
    public function setWebsite(\Svecon\MffNotifierBundle\Entity\Website $website = null) {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return \Svecon\MffNotifierBundle\Entity\Website 
     */
    public function getWebsite() {
        return $this->website;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps() {
        $this->setLastUpdate(new \DateTime(date('Y-m-d H:i:s')));
    }

}