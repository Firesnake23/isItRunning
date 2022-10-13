<?php

namespace firesnake\isItRunning\controllers;

use Cron\CronExpression;
use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\TextResponse;

class CronController
{
    public function getRuns(RequestEvent $event)
    {
        $request = $event->getRequest();

        $resultArr = [
            'status' => 'error'
        ];

        if(isset($request->getPost()['cronString'])) {
            try {
                $expression = new CronExpression($request->getPost()['cronString']);

                $results = $expression->getMultipleRunDates(5, 'now', false, true);

                $arr = [];
                foreach ($results as $result) {
                    $arr[] = $result->format('Y-m-d H:i:s');
                }

                $resultArr['status'] = 'success';
                $resultArr['data'] = $arr;
            } catch (\InvalidArgumentException $e) {
                /*nop*/
            }
        }

        return new TextResponse(json_encode($resultArr));
    }
}