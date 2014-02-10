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

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use CargoNetwork\AdminBundle\Wizard\Exception\WizardException;


abstract class AbstractWizard implements \IteratorAggregate
{
    /**
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    protected $steps = array();

    /**
     * @var string
     */
    private $current;

    /**
     * Entity required on wizard
     *
     * @var mixed
     */
    private $entity;

    /**
     * Constructor
     *
     * @param Session       $session
     * @param EntityManager $entityManager
     * @param array         $steps
     */
    public function __construct(Session $session, EntityManager $entityManager, array $steps = null)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->current = null;

        if (!is_null($steps)) {
            foreach ($steps as $step) {
                $this->add($step);
            }
        }

        $this->setup();
    }

    /**
     * Add a step into the wizard
     *
     * @param AbstractStep $step
     */
    public function add(AbstractStep $step)
    {
        $this->steps[$step->getRoute()] = $step;
    }

    /**
     * Sets the current varible by checking against the step keys
     *
     * @param  string          $route
     * @return AbstractWizard
     * @throws WizardException
     */
    public function select($route)
    {
        if (!(isset($this->steps[$route]) && $this->steps[$route] instanceof AbstractStep)) {
            throw new WizardException(sprintf('Step with the route "%s" does not exist.', $route));
        }

        $this->current = $route;

        return $this;
    }

    /**
     * Returns the current step
     *
     * @return AbstractStep
     */
    public function current()
    {
        if (is_null($this->current)) {
            throw new WizardException('The current step has not been set. Ensure that "->select()" has been called prior to using "->current()".');
        }

        return $this->steps[$this->current];
    }

    /**
     * Return next step
     *
     * @return AbstractStep
     */
    public function next()
    {
        if (is_null($this->current)) {
            throw new WizardException('The current step has not been set. Ensure that "->select()" has been called prior to using "->next()".');
        }

        $next = null;
        $keys = array_keys($this->steps);
        $position = array_search($this->current, $keys);

        if (isset($keys[$position + 1])) {
            $next = $this->steps[$keys[$position + 1]];
        }

        return $next;
    }

    /**
     * Determines the next unfinished step and returns it
     *
     * @return AbstractStep
     */
    public function nextUnfinished()
    {
        $next = null;

        foreach ($this->getIterator() as $step) {
            $next = $step;

            if (!$step->completed()) {
                break;
            }
        }

        return $next;
    }

    /**
     * Returns all steps
     *
     * @return array
     */
    public function all()
    {
        return $this->steps;
    }

    public function count()
    {
        return $this->getIterator()->count();
    }

    /**
     * Returns first element of the Steps array
     *
     * @return AbstractStep
     */
    public function first()
    {
        return $this->steps[key($this->steps)];
    }

    /**
     * Get Iterator
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * Returns Session
     *
     * @return Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
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
     * Called to setup and establish Wizard class
     *
     * @return AbstractWizard
     */
    public abstract function setup();

    /**
     * Called to update Wizard class
     *
     * @param  mixed          $entity
     * @return AbstractWizard
     */
    public abstract function update($entity);
}
