<?php

/*
 * This file is part of Manhattan Form Wizard Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manhattan\FormWizardBundle\Tests\Wizard;

use Manhattan\FormWizardBundle\Wizard\AbstractWizard;

/**
 * AbstractWizardTest
 *
 * @author James Rickard <james@frodosghost.com>
 */
class AbstractWizardTest extends \PHPUnit_Framework_TestCase
{
    private $mockSession;

    private $mockEntityManager;

    private $mockCampaignRepository;

    public function setUp()
    {
        $this->mockSession = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockCampaignRepository = $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockEntityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function tearDown()
    {
        $this->mockSession = null;
        $this->mockEntityManager = null;
        $this->mockCampaignRepository = null;
    }

    public function testConstructor()
    {
        $advertismentWizard = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractWizard', array(
            $this->mockSession, $this->mockEntityManager
        ));

        $this->assertEquals(0, $advertismentWizard->count(), '->count() returns 0 when no steps are passed.');
    }

    /**
     * Test Expected Exception
     */
    public function testCurrentException()
    {
        $advertismentWizard = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractWizard', array(
            $this->mockSession, $this->mockEntityManager
        ));

        $this->setExpectedException('Manhattan\FormWizardBundle\Exception\WizardException');
        $advertismentWizard->current();
    }

    /**
     * Test Expected Exception
     */
    public function testNextException()
    {
        $advertismentWizard = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractWizard', array(
            $this->mockSession, $this->mockEntityManager
        ));

        $this->setExpectedException('Manhattan\FormWizardBundle\Exception\WizardException');
        $advertismentWizard->next();
    }

    /**
     * Test to see of nextUnfinished returns next unfinished step
     */
    public function testNextUnfinished()
    {
        $uncompleted = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractStep', array('foo', 'bar', 'foobar'));
        $uncompleted->expects($this->once())->method('completed')->will($this->returnValue(false));

        $completed = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractStep', array('completed', 'foo', 'barbarfoo'));
        $completed->expects($this->any())->method('completed')->will($this->returnValue(true));

        $completedTwo = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractStep', array('completed', 'completed', 'barbarfoo'));
        $completedTwo->expects($this->any())->method('completed')->will($this->returnValue(true));

        $advertismentWizard = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractWizard', array(
            $this->mockSession, $this->mockEntityManager, array($completed, $uncompleted, $completedTwo)
        ));

        // Confirm abstract steps have been passed into the Wizard
        $this->assertEquals(3, $advertismentWizard->count(), '->count() returns 3 when two steps are passed.');

        $this->assertEquals($uncompleted, $advertismentWizard->nextUnfinished(), '->nextUnfinished() returns the uncompleted step.');
    }

    /**
     * Tests the Next Unfinished step tp return to last step, even though
     * it has been completed.
     */
    public function testNextUnfinishedAllCompleted()
    {
        $completed = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractStep', array('completed', 'foo', 'barbarfoo'));
        $completed->expects($this->any())->method('completed')->will($this->returnValue(true));

        $completedTwo = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractStep', array('completed', 'completed', 'barbarfoo'));
        $completedTwo->expects($this->any())->method('completed')->will($this->returnValue(true));

        $advertismentWizard = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractWizard', array(
            $this->mockSession, $this->mockEntityManager, array($completed, $completedTwo)
        ));

        // Confirm abstract steps have been passed into the Wizard
        $this->assertEquals(2, $advertismentWizard->count(), '->count() returns 2 when two steps are passed.');

        $nextUnfinished = $advertismentWizard->nextUnfinished();

        $this->assertEquals($completedTwo, $nextUnfinished, '->nextUnfinished() returns the last step, even though it is completed.');

        $this->assertEquals('completed', $nextUnfinished->getRoute(), '->nextUnfinished() returns the last step, even though it is completed.');
    }

    /**
     * Test Expected Exception
     */
    public function testPreviousException()
    {
        $advertismentWizard = $this->getMockForAbstractClass('Manhattan\FormWizardBundle\Wizard\AbstractWizard', array(
            $this->mockSession, $this->mockEntityManager
        ));

        $this->setExpectedException('Manhattan\FormWizardBundle\Exception\WizardException');
        $advertismentWizard->previous();
    }

}
