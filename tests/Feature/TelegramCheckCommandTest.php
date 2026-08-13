<?php

declare(strict_types=1);

use App\Models\QuoteRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    config()->set('services.telegram.bot_token', '123:test-token');
    config()->set('services.telegram.chat_id', '-1001234567890');
    config()->set('services.telegram.thread_id', null);
});

/**
 * Register Bot API fakes. Stubs are matched in registration order, so each
 * test builds its full set in one call rather than layering extra Http::fake()s.
 */
function fakeTelegram(array $overrides = []): void
{
    Http::fake(array_merge([
        '*/getMe' => Http::response(['ok' => true, 'result' => ['username' => 'testbot', 'first_name' => 'Test']]),
        '*/getChat' => Http::response(['ok' => true, 'result' => ['title' => 'Sales', 'type' => 'supergroup']]),
        '*/sendMessage' => Http::response(['ok' => true]),
    ], $overrides));
}

it('sends a sample lead to the group', function (): void {
    fakeTelegram();

    $this->artisan('telegram:check')->assertExitCode(0);

    Http::assertSent(fn (Request $r): bool => str_ends_with($r->url(), '/sendMessage')
        && str_contains($r['text'], 'Тестовая заявка'));

    // The sample must never be persisted.
    expect(QuoteRequest::count())->toBe(0);
});

it('verifies the token and chat without sending on a dry run', function (): void {
    fakeTelegram();

    $this->artisan('telegram:check', ['--dry' => true])->assertExitCode(0);

    Http::assertSent(fn (Request $r): bool => str_ends_with($r->url(), '/getMe'));
    Http::assertSent(fn (Request $r): bool => str_ends_with($r->url(), '/getChat'));
    Http::assertNotSent(fn (Request $r): bool => str_ends_with($r->url(), '/sendMessage'));
});

it('sends a custom message when one is given', function (): void {
    fakeTelegram();

    $this->artisan('telegram:check', ['--message' => 'ping from ci'])->assertExitCode(0);

    Http::assertSent(fn (Request $r): bool => str_ends_with($r->url(), '/sendMessage')
        && $r['text'] === 'ping from ci');
});

it('resends the latest quote request', function (): void {
    fakeTelegram();

    QuoteRequest::create(['name' => 'Старая', 'phone' => '+998900000000', 'status' => 'new']);
    QuoteRequest::create(['name' => 'Свежая', 'phone' => '+998901111111', 'status' => 'new']);

    $this->artisan('telegram:check', ['--latest' => true])->assertExitCode(0);

    Http::assertSent(fn (Request $r): bool => str_ends_with($r->url(), '/sendMessage')
        && str_contains($r['text'], 'Свежая'));
});

it('fails when the bot token is missing', function (): void {
    fakeTelegram();

    config()->set('services.telegram.bot_token', null);

    $this->artisan('telegram:check')->assertExitCode(1);

    Http::assertNothingSent();
});

it('fails when the chat id is missing', function (): void {
    fakeTelegram();

    config()->set('services.telegram.chat_id', null);

    $this->artisan('telegram:check')->assertExitCode(1);

    Http::assertNothingSent();
});

it('fails and explains when telegram rejects the token', function (): void {
    fakeTelegram([
        '*/getMe' => Http::response(['ok' => false, 'description' => 'Unauthorized'], 401),
    ]);

    $this->artisan('telegram:check')
        ->expectsOutputToContain('Unauthorized')
        ->assertExitCode(1);

    Http::assertNotSent(fn (Request $r): bool => str_ends_with($r->url(), '/sendMessage'));
});

it('fails and explains when the chat cannot be found', function (): void {
    fakeTelegram([
        '*/getChat' => Http::response(['ok' => false, 'description' => 'Bad Request: chat not found'], 400),
    ]);

    $this->artisan('telegram:check')
        ->expectsOutputToContain('chat not found')
        ->assertExitCode(1);

    Http::assertNotSent(fn (Request $r): bool => str_ends_with($r->url(), '/sendMessage'));
});
