<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\QuoteRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramNotifier
{
    private ?string $token;

    private ?string $chatId;

    private ?string $threadId;

    private int $timeout;

    private int $tries;

    private string $timezone;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.chat_id');
        $this->threadId = config('services.telegram.thread_id');
        $this->timeout = (int) config('services.telegram.timeout', 5);
        $this->tries = (int) config('services.telegram.tries', 2);
        $this->timezone = (string) config('services.telegram.timezone', 'Asia/Tashkent');
    }

    public function isConfigured(): bool
    {
        return filled($this->token) && filled($this->chatId);
    }

    public function hasToken(): bool
    {
        return filled($this->token);
    }

    public function chatId(): ?string
    {
        return $this->chatId;
    }

    public function threadId(): ?string
    {
        return $this->threadId;
    }

    /**
     * Bot token with the secret half masked, safe to print in a terminal.
     */
    public function maskedToken(): string
    {
        if (! filled($this->token)) {
            return '—';
        }

        $parts = explode(':', $this->token, 2);

        if (count($parts) !== 2) {
            return str_repeat('*', 8);
        }

        return $parts[0].':'.str_repeat('*', 8).substr($parts[1], -4);
    }

    /**
     * Call a Bot API method. Unlike send(), transport errors bubble up so
     * callers such as the telegram:check command can report the real cause.
     */
    public function call(string $apiMethod, array $payload = []): Response
    {
        return Http::timeout($this->timeout)
            ->retry($this->tries, 250, throw: false)
            ->post("https://api.telegram.org/bot{$this->token}/{$apiMethod}", $payload);
    }

    /**
     * Build the sendMessage payload for a message aimed at the sales group.
     */
    public function messagePayload(string $message): array
    {
        $payload = [
            'chat_id' => $this->chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
            'link_preview_options' => ['is_disabled' => true],
        ];

        if (filled($this->threadId)) {
            $payload['message_thread_id'] = (int) $this->threadId;
        }

        return $payload;
    }

    /**
     * Render the group message for a quote request without sending it.
     */
    public function previewQuoteRequest(QuoteRequest $quoteRequest): string
    {
        return $this->formatQuoteRequest($quoteRequest);
    }

    /**
     * Notify the sales group about a new quote request.
     *
     * Never throws: a failed notification must not break the form submission.
     */
    public function sendQuoteRequest(QuoteRequest $quoteRequest): bool
    {
        return $this->send($this->formatQuoteRequest($quoteRequest));
    }

    public function send(string $message): bool
    {
        if (! $this->isConfigured()) {
            Log::debug('Telegram notification skipped: bot token or chat id is not set.');

            return false;
        }

        try {
            $response = $this->call('sendMessage', $this->messagePayload($message));

            if ($response->failed()) {
                Log::warning('Telegram notification failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            return true;
        } catch (Throwable $e) {
            Log::warning('Telegram notification threw an exception.', [
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function formatQuoteRequest(QuoteRequest $quoteRequest): string
    {
        $lines = ['🆕 <b>Новая заявка с сайта</b>', ''];

        $fields = [
            '👤 Имя' => $quoteRequest->name,
            '🏢 Компания' => $quoteRequest->company,
            '📞 Телефон' => $quoteRequest->phone,
            '✉️ Email' => $quoteRequest->email,
            '🧵 Продукция' => $quoteRequest->product_interest,
            '📦 Объем' => $quoteRequest->quantity,
        ];

        foreach ($fields as $label => $value) {
            if (filled($value)) {
                $lines[] = "<b>{$label}:</b> ".e($value);
            }
        }

        if (filled($quoteRequest->message)) {
            $lines[] = '';
            $lines[] = '💬 <b>Сообщение:</b>';
            $lines[] = e($quoteRequest->message);
        }

        $createdAt = $quoteRequest->created_at ?? now();

        $lines[] = '';
        $lines[] = '🕒 '.$createdAt->copy()->setTimezone($this->timezone)->format('d.m.Y H:i');

        if ($quoteRequest->exists) {
            $url = url("/admin/quote-requests/{$quoteRequest->getKey()}/edit");
            $lines[] = '🔗 <a href="'.e($url).'">Открыть в админке</a>';
        }

        return implode("\n", $lines);
    }
}
