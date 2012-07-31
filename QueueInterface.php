<?php

namespace Wowo\QueueBundle;

interface QueueInterface
{
    public function configure(array $options);
    public function put($job, $priority = null, $delay = null);
    public function get();
    public function delete($implementationSpecyficJobObject);
}
