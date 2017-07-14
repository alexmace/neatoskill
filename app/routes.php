<?php
// Routes

$app->get('/', App\Action\HomeAction::class)
    ->setName('homepage');

$app->post('/stephen', App\Action\StephenAction::class);
