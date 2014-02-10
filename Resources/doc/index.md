Manhattan Form Wizard Bundle
============================

Installation
------------

1. Add this bundle to your project in composer.json:

    Symfony 2.1 uses composer (http://www.getcomposer.org) to organize dependencies:

    ```json
    {
        "require": {
            "manhattan/form-wizard-bundle": "dev-master",
        }
    }
    ```

2. Add this bundle to your app/AppKernel.php:

    ``` php
    // application/ApplicationKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Manhattan\FormWizardBundle(),
            // ...
        );
    }
    ```

Setup
-----

Setup instructions are located in the [setup.md](https://github.com/frodosghost/AtomLoggerBundle/blob/master/Resources/doc/setup.md) file.
