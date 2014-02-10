<?php

/*
 * This file is part of Manhattan Form Wizard Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\FormWizardBundle\Wizard;

use Symfony\Component\Form\AbstractType;

abstract class AbstractStep
{
    /**
     * Sets the Title associated with the step
     *
     * @var string
     */
    private $title;

    /**
     * Sets the route name to view the step
     *
     * @var string
     */
    private $route;

    /**
     * Sets the route name to view the step
     *
     * @var string
     */
    private $template;

    /**
     * Sets the type to display on the step
     *
     * @var Symfony\Component\Form\AbstractType
     */
    private $type;

    /**
     * Entity required on step to display
     *
     * @var mixed
     */
    private $entity;

    /**
     * Constructor
     *
     * @param string                        $title
     * @param string                        $route
     * @param string                        $template
     * @param Symfony\Component\Form\Form   $type
     * @param mixed                         $entity
     */
    public function __construct($title, $route, $template, AbstractType $type = null, $entity = null)
    {
        $this->type = $type;
        $this->title = $title;
        $this->route = $route;
        $this->entity = $entity;
        $this->template = $template;
    }

    public function __toString()
    {
        return $this->title;
    }


    /**
     * @return Symfony\Component\Form\AbstractType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Determine if the Step is complete
     *
     * @return boolean
     */
    public abstract function completed();
}
