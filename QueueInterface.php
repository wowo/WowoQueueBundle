<?php

namespace Wowo\Bundle\QueueBundle;

interface QueueInterface
{
    public function configure(array $options);
    public function put($job);
    public function get();
    public function delete();
    public function clear();
}
