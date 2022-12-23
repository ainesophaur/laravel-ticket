<?php

use Coderflex\LaravelTicket\Models\Message;
use Coderflex\LaravelTicket\Models\Ticket;

it('can attach message to a ticket', function () {
    $message = Message::factory()->create();
    $ticket = Ticket::factory()->create([
        'title' => 'Can you create a message?',
    ]);

    $message->ticket()->associate($ticket);

    $this->assertEquals($message->ticket->title, 'Can you create a message?');
});

it('can use custom model for message', function () {
    app()['config']->set('laravel_ticket.models.message', \Coderflex\LaravelTicket\Tests\Models\Message::class);
    $ticket = Ticket::factory()->create([
        'title' => 'Can you create a message?',
    ]);

    expect($ticket->messages()->make())->toBeInstanceOf(\Coderflex\LaravelTicket\Tests\Models\Message::class);

});

it('null custom model for message uses default model', function () {
    app()['config']->set('laravel_ticket.models.message', null);
    $ticket = Ticket::factory()->create([
        'title' => 'Can you create a message?',
    ]);

    expect($ticket->messages()->make())->toBeInstanceOf(\Coderflex\LaravelTicket\Models\Message::class);

});
