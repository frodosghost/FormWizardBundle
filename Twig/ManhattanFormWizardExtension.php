<?php

/*
 * This file is part of Manhattan Form Wizard Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\FormWizardBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Manhattan\FormWizardBundle\Wizard\AbstractWizard;

/**
 * Twig Extension for displaying Form Wizard navigation
 *
 * @author James Rickard <james@frodosghost.com>
 */
class ManhattanFormWizardExtension extends \Twig_Extension
{
    /**
     * @var Twig_Environment
     */
    private $environment;

    /**
     * @var Twig_Template
     */
    private $twigTemplate;

    /**
     * @var Manhattan\FormWizardBundle\Wizard\AbstractWizard
     */
    private $abstractWizard;

    /**
     * @var string
     */
    private $template;


    public function __construct(\Twig_Environment $environment, AbstractWizard $abstractWizard, $template)
    {
        $this->environment = $environment;
        $this->abstractWizard = $abstractWizard;
        $this->template = $template;
    }

    public function getFunctions()
    {
        return array(
            'wizardNavigation' => new \Twig_Function_Method($this, 'navigation', array('is_safe' => array('html')))
        );
    }

    /**
     * Builds and returns Twig Template
     */
    public function getTemplate()
    {
        if (!$this->twigTemplate instanceof \Twig_Template) {
            $this->twigTemplate = $this->environment->loadTemplate($this->template);
        }

        return $this->twigTemplate;
    }

    /**
     * Renders analytics javascript
     *
     * @param  array $options
     * @return string
     */
    public function navigation(array $options = array())
    {
        $html = $this->getTemplate()->renderBlock('navigation', array(
            'wizard' => $this->abstractWizard,
            'options'   => $options
        ));

        return $html;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'manhattan_wizard_twig';
    }
}
