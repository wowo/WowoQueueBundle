<?php

namespace Wowo\QueueBundle;

interface QueueImplementationInterface
{
    public function put($tube, $job, $priority = null, $delay = null);
    public function get($tube, $secondsToWait);
    public function release($tube, $job, $priority = null, $delay = null);
    public function delete($implementationSpecyficJobObject);
}
