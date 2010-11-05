<?php

require_once '../autoload.php';

$item = TeamWorkPm::factory(TeamWorkPm::TODO_ITEM);
$item->reOrder(46487, array(
    224848,
    224870,
    224871
));
