<?php

namespace Wowo\QueueBundle;

interface QueueImplementationInterface
{
    public function put($tube, $job, $priority = null, $delay = null);
    public function get($tube);
    public function delete($implementationSpecyficJobObject);
}
