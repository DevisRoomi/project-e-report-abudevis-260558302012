<?php

namespace Tests\Feature;

use App\Livewire\Admin;
use App\Livewire\Auth;
use App\Livewire\Warga;
use App\Models\Laporan;
use App\Models\User;
use App\Services\ComplaintManager;
use Database\Seeders\AdminSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class EReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('register:127.0.0.1');
        RateLimiter::clear('login:warga@example.test|127.0.0.1');
        Storage::fake('public');
    }

    public function test_registration_creates_and_authenticates_only_a_citizen(): void
    {
        Livewire::test(Auth\Register::class)
            ->set('name', 'Ayu Warga')->set('email', 'AYU@example.test')
            ->set('no_whatsapp', '081234567890')->set('alamat', 'Jalan Melati 10')
            ->set('password', 'Password123!')->set('password_confirmation', 'Password123!')
            ->call('register')->assertHasNoErrors()->assertRedirect(route('warga.dashboard'));

        $user = User::firstOrFail();
        $this->assertSame('warga', $user->role);
        $this->assertSame('ayu@example.test', $user->email);
        $this->assertTrue(Hash::check('Password123!', $user->password));
        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_validates_unique_email_and_password_confirmation(): void
    {
        User::factory()->create(['email' => 'ayu@example.test']);
        Livewire::test(Auth\Register::class)
            ->set('name', 'Ayu')->set('email', 'ayu@example.test')->set('no_whatsapp', 'abc')
            ->set('alamat', 'Sentul')->set('password', 'short')->set('password_confirmation', 'different')
            ->call('register')->assertHasErrors(['email', 'no_whatsapp', 'password']);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_login_redirects_citizen_to_their_dashboard(): void
    {
        $user = User::factory()->create();
        Livewire::test(Auth\Login::class)->set('email', $user->email)->set('password', 'Password123!')
            ->call('login')->assertHasNoErrors()->assertRedirect(route('warga.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_redirects_admin_to_admin_dashboard(): void
    {
        $user = User::factory()->admin()->create();
        Livewire::test(Auth\Login::class)->set('email', $user->email)->set('password', 'Password123!')
            ->call('login')->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_login_is_throttled(): void
    {
        User::factory()->create(['email' => 'warga@example.test']);
        for ($i = 0; $i < 5; $i++) {
            Livewire::test(Auth\Login::class)->set('email', 'warga@example.test')->set('password', 'incorrect')
                ->call('login')->assertHasErrors('email');
        }
        Livewire::test(Auth\Login::class)->set('email', 'warga@example.test')->set('password', 'Password123!')
            ->call('login')->assertHasErrors('email')->assertSee('Terlalu banyak percobaan');
        $this->assertGuest();
    }

    public function test_guests_cannot_access_either_dashboard(): void
    {
        $this->get('/warga/dashboard')->assertRedirect(route('login'));
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }

    public function test_citizen_cannot_access_any_admin_page(): void
    {
        $user = User::factory()->create();
        $laporan = Laporan::factory()->create();
        $this->actingAs($user);
        foreach (['/admin/dashboard', '/admin/laporan', '/admin/laporan/'.$laporan->id] as $path) {
            $this->get($path)->assertForbidden();
        }
    }

    public function test_admin_cannot_access_citizen_routes_or_create_component(): void
    {
        $this->actingAs(User::factory()->admin()->create());
        foreach (['/warga/dashboard', '/warga/laporan', '/warga/laporan/buat', '/warga/profile'] as $path) {
            $this->get($path)->assertForbidden();
        }
        Livewire::test(Warga\Laporan\Create::class)->assertForbidden();
        $this->assertDatabaseCount('laporans', 0);
    }

    public function test_citizen_can_create_a_pending_complaint_with_server_assigned_owner(): void
    {
        $user = User::factory()->create();
        Livewire::actingAs($user)->test(Warga\Laporan\Create::class)
            ->set('judul', 'Lampu jalan mati')->set('detail', 'Lampu di Jalan Melati mati sejak kemarin.')
            ->set('tanggal', today()->format('Y-m-d'))
            ->set('foto', UploadedFile::fake()->image('bukti.jpg'))
            ->call('save')->assertHasNoErrors();

        $laporan = Laporan::firstOrFail();
        $this->assertSame($user->id, $laporan->pelapor_id);
        $this->assertSame('pending', $laporan->status);
        Storage::disk('public')->assertExists($laporan->foto);
    }

    public function test_future_dates_and_invalid_images_are_rejected(): void
    {
        Livewire::actingAs(User::factory()->create())->test(Warga\Laporan\Create::class)
            ->set('judul', 'Jalan rusak')->set('detail', 'Jalan berlubang.')
            ->set('tanggal', today()->addDay()->format('Y-m-d'))
            ->set('foto', UploadedFile::fake()->create('malware.jpg', 10, 'text/plain'))
            ->call('save')->assertHasErrors(['tanggal', 'foto']);
        $this->assertDatabaseCount('laporans', 0);
    }

    public function test_oversized_photo_is_rejected(): void
    {
        Livewire::actingAs(User::factory()->create())->test(Warga\Laporan\Create::class)
            ->set('judul', 'Jalan rusak')->set('detail', 'Jalan berlubang.')
            ->set('tanggal', today()->format('Y-m-d'))
            ->set('foto', UploadedFile::fake()->image('large.jpg')->size(2049))
            ->call('save')->assertHasErrors('foto');
    }

    public function test_citizen_cannot_view_or_edit_another_citizens_complaint(): void
    {
        $laporan = Laporan::factory()->create();
        $this->actingAs(User::factory()->create());
        $this->get(route('warga.laporan.show', $laporan))->assertForbidden();
        $this->get(route('warga.laporan.edit', $laporan))->assertForbidden();
        Livewire::test(Warga\Laporan\Show::class, ['laporan' => $laporan])->assertForbidden();
        Livewire::test(Warga\Laporan\Edit::class, ['laporan' => $laporan])->assertForbidden();
    }

    public function test_citizen_can_edit_own_pending_complaint_and_replace_photo(): void
    {
        $user = User::factory()->create();
        $old = UploadedFile::fake()->image('old.jpg')->store('laporan', 'public');
        $laporan = Laporan::factory()->for($user, 'pelapor')->create(['foto' => $old]);
        Livewire::actingAs($user)->test(Warga\Laporan\Edit::class, ['laporan' => $laporan])
            ->set('judul', 'Judul diperbarui')->set('foto', UploadedFile::fake()->image('new.png'))
            ->call('save')->assertHasNoErrors()->assertRedirect(route('warga.laporan.show', $laporan));
        $this->assertSame('Judul diperbarui', $laporan->fresh()->judul);
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($laporan->fresh()->foto);
    }

    public function test_citizen_cannot_edit_processed_complaint(): void
    {
        $user = User::factory()->create();
        $laporan = Laporan::factory()->for($user, 'pelapor')->create(['status' => 'proses']);
        $this->actingAs($user)->get(route('warga.laporan.edit', $laporan))->assertForbidden();
    }

    public function test_stale_edit_is_rechecked_after_admin_starts_processing(): void
    {
        $user = User::factory()->create();
        $laporan = Laporan::factory()->for($user, 'pelapor')->create();
        $component = Livewire::actingAs($user)->test(Warga\Laporan\Edit::class, ['laporan' => $laporan]);
        $laporan->status = 'proses';
        $laporan->save();
        $component->call('save')->assertForbidden();
        $this->assertNotSame('Tidak boleh disimpan', $laporan->fresh()->judul);
    }

    public function test_citizen_cannot_delete_completed_complaint(): void
    {
        $user = User::factory()->create();
        $laporan = Laporan::factory()->for($user, 'pelapor')->create(['status' => 'selesai']);
        Livewire::actingAs($user)->test(Warga\Laporan\Index::class)->call('delete', $laporan->id)->assertForbidden();
        $this->assertModelExists($laporan);
    }

    public function test_citizen_cannot_delete_another_citizens_pending_complaint(): void
    {
        $laporan = Laporan::factory()->create();
        Livewire::actingAs(User::factory()->create())->test(Warga\Laporan\Index::class)
            ->call('delete', $laporan->id)->assertForbidden();
        $this->assertModelExists($laporan);
    }

    public function test_deleting_own_pending_complaint_removes_evidence(): void
    {
        $user = User::factory()->create();
        $photo = UploadedFile::fake()->image('photo.jpg')->store('laporan', 'public');
        $laporan = Laporan::factory()->for($user, 'pelapor')->create(['foto' => $photo]);
        Livewire::actingAs($user)->test(Warga\Laporan\Show::class, ['laporan' => $laporan])->call('delete')
            ->assertRedirect(route('warga.laporan.index'));
        $this->assertModelMissing($laporan);
        Storage::disk('public')->assertMissing($photo);
    }

    public function test_admin_can_view_all_reports_and_citizen_contact_details(): void
    {
        $reports = Laporan::factory()->count(2)->create();
        $this->actingAs(User::factory()->admin()->create());
        $this->get(route('admin.laporan.index'))->assertOk()->assertSee($reports[0]->judul)->assertSee($reports[1]->judul);
        $this->get(route('admin.laporan.show', $reports[0]))->assertOk()->assertSee($reports[0]->pelapor->email);
    }

    public function test_admin_can_transition_pending_to_proses_to_selesai(): void
    {
        $laporan = Laporan::factory()->create();
        $admin = User::factory()->admin()->create();
        $component = Livewire::actingAs($admin)->test(Admin\Laporan\Show::class, ['laporan' => $laporan]);
        $component->call('changeStatus', 'proses')->assertHasNoErrors();
        $this->assertSame('proses', $laporan->fresh()->status);
        $component->call('changeStatus', 'selesai')->assertHasNoErrors();
        $this->assertSame('selesai', $laporan->fresh()->status);
        $component->call('changeStatus', 'pending')->assertHasErrors('status');
    }

    public function test_admin_cannot_skip_or_reverse_status_transitions(): void
    {
        $laporan = Laporan::factory()->create();
        $component = Livewire::actingAs(User::factory()->admin()->create())
            ->test(Admin\Laporan\Show::class, ['laporan' => $laporan]);
        $component->call('changeStatus', 'selesai')->assertHasErrors('status');
        $this->assertSame('pending', $laporan->fresh()->status);
        $component->call('changeStatus', 'proses')->assertHasNoErrors();
        $component->call('changeStatus', 'pending')->assertHasErrors('status');
        $this->assertSame('proses', $laporan->fresh()->status);
    }

    public function test_citizen_cannot_invoke_admin_component_or_status_service(): void
    {
        $user = User::factory()->create();
        $laporan = Laporan::factory()->for($user, 'pelapor')->create();
        $this->actingAs($user);
        $this->assertFalse(Gate::allows('changeStatus', $laporan));
        Livewire::test(Admin\Laporan\Show::class, ['laporan' => $laporan])->assertForbidden();
        $this->expectException(AuthorizationException::class);
        app(ComplaintManager::class)->transition($laporan->id, 'proses');
    }

    public function test_profile_updates_only_current_user_and_replaces_photo(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $old = UploadedFile::fake()->image('old.jpg')->store('profil', 'public');
        $user->update(['foto' => $old]);
        Livewire::actingAs($user)->test(Warga\Profile::class)
            ->set('name', 'Nama Baru')->set('no_whatsapp', '+628123456789')->set('alamat', 'Alamat Baru')
            ->set('foto', UploadedFile::fake()->image('new.jpg'))
            ->call('updateProfile')->assertHasNoErrors();
        $this->assertSame('Nama Baru', $user->fresh()->name);
        $this->assertSame('Alamat Baru', $user->fresh()->alamat);
        $this->assertSame('+628123456789', $user->fresh()->no_whatsapp);
        $this->assertSame($other->name, $other->fresh()->name);
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($user->fresh()->foto);
    }

    public function test_profile_rejects_non_images(): void
    {
        Livewire::actingAs(User::factory()->create())->test(Warga\Profile::class)
            ->set('foto', UploadedFile::fake()->create('script.png', 5, 'text/plain'))
            ->call('updateProfile')->assertHasErrors('foto');
    }

    public function test_citizen_can_remove_profile_photo_and_cancel_staged_photo(): void
    {
        $user = User::factory()->create();
        $stored = UploadedFile::fake()->image('profil.jpg')->store('profil', 'public');
        $user->update(['foto' => $stored]);
        Storage::disk('public')->assertExists($stored);

        // Test cancel staged upload
        Livewire::actingAs($user)->test(Warga\Profile::class)
            ->set('foto', UploadedFile::fake()->image('temp.jpg'))
            ->assertSet('foto', fn($f) => $f !== null)
            ->call('batalPilihFoto')
            ->assertSet('foto', null);

        // Test delete photo
        Livewire::actingAs($user)->test(Warga\Profile::class)
            ->call('hapusFoto');

        $this->assertNull($user->fresh()->foto);
        Storage::disk('public')->assertMissing($stored);
    }

    public function test_password_change_requires_current_password_then_hashes_new_password(): void
    {
        $user = User::factory()->create();
        $component = Livewire::actingAs($user)->test(Warga\Profile::class)
            ->set('current_password', 'incorrect')->set('password', 'NewPassword123!')
            ->set('password_confirmation', 'NewPassword123!');
        $component->call('updatePassword')->assertHasErrors('current_password');
        $this->assertTrue(Hash::check('Password123!', $user->fresh()->password));
        $component->set('current_password', 'Password123!')->call('updatePassword')->assertHasNoErrors();
        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
        $component->assertSet('password', '')->assertSet('current_password', '');
    }

    public function test_citizen_dashboard_statistics_and_recent_reports_are_scoped(): void
    {
        $user = User::factory()->create();
        Laporan::factory()->for($user, 'pelapor')->count(2)->create();
        Laporan::factory()->for($user, 'pelapor')->create(['status' => 'proses']);
        $private = Laporan::factory()->create(['judul' => 'Rahasia warga lain', 'status' => 'selesai']);
        Livewire::actingAs($user)->test(Warga\Dashboard::class)
            ->assertViewHas('total', 3)->assertViewHas('stats', ['pending' => 2, 'proses' => 1, 'selesai' => 0])
            ->assertDontSee($private->judul);
        Livewire::test(Warga\Laporan\Index::class)->assertDontSee($private->judul);
    }

    public function test_admin_dashboard_statistics_are_database_derived(): void
    {
        $citizens = User::factory()->count(3)->create();
        foreach (['pending', 'proses', 'selesai', 'selesai'] as $status) {
            Laporan::factory()->for($citizens[0], 'pelapor')->create(['status' => $status]);
        }
        Livewire::actingAs(User::factory()->admin()->create())->test(Admin\Dashboard::class)
            ->assertViewHas('total', 4)->assertViewHas('citizens', 3)
            ->assertViewHas('stats', ['pending' => 1, 'proses' => 1, 'selesai' => 2]);
    }

    public function test_admin_searches_title_or_citizen_and_filters_and_sorts(): void
    {
        $user = User::factory()->create(['name' => 'Ayu Pelapor']);
        $first = Laporan::factory()->for($user, 'pelapor')->create(['judul' => 'Lampu Melati', 'created_at' => now()->subDay()]);
        $second = Laporan::factory()->create(['judul' => 'Sampah Mawar', 'status' => 'proses']);
        $component = Livewire::actingAs(User::factory()->admin()->create())->test(Admin\Laporan\Index::class);
        $component->set('search', 'Ayu Pelapor')->assertSee($first->judul)->assertDontSee($second->judul);
        $component->set('search', 'Sampah')->assertSee($second->judul)->assertDontSee($first->judul);
        $component->set('search', '')->set('status', 'proses')->assertSee($second->judul)->assertDontSee($first->judul);
        $component->set('status', '')->set('sort', 'oldest')
            ->assertViewHas('laporans', fn ($rows) => $rows->first()->id === $first->id);
        $component->set('sort', 'newest')->assertViewHas('laporans', fn ($rows) => $rows->first()->id === $second->id);
    }

    public function test_complaint_pagination_works(): void
    {
        $user = User::factory()->create();
        Laporan::factory()->for($user, 'pelapor')->count(11)->create();
        Livewire::actingAs($user)->test(Warga\Laporan\Index::class)
            ->assertViewHas('laporans', fn ($rows) => $rows->count() === 10 && $rows->total() === 11)
            ->call('nextPage')->assertViewHas('laporans', fn ($rows) => $rows->currentPage() === 2 && $rows->count() === 1);
    }

    public function test_sensitive_fields_are_not_mass_assignable(): void
    {
        $user = new User;
        $laporan = new Laporan;
        $this->assertFalse($user->isFillable('role'));
        $this->assertFalse($laporan->isFillable('pelapor_id'));
        $this->assertFalse($laporan->isFillable('status'));
    }

    public function test_missing_complaints_show_a_friendly_404(): void
    {
        $this->actingAs(User::factory()->create())->get('/warga/laporan/999')->assertNotFound()->assertSee('Halaman tidak ditemukan');
    }

    public function test_user_supplied_html_is_escaped_and_landing_exposes_no_private_data(): void
    {
        $user = User::factory()->create();
        $laporan = Laporan::factory()->for($user, 'pelapor')->create(['detail' => '<script>alert("unsafe")</script>', 'judul' => 'Pengaduan privat warga']);
        $this->get('/')->assertOk()->assertDontSee($laporan->judul)->assertDontSee($user->email);
        $this->actingAs($user)->get(route('warga.laporan.show', $laporan))->assertOk()
            ->assertSee('&lt;script&gt;alert', false)->assertDontSee('<script>alert("unsafe")</script>', false);
    }

    public function test_all_citizen_pages_render_successfully(): void
    {
        $user = User::factory()->create();
        $laporan = Laporan::factory()->for($user, 'pelapor')->create();
        $this->actingAs($user);
        foreach (['/warga/dashboard', '/warga/laporan', '/warga/laporan/buat', '/warga/profile', '/warga/laporan/'.$laporan->id, '/warga/laporan/'.$laporan->id.'/edit'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_authentication_pages_render_and_logout_ends_session(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->actingAs(User::factory()->create())->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_admin_seeder_requires_environment_password(): void
    {
        config(['ereport.admin_password' => null]);
        $this->expectException(ValidationException::class);
        $this->seed(AdminSeeder::class);
    }

    public function test_admin_seeder_never_promotes_or_overwrites_an_existing_account(): void
    {
        config(['ereport.admin_email' => 'admin@ereport.test', 'ereport.admin_password' => 'UniqueAdminPassword123!']);
        $user = User::factory()->create(['email' => 'admin@ereport.test']);
        $this->seed(AdminSeeder::class);
        $this->assertSame('warga', $user->fresh()->role);
        $this->assertTrue(Hash::check('Password123!', $user->fresh()->password));
    }
}