<?php

use Coderflex\LaravelTicket\Models\Label;
use Coderflex\LaravelTicket\Models\Ticket;

it('can attach label to a ticket', function () {
    $label = Label::factory()->create();
    $ticket = Ticket::factory()->create();

    $label->tickets()->attach($ticket);

    $this->assertEquals($label->tickets->count(), 1);
});

it('can deattach label to a ticket', function () {
    $label = Label::factory()->create();
    $ticket = Ticket::factory()->create();

    $ticket->attachLabels($label);

    $label->tickets()->detach($ticket);

    $this->assertEquals($label->tickets->count(), 0);
});

it('gets labels by visibility status', function () {
    Label::factory()->times(7)->create([
        'is_visible' => true,
    ]);

    Label::factory()->times(6)->create([
        'is_visible' => false,
    ]);

    $this->assertEquals(Label::count(), 13);
    $this->assertEquals(Label::visible()->count(), 7);
    $this->assertEquals(Label::hidden()->count(), 6);
});


it('can use custom model for label', function () {
    app()['config']->set('laravel_ticket.models.label', \Coderflex\LaravelTicket\Tests\Models\Label::class);
    $ticket = Ticket::factory()->create([
        'title' => 'Can you create a message?',
    ]);

    expect($ticket->labels()->make())->toBeInstanceOf(\Coderflex\LaravelTicket\Tests\Models\Label::class);

});

it('null custom model for label uses default model', function () {
    app()['config']->set('laravel_ticket.models.label', null);
    $ticket = Ticket::factory()->create([
        'title' => 'Can you create a message?',
    ]);

    expect($ticket->labels()->make())->toBeInstanceOf(\Coderflex\LaravelTicket\Models\Label::class);

});
