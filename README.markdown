# WowoQueueBundle

*WARNING* this bundle is still under development, although you can use it on your own risk :-)

[![Build Status](https://secure.travis-ci.org/wowo/WowoQueueBundle.png)](https://secure.travis-ci.org/wowo/WowoQueueBundle)

The WowoQueueBundle provides unified method for use queue systems, like Beanstalkd, RabbitMQ, flat files,
database driven queues, etc. For now it only supports Beanstalkd, but you can add your own implementation
on your own and send pull request.

## Installation

### Step 1: Download WowoQueueBundle

Add following lines to your `deps` file:

```
    [WowoNewsletterBundle]
        git=git://github.com/wowo/WowoQueueBundle.git
        target=bundles/Wowo/QueueBundle

```
Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

### Step 2: Configure the Autoloader

Add the `Wowo` namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
        'Wowo' => __DIR__.'/../vendor/bundles',
        ));
```

### Step 3: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
        $bundles = array(
            // ...
            new Wowo\NewsletterBundle\WowoQueueBundle(),
        );
}
```
