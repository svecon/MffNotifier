<?php

namespace Svecon\MffNotifierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Website
 *
 * @ORM\Table(name="website")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Website {

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
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, nullable=false)
     */
    private $hash;

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
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=false)
     */
    private
            $updated;

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
     * @return Website
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
     * Set url
     *
     * @param string $url
     * @return Website
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Website
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
    public
            function getHash() {
        return $this->hash;
    }

    /**
     * Set selector
     *
     * @param string $selector
     * @return Website
     */
    public
            function setSelector($selector) {
        $this->selector = $selector;

        return $this;
    }

    /**
     * Get selector
     *
     * @return string 
     */
    public function

    getSelector() {
        return $this->selector;
    }

    /**
     * Set ordered
     *
     * @param integer $ordered
     * @return Website
     */
    public function

    setOrdered($ordered) {
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Website
     */
    public function setUpdated($updated
    ) {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated() {

        return $this->updated;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps() {
        $this->setUpdated(new \DateTime(date('Y-m-d H:i:s')));
    }

}