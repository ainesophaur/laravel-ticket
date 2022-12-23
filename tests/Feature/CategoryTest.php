<?php

use Coderflex\LaravelTicket\Models\Category;
use Coderflex\LaravelTicket\Models\Ticket;

it('can attach category to a ticket', function () {
    $category = Category::factory()->create();
    $ticket = Ticket::factory()->create();

    $category->tickets()->attach($ticket);

    $this->assertEquals($category->tickets->count(), 1);
});

it('can deattach category to a ticket', function () {
    $category = Category::factory()->create();
    $ticket = Ticket::factory()->create();

    $ticket->attachCategories($category);

    $category->tickets()->detach($ticket);

    $this->assertEquals($category->tickets->count(), 0);
});

it('gets categories by visibility status', function () {
    Category::factory()->times(10)->create([
        'is_visible' => true,
    ]);

    Category::factory()->times(9)->create([
        'is_visible' => false,
    ]);

    $this->assertEquals(Category::count(), 19);
    $this->assertEquals(Category::visible()->count(), 10);
    $this->assertEquals(Category::hidden()->count(), 9);
});

it('can use custom model for category', function () {
    app()['config']->set('laravel_ticket.models.category', \Coderflex\LaravelTicket\Tests\Models\Category::class);
    $ticket = Ticket::factory()->create([
        'title' => 'Can you create a message?',
    ]);

    expect($ticket->categories()->make())->toBeInstanceOf(\Coderflex\LaravelTicket\Tests\Models\Category::class);

});

it('null custom model for category uses default model', function () {
    app()['config']->set('laravel_ticket.models.category', null);
    $ticket = Ticket::factory()->create([
        'title' => 'Can you create a message?',
    ]);

    expect($ticket->categories()->make())->toBeInstanceOf(\Coderflex\LaravelTicket\Models\Category::class);

});
