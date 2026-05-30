<?php

use App\Models\Institution;
use App\Models\User;

it('lets a super admin access the selected active institution without an assignment', function () {
    $user = User::factory()->create(['is_super_admin' => true]);
    $institution = Institution::query()->create([
        'name' => 'Selected College',
        'slug' => 'selected-college',
        'status' => 'active',
    ]);

    $this->actingAs($user, 'web')
        ->post(route('institution.select.store'), [
            'institution_id' => $institution->id,
        ])
        ->assertRedirect(route('institution.dashboard'));

    $this->assertSame($institution->id, session('active_institution_id'));

    $this->actingAs($user, 'web')
        ->withSession(['active_institution_id' => $institution->id])
        ->get(route('institution.dashboard'))
        ->assertOk();
});
