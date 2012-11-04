# WowoQueueBundle

[![Build Status](https://secure.travis-ci.org/wowo/WowoQueueBundle.png)](https://secure.travis-ci.org/wowo/WowoQueueBundle)

The WowoQueueBundle provides unified method for use queue systems, like Beanstalkd, RabbitMQ, flat files,
database driven queues, etc. For now it only supports Beanstalkd, but you can add your own implementation
on your own and send pull request.

## Installation

### Step 1: Download WowoQueueBundle

#### If you are using Deps (Symfony 2.0.x)

Add following lines to your `deps` file:

```
    [WowoQueueBundle]
        git=git://github.com/wowo/WowoQueueBundle.git
        target=bundles/Wowo/QueueBundle

    [pheanstalk]
        git=https://github.com/pda/pheanstalk
        target=/pheanstalk
        version=v1.1.0

```
Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

#### If you are using [Composer](http://getcomposer.org/) (Symfony >= 2.1.x)

Add following lines to your `composer.json` requirements:

``` json
    "require": {
        "wowo/wowo-queue-bundle": "dev-master"
    }

```
Now, install the bundle with composer:

``` bash
$ php composer.phar install
```

### Step 2: Configure the Autoloader

(You can jump to Step 3 if you are using composer)

Add the `Wowo` namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
        'Wowo' => __DIR__.'/../vendor/bundles',
        ));
```

Also add Pheanstalk init on the bottom of autoload:

``` php
// ...
require_once __DIR__.'/../vendor/pheanstalk/pheanstalk_init.php';
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
            new Wowo\QueueBundle\WowoQueueBundle(),
        );
}
```
### Step 4: install and run beanstalkd

On Debian linux systems (including Ubuntu) you can run:

``` bash
$ sudo apt-get install beanstalkd
```

Then run it as a daemon:

``` bash
$ beanstalkd -d -l 127.0.0.1 -p 11300
```

***Note:*** If your beanstalkd service is running in other address or port, you must set the following parameter in your configuration:

``` yaml
parameters:
    wowo_queue.pheanstalk.address: 127.0.0.1:11300
```

Don't forget to change ``` 127.0.0.1:11300 ``` with your address and port.

![tracking](http://visitspy.net/spot/d9dd2644/track)