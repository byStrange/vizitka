<?php

declare(strict_types=1);

use App\Models\QuoteRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    config()->set('services.telegram.bot_token', 'test-token');
    config()->set('services.telegram.chat_id', '-1001234567890');
    config()->set('services.telegram.thread_id', null);
});

it('sends a telegram message when a quote request is submitted', function (): void {
    Http::fake([
        'api.telegram.org/*' => Http::response(['ok' => true]),
    ]);

    $this->post('/contact', [
        'name' => 'Иван Петров',
        'company' => 'ООО Ромашка',
        'phone' => '+998901234567',
        'email' => 'ivan@example.com',
        'product_interest' => 'Футболки',
        'quantity' => '500 шт',
        'message' => 'Нужен расчёт стоимости',
    ])->assertRedirect();

    expect(QuoteRequest::count())->toBe(1);

    Http::assertSent(function (Request $request): bool {
        return $request->url() === 'https://api.telegram.org/bottest-token/sendMessage'
            && $request['chat_id'] === '-1001234567890'
            && $request['parse_mode'] === 'HTML'
            && str_contains($request['text'], 'Иван Петров')
            && str_contains($request['text'], '+998901234567')
            && str_contains($request['text'], 'Футболки')
            && str_contains($request['text'], 'Нужен расчёт стоимости');
    });
});

it('escapes html in user supplied values', function (): void {
    Http::fake([
        'api.telegram.org/*' => Http::response(['ok' => true]),
    ]);

    $this->post('/contact', [
        'name' => '<b>hacker</b>',
        'phone' => '+998901234567',
    ]);

    Http::assertSent(function (Request $request): bool {
        return str_contains($request['text'], '&lt;b&gt;hacker&lt;/b&gt;')
            && ! str_contains($request['text'], '<b>hacker</b>');
    });
});

it('includes the thread id when the group uses topics', function (): void {
    config()->set('services.telegram.thread_id', '42');

    Http::fake([
        'api.telegram.org/*' => Http::response(['ok' => true]),
    ]);

    $this->post('/contact', [
        'name' => 'Иван',
        'phone' => '+998901234567',
    ]);

    Http::assertSent(fn (Request $request): bool => $request['message_thread_id'] === 42);
});

it('does not call telegram when it is not configured', function (): void {
    config()->set('services.telegram.bot_token', null);
    config()->set('services.telegram.chat_id', null);

    Http::fake();

    $this->post('/contact', [
        'name' => 'Иван',
        'phone' => '+998901234567',
    ])->assertRedirect();

    expect(QuoteRequest::count())->toBe(1);

    Http::assertNothingSent();
});

it('still saves the lead when telegram is down', function (): void {
    config()->set('services.telegram.tries', 1);

    Http::fake([
        'api.telegram.org/*' => Http::response('Bad Gateway', 502),
    ]);

    $this->post('/contact', [
        'name' => 'Иван',
        'phone' => '+998901234567',
    ])->assertRedirect()->assertSessionHas('success');

    expect(QuoteRequest::count())->toBe(1);
});
