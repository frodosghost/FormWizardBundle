Manhattan Form Wizard Bundle
============================

1. Configure Each Step of the Wizard
------------------------------------

The Manhattan Form Wizard Bundle provides a base class for configuring each step of the form
wizard. You will need to create a new class for each step of the process and extend the
`AbstractStep` class. Then inside the new class configure what determines if the Step is
completed.

    ``` php
    namespace Acme\DemoBundle\Wizard;

    use Manhattan\FormWizardBundle\Wizard\AbstractStep;

    class FirstStep extends AbstractStep
    {
        /**
         * Determines if the step has been completed
         */
        public function completed()
        {
            $entity = $this->getEntity();

            if ($entity->getCreatedAt() instanceof \DateTime) {
                return true;
            }

            return false;
        }
    }
    ```

2. Create the Step as a Service
-------------------------------

Because the Wizard is loaded as a Service we need to create the Step as a service so it can be
injected into the Wizard.

Each Step requires the first three arguments: The title, route name and template. These are
provided as strings.

The Type and the Entity are the final two parameters, these are optional, but if they are to be
included you must define them as a service beforehand, in the services.xml.

    ``` xml
    <service id="wizard.first.step.type" class="Acme\DemoBundle\Form\FirstStepType"></service>

    <service id="wizard.first.step" class="Acme\DemoBundle\Wizard\FirstStep">
        <argument type="string">Step Titile</argument>
        <argument type="string">step_route_name</argument>
        <argument type="string">AcmeDemoBundle:Wizard:stepTwo.html.twig</argument>
        <argument type="service" id="wizard.first.step.type" />
    </service>
    ```

3. Create the Wizard Class
--------------------------

Again you will need to configure the Wizard class to extend `AbstractWizard.php`. The abstract class
forces you to define the setup function.

The setup function allows you to configure the entity managed within the Form Wizard, including setting
up the Entity by retrieving it with a Repository.

    ``` php
    class AcmeWizard extends AbstractWizard
    {
        /**
         * Setup function called to populate Steps
         *
         * @return AcmeWizard
         */
        public function setup()
        {
            $id = $this->getSession()->get('acme/id');

            $entity = $this->getEntityManager()
                ->getRepository('AcmeDemoBundle:Wizard')
                ->findWizardById($id);

            if (!$entity instanceof Wizard) {
                $entity = new Wizard();
            }
            $this->setEntity($entity);

            return $this;
        }

        public function update($entity)
        {

        }
    }
    ```

4. Configure the Wizard as a Service
------------------------------------

Create the service within the Service Container.

    ``` xml
    <service id="acme.demo.wizard" class="%acme.demo.wizard.class%">
        <argument type="service" id="session" />
        <argument type="service" id="doctrine.orm.entity_manager" />
        <argument type="collection">
            <argument type="service" id="wizard.first.step" />
        </argument>
        <tag name="monolog.logger" channel="wizard_error" />
    </service>
    ```

5. Controller
-------------

Now you can access the Wizard in the controller. To set the current step pass in the route from the
controller.

    ``` php
    $wizard = $this->get('acme.demo.wizard');
    $wizard->select($request->get('_route'));
    ```

Then you have access to the step.

    ``` php
    /** @var AbstractStep $step **/
    $step = $wizard->current();
    ```
