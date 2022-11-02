<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_all_books()
    {
        $books = Book::factory(4)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title,
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
    }

    /** @test */
    public function can_get_one_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title,
        ]);
    }

    /** @test */
    public function can_create_a_book()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
           'title' => 'My Book',
        ])->assertJsonFragment([
            'title' => 'My Book',
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'My Book',
        ]);
    }

    /** @test */
    public function can_update_a_book()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [
            'title' => 'Edited Book',
        ])->assertJsonFragment([
            'title' => 'Edited Book',
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Edited Book',
        ]);
    }

    /** @test */
    public function can_delete_a_book()
    {
        $book = Book::factory()->create();
        $this->deleteJson(route('books.destroy', $book))
        ->assertNoContent();

        $this->assertDatabaseCount('books', 0);

    }

}
