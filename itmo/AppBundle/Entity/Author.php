<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Author
 *
 * @ORM\Table(name="author")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuthorRepository")
 */
class Author
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=50)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="patronymic", type="string", length=50, nullable=true)
     */
    private $patronymic;

    /**
     * @ORM\ManyToMany(targetEntity="Book", mappedBy="authors")
     */
    private $books;


    public function __construct() {
       $this->books = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Author
     */
    public function setFirstName($firstName)
    {
        $this->firstName = ucfirst($firstName);

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Author
     */
    public function setLastName($lastName)
    {
        $this->lastName = ucfirst($lastName);

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set patronymic
     *
     * @param string $patronymic
     *
     * @return Author
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = ucfirst($patronymic);

        return $this;
    }

    /**
     * Get patronymic
     *
     * @return string
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Get patronymic
     *
     * @return string
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * Get author initials
     *
     * @return string
     */
    public function getInitials()
    {
        $initials =  $this->lastName . " " . $this->firstName[0] . ".";

        if ($this->patronymic) {
          $initials .= $this->patronymic[0] . ".";
        }

        return $initials;
    }

    public function __toString()
    {
        $fullName =  $this->lastName . " " . $this->firstName . ".";

        if ($this->patronymic) {
          $fullName .= $this->patronymic . ".";
        }

        return $fullName;
    }
}
