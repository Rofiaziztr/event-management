<?php

use App\Models\Category;
use App\Models\User;

it('allows admin to create and delete categories', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $createResponse = $this->actingAs($admin)->get('/admin/categories/create');
    $createResponse->assertStatus(200);
    $token = session('_token');

    $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
        '_token' => $token,
        'name' => 'Kategori',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('categories', ['name' => 'Kategori']);

    $category = Category::where('name', 'Kategori')->first();
    expect($category)->not->toBeNull();

    $response = $this->actingAs($admin)->post(route('admin.categories.destroy', $category->slug), ['_method' => 'DELETE', '_token' => session('_token')]);
    $response->assertRedirect();
    $this->assertSoftDeleted('categories', ['id' => $category->id]);
});
