<?php

namespace Coderflex\LaravelTicket;

use Coderflex\LaravelTicket\Exceptions\InvalidConfiguration;
use Coderflex\LaravelTicket\Models\Category as CategoryModel;
use Coderflex\LaravelTicket\Models\Label as LabelModel;
use Coderflex\LaravelTicket\Models\Message as MessageModel;
use Coderflex\LaravelTicket\Models\Ticket as TicketModel;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class LaravelTicketServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-ticket')
            ->hasConfigFile('laravel_ticket')
            ->hasMigrations(
                'create_tickets_table',
                'create_messages_table',
                'create_categories_table',
                'create_labels_table',
                'create_category_ticket_table',
                'create_label_ticket_table',
                'add_assigned_to_column_into_tickets_table',
            );
    }

    public static function determineCategoryModel(): string
    {
        $categoryModel = config('laravel_ticket.models.category') ?? CategoryModel::class;
        return self::determinePackageModel($categoryModel, CategoryModel::class);
    }

    public static function determineLabelModel(): string
    {
        $labelModel = config('laravel_ticket.models.label') ?? LabelModel::class;
        return self::determinePackageModel($labelModel, LabelModel::class);
    }

    public static function determineMessageModel(): string
    {
        $messageModel = config('laravel_ticket.models.message') ?? MessageModel::class;
        return self::determinePackageModel($messageModel, MessageModel::class);
    }

    public static function determineTicketModel(): string
    {
        $ticketModel = config('laravel_ticket.models.ticket') ?? TicketModel::class;
        return self::determinePackageModel($ticketModel, TicketModel::class);
    }

    /**
     * @param class-string $modelClass
     * @param class-string $baseClass
     * @return class-string string
     * @throws InvalidConfiguration
     */
    protected static function determinePackageModel($modelClass, $baseClass): string
    {
        if (! (is_a($modelClass, $baseClass, true)
            || is_subclass_of($modelClass, $baseClass, true))) {
            throw InvalidConfiguration::modelIsNotValid($modelClass, $baseClass);
        }

        return $modelClass;
    }

}
