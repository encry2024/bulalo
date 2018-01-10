<?php

namespace App\Listeners\Backend\Category;

/**
* Class CategoryEventListener.
*/
class CategoryEventListener
{
    /**
     * @var string
     */
    private $history_slug = 'Category';

    /**
     * @param $event
     */
    public function onCreated($event)
    {
        history()->withType($this->history_slug)
        ->withEntity($event->category->id)
        ->withText('trans("history.backend.category.created") <strong>{category}</strong>')
        ->withIcon('plus')
        ->withClass('bg-green')
        ->withAssets(
            [
                'category_link' => ['admin.category.show', $event->category->name, $event->category->id],
            ]
        )
        ->log();
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        history()->withType($this->history_slug)
        ->withEntity($event->category->id)
        ->withText('trans("history.backend.category.updated") <strong>{category}</strong>')
        ->withIcon('save')
        ->withClass('bg-aqua')
        ->withAssets(
            [
                'category_link' => ['admin.category.show', $event->category->name, $event->category->id],
            ]
        )
        ->log();
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        history()->withType($this->history_slug)
        ->withEntity($event->category->id)
        ->withText('trans("history.backend.category.deleted") <strong>{category}</strong>')
        ->withIcon('trash')
        ->withClass('bg-maroon')
        ->withAssets(
            [
                'category_link' => ['admin.category.show', $event->category->name, $event->category->id],
            ]
        )
        ->log();
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        history()->withType($this->history_slug)
        ->withEntity($event->category->id)
        ->withText('trans("history.backend.category.restored") <strong>{category}</strong>')
        ->withIcon('refresh')
        ->withClass('bg-aqua')
        ->withAssets(
            [
                'category_link' => ['admin.category.show', $event->category->name, $event->category->id],
            ]
        )
        ->log();
    }

    /**
     * @param $event
     */
    public function onPermanentlyDeleted($event)
    {
        history()->withType($this->history_slug)
        ->withEntity($event->category->id)
        ->withText('trans("history.backend.category.permanently_deleted") <strong>{category}</strong>')
        ->withIcon('trash')
        ->withClass('bg-maroon')
        ->withAssets(
            [
                'category_string' => $event->category->name,
            ]
        )
        ->log();

        history()->withType($this->history_slug)
        ->withEntity($event->category->id)
        ->withAssets(
            [
            'category_string' => $event->category->name,
        ])
        ->updateUserLinkCategories();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            \App\Events\Backend\Category\CategoryCreated::class,
            'App\Listeners\Backend\Category\CategoryEventListener@onCreated'
        );

        $events->listen(
            \App\Events\Backend\Category\CategoryUpdated::class,
            'App\Listeners\Backend\Category\CategoryEventListener@onUpdated'
        );

        $events->listen(
            \App\Events\Backend\Category\CategoryDeleted::class,
            'App\Listeners\Backend\Category\CategoryEventListener@onDeleted'
        );

        $events->listen(
            \App\Events\Backend\Category\CategoryRestored::class,
            'App\Listeners\Backend\Category\CategoryEventListener@onRestored'
        );

        $events->listen(
            \App\Events\Backend\Category\CategoryPermanentlyDeleted::class,
            'App\Listeners\Backend\Category\CategoryEventListener@onPermanentlyDeleted'
        );
    }
}
