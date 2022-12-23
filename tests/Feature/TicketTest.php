<?php

use Coderflex\LaravelTicket\Models\Ticket;
use Coderflex\LaravelTicket\Tests\Models\User;

it('filters tickets by status', function () {
    Ticket::factory()
            ->times(3)
            ->create([
                'status' => 'open',
            ]);

    Ticket::factory()
            ->times(7)
            ->create([
                'status' => 'closed',
            ]);

    $this->assertEquals(Ticket::count(), 10);
    $this->assertEquals(Ticket::opened()->count(), 3);
    $this->assertEquals(Ticket::closed()->count(), 7);
});

it('filters tickets by resolved status', function () {
    Ticket::factory()
            ->times(2)
            ->create([
                'is_resolved' => true,
            ]);

    Ticket::factory()
            ->times(10)
            ->create([
                'is_resolved' => false,
            ]);

    $this->assertEquals(Ticket::count(), 12);
    $this->assertEquals(Ticket::resolved()->count(), 2);
    $this->assertEquals(Ticket::unresolved()->count(), 10);
});

it('filters tickets by locked status', function () {
    Ticket::factory()
            ->times(3)
            ->create([
                'is_locked' => true,
            ]);

    Ticket::factory()
            ->times(9)
            ->create([
                'is_locked' => false,
            ]);

    $this->assertEquals(Ticket::count(), 12);
    $this->assertEquals(Ticket::locked()->count(), 3);
    $this->assertEquals(Ticket::unlocked()->count(), 9);
});

it('filters tickets by priority status', function () {
    Ticket::factory()
            ->times(7)
            ->create([
                'priority' => 'low',
            ]);

    Ticket::factory()
            ->times(5)
            ->create([
                'priority' => 'normal',
            ]);

    Ticket::factory()
            ->times(15)
            ->create([
                'priority' => 'high',
            ]);

    Ticket::factory()
            ->times(15)
            ->create([
                'priority' => 'something else',
            ]);

    $this->assertEquals(Ticket::count(), 42);
    $this->assertEquals(Ticket::withLowPriority()->count(), 7);
    $this->assertEquals(Ticket::withNormalPriority()->count(), 5);
    $this->assertEquals(Ticket::withHighPriority()->count(), 15);
    $this->assertEquals(Ticket::withPriority('something else')->count(), 15);
});

it('can close the ticket', function () {
    $ticket = Ticket::factory()->create([
        'status' => 'open',
    ]);

    $ticket->close();

    $this->assertEquals($ticket->status, 'closed');
});

it('can reopen the ticket', function () {
    $ticket = Ticket::factory()->create([
        'status' => 'closed',
    ]);

    $ticket->reopen();

    $this->assertEquals($ticket->status, 'open');
});

it('can check if the ticket is open/closed/archived', function () {
    $ticket = Ticket::factory()->create([
        'status' => 'open',
    ]);

    $archivedTicket = Ticket::factory()->create([
        'status' => 'archived',
    ]);

    $closedTicket = Ticket::factory()->create([
        'status' => 'closed',
    ]);

    $this->assertTrue($ticket->isOpen());
    $this->assertTrue($closedTicket->isClosed());
    $this->assertTrue($archivedTicket->isArchived());
});

it('can check if the ticket is resolved or unresolved', function () {
    $ticket = Ticket::factory()->create([
        'is_resolved' => true,
    ]);

    $anotherTicket = Ticket::factory()->create([
        'is_resolved' => false,
    ]);

    $this->assertTrue($ticket->isResolved());
    $this->assertTrue($anotherTicket->isUnresolved());
});

it('can mark a ticket as archived', function () {
    $ticket = Ticket::factory()->create([
        'status' => 'open',
    ]);

    $ticket->markAsArchived();

    $this->assertTrue($ticket->isArchived());
});

it('can mark a ticket as resolved', function () {
    $ticket = Ticket::factory()->create([
        'is_resolved' => false,
    ]);

    $ticket->markAsResolved();

    $this->assertTrue($ticket->isResolved());
});

it('can mark a ticket as locked', function () {
    $ticket = Ticket::factory()->create([
        'is_locked' => false,
    ]);

    $ticket->markAsLocked();

    $this->assertTrue($ticket->isLocked());
});

it('can mark a ticket as unlocked', function () {
    $ticket = Ticket::factory()->create([
        'is_locked' => true,
    ]);

    $ticket->markAsUnlocked();

    $this->assertTrue($ticket->isUnlocked());
});

it('can mark a ticket as closed & resolved', function () {
    $ticket = Ticket::factory()->create([
        'is_resolved' => false,
        'status' => 'open',
    ]);

    $ticket->closeAsResolved();

    $this->assertTrue($ticket->isResolved());
    $this->assertTrue($ticket->isClosed());
});

it('can mark a ticket as closed & unresolved', function () {
    $ticket = Ticket::factory()->create([
        'is_resolved' => true,
        'status' => 'open',
    ]);

    $ticket->closeAsUnresolved();

    $this->assertTrue($ticket->isUnresolved());
    $this->assertTrue($ticket->isClosed());
});

it('can mark a ticket as reopened & unresolved', function () {
    $ticket = Ticket::factory()->create([
        'is_resolved' => true,
        'status' => 'closed',
    ]);

    $ticket->reopenAsUnresolved();

    $this->assertTrue($ticket->isUnresolved());
    $this->assertTrue($ticket->isOpen());
});

it('can mark a ticket as locked & unlocked', function () {
    $ticket = Ticket::factory()->create([
        'is_locked' => false,
    ]);

    $lockedTicket = Ticket::factory()->create([
        'is_locked' => true,
    ]);

    $ticket->reopenAsUnresolved();

    $this->assertTrue($ticket->isUnlocked());
    $this->assertTrue($lockedTicket->isLocked());
});

it('ensures ticket methods as chainable', function () {
    $ticket = Ticket::factory()->create([
        'status' => 'open',
        'is_locked' => false,
    ]);

    $ticket->archive()
            ->markAsLocked();

    $this->assertTrue($ticket->isArchived());
    $this->assertTrue($ticket->isLocked());
});

it('can delete a ticket', function () {
    $ticket = Ticket::factory()->create();

    $ticket->delete();

    $this->assertEquals(Ticket::count(), 0);
});

it('can assign ticket to a user using user model', function () {
    $ticket = Ticket::factory()->create();
    $agentUser = User::factory()->create();

    $ticket->assignTo($agentUser);

    expect($ticket->assigned_to)
        ->toBe($agentUser);
});

it('can assign ticket to a user using user id', function () {
    $ticket = Ticket::factory()->create();
    $agentUser = User::factory()->create();

    $ticket->assignTo($agentUser->id);

    expect($ticket->assigned_to)
        ->toBe($agentUser->id);
});



it('can use custom model for ticket', function () {
    app()['config']->set('laravel_ticket.models.ticket', \Coderflex\LaravelTicket\Tests\Models\Ticket::class);
    $agentUser = User::factory()->create();

    expect($agentUser->tickets()->make())->toBeInstanceOf(\Coderflex\LaravelTicket\Tests\Models\Ticket::class);

});

it('null custom model for ticket uses default model', function () {
    app()['config']->set('laravel_ticket.models.ticket', null);
    $agentUser = User::factory()->create();

    expect($agentUser->tickets()->make())->toBeInstanceOf(\Coderflex\LaravelTicket\Models\Ticket::class);

});
